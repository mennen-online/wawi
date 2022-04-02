<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'max:255', 'string'],
            'salutation' => ['required', 'max:255', 'string'],
            'first_name' => ['required', 'max:255', 'string'],
            'last_name' => ['required', 'max:255', 'string'],
            'username' => ['nullable', 'max:255', 'string'],
            'password' => ['nullable', 'max:255', 'string'],
            'csv_url' => ['nullable', 'max:255', 'string']
        ];
    }
}
