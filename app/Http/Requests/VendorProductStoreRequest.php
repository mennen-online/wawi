<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorProductStoreRequest extends FormRequest
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
            'vendor_id' => ['required', 'exists:vendors,id'],
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'available' => ['required', 'boolean'],
        ];
    }
}
