<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.products.import_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{route('products.index')}}" class="mr-4"
                       ><i class="mr-1 icon ion-md-arrow-back"></i></a>
                </x-slot>

                <x-form
                    method="POST"
                    action="{{ route('products.process') }}"
                    has-files
                    class="mt-4"
                    >
                    <x-inputs.select name="vendor_id" label="Vendor" required>
                        @php $selected = old('vendor_id', ($editing ? $vendorProduct->vendor_id : '')) @endphp
                        <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{$vendor->id}}" {{($selected == $vendor->id || (int)filter_input(INPUT_GET, 'vendor_id') === $vendor->id) ? 'selected' : ''}} >{{$vendor->company}}</option>
                        @endforeach
                    </x-inputs.select>

                    <x-inputs.group class="w-full">
                        <button
                                type="submit"
                                class="button button-primary">
                            CSV Import Starten
                        </button>
                    </x-inputs.group>

                </x-form>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>