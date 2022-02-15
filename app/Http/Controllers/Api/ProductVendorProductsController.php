<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorProductResource;
use App\Http\Resources\VendorProductCollection;

class ProductVendorProductsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {
        $this->authorize('view', $product);

        $search = $request->get('search', '');

        $vendorProducts = $product
            ->vendorProducts()
            ->search($search)
            ->latest()
            ->paginate();

        return new VendorProductCollection($vendorProducts);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $this->authorize('create', VendorProduct::class);

        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'price' => ['required', 'numeric'],
            'available' => ['required', 'boolean'],
        ]);

        $vendorProduct = $product->vendorProducts()->create($validated);

        return new VendorProductResource($vendorProduct);
    }
}
