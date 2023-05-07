<?php

namespace App\Http\Requests\Cupboard\Reaction;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'user_id'  => 'required|exists:users,uuid',
            'post_id'  => 'required|exists:posts,uuid',
            'reaction' => 'required|boolean',
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
            'post_id' => $this->validationTranslation('post_id'),
            'reaction' => $this->validationTranslation('reaction')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.reactions.validation.' . $key);
    }
}
