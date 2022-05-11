<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorStoreRequest;
use App\Http\Requests\VendorUpdateRequest;
use App\Models\Vendor;
use App\Services\Lexoffice\Endpoints\Contacts;
use Illuminate\Http\Request;

/**
 *
 */
class VendorController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
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
    public function destroy(Request $request, Vendor $vendor) {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->withSuccess(__('crud.common.removed'));
    }

    /**
     * @param  Request  $request
     * @param  Contacts  $contacts
     * @return mixed
     */
    public function import(Request $request, Contacts $contacts) {
        $page = 0;
        do {
            $result = $contacts->onlyVendor()->setSize(500)->setPage($page)->index()->object();
            collect($result->content)->each(function ($vendor) {
                if (property_exists($vendor, 'company')) {
                    $contactPerson = null;
                    if(property_exists($vendor->company, 'contactPersons')) {
                        $contactPerson = collect($vendor->company->contactPersons)->filter(function ($contactPerson) {
                            if ($contactPerson?->primary) {
                                return $contactPerson;
                            }
                        })->first();
                    }

                    Vendor::updateOrCreate(
                        [
                            'resource_id' => $vendor->id,
                        ],
                        [
                            'resource_id' => $vendor->id,
                            'company'     => $vendor->company->name,
                            'email'       => $contactPerson?->emailAddress ?? '',
                            'phone'       => $contactPerson?->phoneNumber ?? '',
                            'salutation'  => $contactPerson?->salutation ?? '',
                            'first_name'  => $contactPerson?->firstName ?? '',
                            'last_name'   => $contactPerson?->lastName ?? '',
                        ]);
                } else {
                    if (property_exists($vendor, 'person')) {
                        Vendor::updateOrCreate(
                            [
                                'resource_id' => $vendor->id,
                            ],
                            [
                                'resource_id' => $vendor->id,
                                'company'     => '',
                                'salutation'  => $vendor->person->salutation,
                                'first_name'  => $vendor->person->firstName,
                                'last_name'   => $vendor->person->lastName,
                                'email'       => '',
                                'phone'       => ''
                            ]);
                    }
                }
            });
            $page++;
        } while ($result->last === false);

        return to_route('vendors.index')
            ->withSuccess(__('crud.common.imported'));
    }
}
