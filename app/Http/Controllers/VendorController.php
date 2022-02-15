<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
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
            ->paginate(5)
            ->withQueryString();

        return view('app.vendors.index', compact('vendors', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Vendor::class);

        return view('app.vendors.create');
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

        return redirect()
            ->route('vendors.edit', $vendor)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Vendor $vendor)
    {
        $this->authorize('view', $vendor);

        return view('app.vendors.show', compact('vendor'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vendor $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        return view('app.vendors.edit', compact('vendor'));
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

        return redirect()
            ->route('vendors.edit', $vendor)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('vendors.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
