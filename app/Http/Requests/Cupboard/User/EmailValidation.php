<?php

namespace App\Http\Requests\Cupboard\User;

use App\Models\Cupboard\User;
use Illuminate\Foundation\Http\FormRequest;

class EmailValidation extends FormRequest
{
    use ValidateFieldTrait;

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
            'email' => 'required|email:rfc,dns',
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
            'email' => $this->validationTranslation('email_validation'),
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.users.validation.' . $key);
    }
}
