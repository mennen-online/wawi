<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductProcessImportRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Imports\ProductsImport;
use App\Imports\Wave\ProductImport;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

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

    public function import(Request $request) {
        return view('app.products.import')
            ->with('vendors', Vendor::all())
            ->with('editing', null);
    }

    public function processImport(ProductProcessImportRequest $processImportRequest) {
        $vendor = Vendor::find($processImportRequest->input('vendor_id'));

        if (empty($vendor->csv_url)) {
            return view('app.products.import')
                ->with('vendors', Vendor::all())
                ->with('editing', null)
                ->withError(__('crud.products.errors.no_csv_configuration'));
        }

        $request = Http::withBasicAuth($vendor->username, $vendor->password)->get($vendor->csv_url);

        $path = 'csv/'.$vendor->id.'/file.csv';

        Storage::put($path, $request->body());

        $string = str_replace([
            "\n",
        ], [
            "\\"
        ], $request->body());

        $array = explode("\\", $string);

        $csvLines = collect($array)->map(function ($line) {
            return explode("\t", $line);
        });

        $keys = $csvLines->first();

        $items = $csvLines->skip(1)->all();

        collect($items)->map(function($item) use($keys) {
            if(count($item) === count($keys)) {
                return collect($keys)->combine($item);
            }
        })->each(function($product) {
            if($product) {
                (new ProductImport())->model($product->toArray());
            }
        });

        return to_route('products.import');
    }
}
