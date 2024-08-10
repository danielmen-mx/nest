<?php

namespace App\Http\Requests\Cupboard\Cart;

use App\Models\Cupboard\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Index extends FormRequest
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
        $this->merge([
            'per_page' => $this->per_page??6
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
            'per_page'  => 'required',
            'page'      => 'required',
            'user_id'   => 'required',
            'status'    => 'required'
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
            'per_page' => $this->validationTranslation('per_page'),
            'status' => $this->validationTranslation('status')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.cart.validation.index.' . $key);
    }
}
