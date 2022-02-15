<?php
namespace App\Http\Controllers\Api;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\VendorProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferCollection;

class VendorProductOffersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, VendorProduct $vendorProduct)
    {
        $this->authorize('view', $vendorProduct);

        $search = $request->get('search', '');

        $offers = $vendorProduct
            ->offers()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfferCollection($offers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        VendorProduct $vendorProduct,
        Offer $offer
    ) {
        $this->authorize('update', $vendorProduct);

        $vendorProduct->offers()->syncWithoutDetaching([$offer->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\VendorProduct $vendorProduct
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        VendorProduct $vendorProduct,
        Offer $offer
    ) {
        $this->authorize('update', $vendorProduct);

        $vendorProduct->offers()->detach($offer);

        return response()->noContent();
    }
}
