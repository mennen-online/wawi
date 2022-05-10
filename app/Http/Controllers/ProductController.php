<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductProcessImportRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Imports\ProductsImport;
use App\Imports\Wave\ProductImport;
use App\Jobs\Vendor\ImportVendorProducts;
use App\Models\Offer;
use App\Models\OfferVendorProduct;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

/**
 *
 */
class ProductController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $this->authorize('view-any', Product::class);

        $search = $request->get('search', '');

        $products = Product::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.products.index', compact('products', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $this->authorize('create', Product::class);

        return view('app.products.create');
    }

    /**
     * @param \App\Http\Requests\ProductStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request) {
        $this->authorize('create', Product::class);

        $validated = $request->validated();
        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request
                ->file('image_url')
                ->store('public');
        }

        $product = Product::create($validated);

        return redirect()
            ->route('products.edit', $product)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product) {
        $this->authorize('view', $product);

        return view('app.products.show', compact('product'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Product $product) {
        $this->authorize('update', $product);

        return view('app.products.edit', compact('product'));
    }

    /**
     * @param \App\Http\Requests\ProductUpdateRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product) {
        $this->authorize('update', $product);

        $validated = $request->validated();
        if ($request->hasFile('image_url')) {
            if ($product->image_url) {
                Storage::delete($product->image_url);
            }

            $validated['image_url'] = $request
                ->file('image_url')
                ->store('public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.edit', $product)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product) {
        $this->authorize('delete', $product);

        if ($product->image_url) {
            Storage::delete($product->image_url);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->withSuccess(__('crud.common.removed'));
    }

    /**
     * Return a View with Vendors to select one for Product Import
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function import(Request $request) {
        return view('app.products.import')
            ->with('vendors', Vendor::all())
            ->with('editing', null);
    }

    /**
     * Fetch selected Vendor and dispatch a Asynchronous Job to import / update their Products
     *
     * @param  ProductProcessImportRequest  $processImportRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processImport(ProductProcessImportRequest $processImportRequest) {
        $vendor = Vendor::find($processImportRequest->input('vendor_id'));

        ImportVendorProducts::dispatch($vendor);

        return to_route('products.import');
    }

    /**
     * @param  Request  $request
     * @param  Offer  $offer
     * @param  VendorProduct  $vendorProduct
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignToOffer(Request $request, Offer $offer, VendorProduct $vendorProduct) {
        if($offerVendorProduct = OfferVendorProduct::whereVendorProductId($vendorProduct->id)->whereOfferId($offer->id)->first()) {
            $offerVendorProduct->increment('quantity');
        }else {
            $offer->vendorProducts()->attach($vendorProduct);
        }

        return to_route('vendor-products.index');
    }
}
