<?php

namespace App\Http\Requests\Cupboard\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Store extends FormRequest
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

    protected function prepareForValidation()
    {
        $price = number_format($this->price, 2);
        $shippingPrice = number_format($this->shipping_price, 2);

        $this->merge([
            'price' => $price,
            'shipping_price' => $shippingPrice,
            'image' => $this->assets
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'           => 'required|max:255',
            'price'          => 'required|min:1',
            'shipping_price' => 'required|min:1',
            'stock'       => 'required|min:1',
            'description'    => 'required|max:255',
            'image'          => 'nullable|image',
            'user_id'        => 'nullable'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name' => $this->validationTranslation('name'),
            'price' => $this->validationTranslation('price'),
            'shipping_price' => $this->validationTranslation('shipping_price'),
            'stock' => $this->validationTranslation('stock'),
            'description' => $this->validationTranslation('description'),
            'assets' => $this->validationTranslation('assets'),
            'user_id' => $this->validationTranslation('user_id')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.products.validation.' . $key);
    }
}
