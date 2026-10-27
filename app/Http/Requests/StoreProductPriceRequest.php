<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency_id' => [
                'required',
                'exists:currencies,id',
                Rule::unique('product_prices', 'currency_id')
                    ->where('product_id', $this->route('product')->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'currency_id.unique' => 'Este producto ya tiene un precio en esa divisa.',
        ];
    }
}