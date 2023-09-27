<?php

namespace App\Http\Requests\Cupboard\User;

use App\Models\Cupboard\User;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->setUser($this->id);
        if (!$this->username) {
          $this->merge(["username" => $this->user->username]);
        } else {
          $this->validateField('username', $this->username);
        }

        if (!$this->email) {
          $this->merge(["email" => $this->user->email]);
        } else {
          $this->validateField('email', $this->email);
        }

        $this->validateLanguage($this->language);
        $this->validatePassword($this->password);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username'   => 'nullable|max:255',
            'email'      => 'nullable',
            'is_admin'   => 'nullable|boolean',
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:100',
            'language'   => 'required',
            'password'   => 'nullable'
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
            'username' => $this->validationTranslation('username'),
            'email' => $this->validationTranslation('email'),
            'is_admin' => $this->validationTranslation('is_admin'),
            'first_name' => $this->validationTranslation('first_name'),
            'last_name' => $this->validationTranslation('last_name'),
            'language' => $this->validationTranslation('language'),
            'password' => $this->validationTranslation('password'),
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.users.validation.' . $key);
    }
}
