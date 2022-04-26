<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.offers.show_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{ route('offers.index') }}" class="mr-4"
                    ><i class="mr-1 icon ion-md-arrow-back"></i
                        ></a>
                </x-slot>

                <div class="mt-4 px-4">
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.offers.inputs.name')
                        </h5>
                        <span>{{ $offer->name ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.offers.inputs.contact_id')
                        </h5>
                        <span>{{ $offer->contact?->company?->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-4 px-4">
                    <div class="mb-4">
                        <table class="w-full max-w-full mb-4 bg-transparent">
                            <thead class="text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.vendor_products.inputs.vendor_id')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.vendor_products.inputs.product_id')
                                </th>
                                <th class="px-4 py-3 text-right">
                                    @lang('crud.vendor_products.inputs.price')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.vendor_products.inputs.available')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('Quantity')
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600">
                            @forelse($offer->vendorProducts as $offerProduct)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-left">
                                        {!!
                                        optional($offerProduct->vendor)->company
                                        ?? '-'  !!}
                                    </td>
                                    <td class="px-4 py-3 text-left">
                                        {{ optional($offerProduct->product)->name
                                        ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        {{ number_format($offerProduct->price * $offerProduct->getOriginal()['pivot_quantity'], 2) ?? '-' }} &euro;<br/>
                                        ({{number_format($offerProduct->price, 2)}} &euro;)
                                    </td>
                                    <td class="px-4 py-3 text-left">
                                        {{ $offerProduct->available ? 'Ja' : 'Nein' }}
                                    </td>
                                    <td class="px-4 py-3 text-left">
                                        {{ $offerProduct->getOriginal()['pivot_quantity'] }}
                                    </td>
                                    <td
                                            class="px-4 py-3 text-center"
                                            style="width: 134px;"
                                    >
                                        <div
                                                role="group"
                                                aria-label="Row Actions"
                                                class="
                                            relative
                                            inline-flex
                                            align-middle
                                        "
                                        >
                                            @can('update', $offerProduct)
                                                <a
                                                        href="{{ route('vendor-products.edit', $offerProduct) }}"
                                                        class="mr-1"
                                                >
                                                    <button
                                                            type="button"
                                                            class="button"
                                                    >
                                                        <i
                                                                class="icon ion-md-create"
                                                        ></i>
                                                    </button>
                                                </a>
                                            @endcan @can('view', $offerProduct)
                                                <a
                                                        href="{{ route('vendor-products.show', $offerProduct) }}"
                                                        class="mr-1"
                                                >
                                                    <button
                                                            type="button"
                                                            class="button"
                                                    >
                                                        <i class="icon ion-md-eye"></i>
                                                    </button>
                                                </a>
                                            @endcan @can('delete', $offerProduct)
                                                <form
                                                        action="{{ route('vendor-products.destroy', $offerProduct) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                                >
                                                    @csrf @method('DELETE')
                                                    <button
                                                            type="submit"
                                                            class="button"
                                                    >
                                                        <i
                                                                class="
                                                        icon
                                                        ion-md-trash
                                                        text-red-600
                                                    "
                                                        ></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="px-4 py-3 text-left">
                                        {{number_format($offer->vendorProducts->map(function($vendorProduct) {
                                            return $vendorProduct->getOriginal()['pivot_quantity'] * $vendorProduct->price;
                                        })->sum(), 2)}} &euro; <br/> zzgl. UST.
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-10">
                    <a href="{{ route('offers.index') }}" class="button">
                        <i class="mr-1 icon ion-md-return-left"></i>
                        @lang('crud.common.back')
                    </a>
                    <a href="{{route('offer.send-to-lexoffice', ['offer' => $offer->id])}}" class="button">
                        In Lexoffice erstellen
                    </a>
                    @if($offer->resource_id)
                        <a href="{{route('offer.open-in-lexoffice', ['offer' => $offer->id])}}" target="_blank" class="button">
                            In Lexoffice öffnen
                        </a>
                    @endif
                </div>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>
