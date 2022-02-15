<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\VendorProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorProductResource;
use App\Http\Resources\VendorProductCollection;
use App\Http\Requests\VendorProductStoreRequest;
use App\Http\Requests\VendorProductUpdateRequest;

class VendorProductController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', VendorProduct::class);

        $search = $request->get('search', '');

        $vendorProducts = VendorProduct::search($search)
            ->latest()
            ->paginate();

        return new VendorProductCollection($vendorProducts);
    }

    /**
     * @param \App\Http\Requests\VendorProductStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorProductStoreRequest $request)
    {
        $this->authorize('create', VendorProduct::class);

        $validated = $request->validated();

        $vendorProduct = VendorProduct::create($validated);

        return new VendorProductResource($vendorProduct);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, VendorProduct $vendorProduct)
    {
        $this->authorize('view', $vendorProduct);

        return new VendorProductResource($vendorProduct);
    }

    /**
     * @param \App\Http\Requests\VendorProductUpdateRequest $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function update(
        VendorProductUpdateRequest $request,
        VendorProduct $vendorProduct
    ) {
        $this->authorize('update', $vendorProduct);

        $validated = $request->validated();

        $vendorProduct->update($validated);

        return new VendorProductResource($vendorProduct);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, VendorProduct $vendorProduct)
    {
        $this->authorize('delete', $vendorProduct);

        $vendorProduct->delete();

        return response()->noContent();
    }
}
