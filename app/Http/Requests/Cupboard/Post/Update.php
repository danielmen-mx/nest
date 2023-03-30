<?php

namespace App\Http\Requests\Cupboard\Post;

use Illuminate\Foundation\Http\FormRequest;
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

    protected function prepareForValidation()
    {
        $this->merge([
            'name'        => $this->name,
            'autor'       => $this->autor,
            'description' => $this->description,
            'image'       => $this->image,
            'tags'        => $this->tags,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        # TODO: add validation for image file type
        return [
            'name'        => 'required|exists:posts|max:255',
            'autor'       => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|image',
            'tags'        => 'nullable|max:255',
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
            'autor' => $this->validationTranslation('autor'),
            'description' => $this->validationTranslation('description'),
            'images' => $this->validationTranslation('images')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.posts.validation.' . $key);
    }
}
