<?php

namespace App\Http\Requests\Cupboard\Product;

use App\Models\Cupboard\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{
    use PostRequestTrait;

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
            'shipping_price' => $shippingPrice
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
            'price'          => 'required|floatval|min:1',
            'shipping_price' => 'required|floatval|min:1',
            'quantity'       => 'required|integer|min:1',
            'description'    => 'required|max:255',
            'assets'         => 'nullable|image',
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
            'quantity' => $this->validationTranslation('quantity'),
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
