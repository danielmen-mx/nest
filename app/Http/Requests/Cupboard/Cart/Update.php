<?php

namespace App\Http\Requests\Cupboard\Cart;

use App\Models\Cupboard\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Update extends FormRequest
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
            'status' => $this->validationTranslation('status'),
            'quantity' => $this->validationTranslation('quantity')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.cart.validation.' . $key);
    }
}
