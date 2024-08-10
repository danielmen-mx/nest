<?php

namespace App\Http\Requests\Cupboard\Cart;

use App\Models\Cupboard\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'    => 'required|exists:users,uuid',
            'product_id' => 'required|exists:products,uuid',
            'status'     => 'required',
            'quantity'   => 'required|integer'
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
            'user_id' => $this->validationTranslation('user_id'),
            'product_id' => $this->validationTranslation('product_id'),
            'status' => $this->validationTranslation('status'),
            'quantity' => $this->validationTranslation('quantity')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.cart.validation.' . $key);
    }
}
