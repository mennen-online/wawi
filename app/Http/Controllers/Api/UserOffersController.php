<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferCollection;

class UserOffersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $offers = $user
            ->offers()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfferCollection($offers);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Offer::class);

        $validated = $request->validate([
            'resource_id' => ['nullable', 'max:255', 'string'],
        ]);

        $offer = $user->offers()->create($validated);

        return new OfferResource($offer);
    }
}
