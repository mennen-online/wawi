<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorProductResource;
use App\Http\Resources\VendorProductCollection;

class VendorVendorProductsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Vendor $vendor)
    {
        $this->authorize('view', $vendor);

        $search = $request->get('search', '');

        $vendorProducts = $vendor
            ->vendorProducts()
            ->search($search)
            ->latest()
            ->paginate();

        return new VendorProductCollection($vendorProducts);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vendor $vendor)
    {
        $this->authorize('create', VendorProduct::class);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'available' => ['required', 'boolean'],
        ]);

        $vendorProduct = $vendor->vendorProducts()->create($validated);

        return new VendorProductResource($vendorProduct);
    }
}
