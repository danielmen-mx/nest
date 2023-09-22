<?php

namespace App\Http\Requests\Cupboard\User;

use App\Models\Cupboard\Post;
use App\Models\Cupboard\User;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class Update extends FormRequest
{
    private $user;

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
        $this->setUser();
        $this->validateField('username', $this->username);
        $this->validateField('email', $this->email);
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

    private function setUser()
    {
        $this->user = User::query()->where('uuid', $this->id)->firstOrFail();
    }

    private function validateField($field, $request)
    {
        if (!$request) return;
        $query = User::all("uuid", "username", "email");
        $query->map(function ($item) use ($request, $field) {
            if ($item->uuid === $this->id && $item->$field === $request) return $this->throwValidationError("duplicated_".$field);
            if ($item->$field === $request) return $this->throwValidationError($field."_in_use");
        });
    }

    private function validatePassword($password)
    {
        if (!$password) return;
        if (!password_verify($password, $this->user->password)) return;
        $this->throwValidationError("duplicated_password");
    }

    private function throwValidationError($key)
    {
        throw new Exception($this->validationTranslation($key));
    }
}
