@php $editing = isset($vendor) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="company"
            label="Company"
            value="{{ old('company', ($editing ? $vendor->company : '')) }}"
            maxlength="255"
            placeholder="Company"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.email
            name="email"
            label="Email"
            value="{{ old('email', ($editing ? $vendor->email : '')) }}"
            maxlength="255"
            placeholder="Email"
            required
        ></x-inputs.email>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="phone"
            label="Phone"
            value="{{ old('phone', ($editing ? $vendor->phone : '')) }}"
            maxlength="255"
            placeholder="Phone"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="salutation"
            label="Salutation"
            value="{{ old('salutation', ($editing ? $vendor->salutation : '')) }}"
            maxlength="255"
            placeholder="Salutation"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="first_name"
            label="First Name"
            value="{{ old('first_name', ($editing ? $vendor->first_name : '')) }}"
            maxlength="255"
            placeholder="First Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="last_name"
            label="Last Name"
            value="{{ old('last_name', ($editing ? $vendor->last_name : '')) }}"
            maxlength="255"
            placeholder="Last Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="username"
            label="Username"
            value="{{ old('username', ($editing ? $vendor->username : '')) }}"
            maxlength="255"
            placeholder="Username"
            ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="password"
            label="Passwort"
            value="{{ old('password', ($editing ? $vendor->password : '')) }}"
            type="password"
            ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="csv_url"
            label="CSV Download URL"
            value="{{ old('csv_url', ($editing ? $vendor->csv_url : '')) }}"
            placeholder="https://someurl.de/file.csv"
            ></x-inputs.text>
    </x-inputs.group>
</div>
