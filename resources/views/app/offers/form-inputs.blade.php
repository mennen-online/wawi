@php $editing = isset($offer) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="{{__('crud.offers.inputs.name')}}"
            ></x-inputs.text>
    </x-inputs.group>
    <x-inputs.group class="w-full">
        <select name="contact_id" class="block appearance-none w-full py-1 px-2 text-base leading-normal text-gray-800 border border-gray-200 rounded" required>
            @foreach($contacts as $contact)
                <option value="{{$contact->id}}">{{$contact?->company?->name ?? $contact->person->firstName . ' ' . $contact->person->lastName}}</option>
                @endforeach
        </select>
        <button type="submit"
                class="button button-primary float-right"
                >
            <i class="mr-1 icon ion-md-save"></i>
            @lang('crud.common.create')
        </button>
    </x-inputs.group>
</div>