<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\VendorProduct;
use App\Http\Requests\VendorProductStoreRequest;
use App\Http\Requests\VendorProductUpdateRequest;

class VendorProductController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $this->authorize('view-any', VendorProduct::class);

        $search = $request->get('search', '');

        $products = Product::search($search)->take(1000)->get()->pluck('id');

        $vendorProducts = VendorProduct::whereIn('product_id', $products)->paginate(10)->withQueryString();

        $offers = $request->user()->offers()->get();

        return view(
            'app.vendor_products.index',
            compact('vendorProducts', 'search', 'offers')
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $this->authorize('create', VendorProduct::class);

        $vendors = Vendor::pluck('resource_id', 'id');
        $products = Product::pluck('name', 'id');

        return view(
            'app.vendor_products.create',
            compact('vendors', 'products')
        );
    }

    /**
     * @param  \App\Http\Requests\VendorProductStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorProductStoreRequest $request) {
        $this->authorize('create', VendorProduct::class);

        $validated = $request->validated();

        $vendorProduct = VendorProduct::create($validated);

        return redirect()
            ->route('vendor-products.edit', $vendorProduct)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, VendorProduct $vendorProduct) {
        $this->authorize('view', $vendorProduct);

        return view('app.vendor_products.show', compact('vendorProduct'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, VendorProduct $vendorProduct) {
        $this->authorize('update', $vendorProduct);

        $vendors = Vendor::pluck('resource_id', 'id');
        $products = Product::pluck('name', 'id');

        return view(
            'app.vendor_products.edit',
            compact('vendorProduct', 'vendors', 'products')
        );
    }

    /**
     * @param  \App\Http\Requests\VendorProductUpdateRequest  $request
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function update(
        VendorProductUpdateRequest $request,
        VendorProduct              $vendorProduct
    ) {
        $this->authorize('update', $vendorProduct);

        $validated = $request->validated();

        $vendorProduct->update($validated);

        return redirect()
            ->route('vendor-products.edit', $vendorProduct)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorProduct  $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, VendorProduct $vendorProduct) {
        $this->authorize('delete', $vendorProduct);

        $vendorProduct->delete();

        return redirect()
            ->route('vendor-products.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
