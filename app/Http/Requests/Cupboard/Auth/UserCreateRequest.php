<?php

namespace App\Http\Requests\Cupboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
        $username = (explode('@', $this->email)[0]) . now()->getTimestamp();
        $password = bcrypt($this->password);

        $this->merge([
            'username'          => $username,
            'password'          => $password
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
            'email'        => 'required|unique:users,email|email',
            'username'     => 'required|unique:users,username',
            'password'     => 'required'
        ];
    }
}
