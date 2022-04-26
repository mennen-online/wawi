<?php

namespace App\Http\Controllers;

use App\Http\Requests\Offer\StoreOfferRequest;
use App\Models\Offer;
use App\Services\Lexoffice\Endpoints\Contacts;
use App\Services\Lexoffice\Endpoints\Quotation;
use Exception;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request) {
        return view('app.offers.index')
            ->with('offers', $request->user()->offers()->get());
    }

    public function show(Request $request, Offer $offer) {
        return view('app.offers.show')
            ->with('offer', $offer);
    }

    public function create(Request $request) {
        return view('app.offers.create')
            ->with('contacts', (new Contacts())->onlyCustomer()->index()->sortBy('company.name')->sortBy('person.firstName'));
    }

    public function store(StoreOfferRequest $request) {
        $request->user()->offers()->create($request->validated());

        return to_route('offers.index');
    }

    public function edit(Request $request) {

    }

    public function update(Request $request) {

    }

    public function destroy(Request $request, int $id) {
        $offer = Offer::findOrFail($id);

        $offer->delete();

        return to_route('offers.index');
    }

    public function sendToLexoffice(Request $request, Offer $offer) {
        $response = app()->make(Quotation::class)->createQuotation($offer);

        $responseObject = $response->object();

        if(property_exists($responseObject, 'id')) {
            $offer->update([
                'resource_id' => $response->object()->id
            ]);
        }

        return to_route('offers.index');
    }

    public function openInLexoffice(Request $request, Offer $offer) {
        return redirect('https://app.lexoffice.de/permalink/quotations/view/' . $offer->resource_id);
    }
}
