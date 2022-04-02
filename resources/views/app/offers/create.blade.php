<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.offers.create_title')
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{route('offers.index')}}" class="mr-4">
                        <i class="mr-1 icon ion-md-arrow-back"></i>
                    </a>
                </x-slot>

                <x-form method="POST"
                        action="{{route('offers.store')}}"
                        class="mt-4">
                    @include('app.offers.form-inputs')
                </x-form>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>