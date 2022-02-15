<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Http\Resources\VendorCollection;
use App\Http\Requests\VendorStoreRequest;
use App\Http\Requests\VendorUpdateRequest;

class VendorController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Vendor::class);

        $search = $request->get('search', '');

        $vendors = Vendor::search($search)
            ->latest()
            ->paginate();

        return new VendorCollection($vendors);
    }

    /**
     * @param \App\Http\Requests\VendorStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorStoreRequest $request)
    {
        $this->authorize('create', Vendor::class);

        $validated = $request->validated();

        $vendor = Vendor::create($validated);

        return new VendorResource($vendor);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Vendor $vendor)
    {
        $this->authorize('view', $vendor);

        return new VendorResource($vendor);
    }

    /**
     * @param \App\Http\Requests\VendorUpdateRequest $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(VendorUpdateRequest $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $validated = $request->validated();

        $vendor->update($validated);

        return new VendorResource($vendor);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        return response()->noContent();
    }
}
