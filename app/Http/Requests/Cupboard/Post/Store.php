<?php

namespace App\Http\Requests\Cupboard\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    protected function prepareForValidation()
    {
        $this->merge([
            'name'        => $this->name,
            'autor'       => $this->convertName($this->autor),
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
        # TODO: exclude soft delete records from validation unique name
        # TODO: add validation for image file type
        return [
            // 'name'        => ['required', Rule::unique('posts', 'name')->whereNull('deleted_at')],
            'name'        => 'required|unique:posts,name,NULL,deleted_at,deleted_at,NULL|max:255',
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

    private function convertName($string)
    {
        $slugged = ucfirst(Str::slug($string));

        if (str_contains($slugged, '-')) {
            $slugsWUnderscore = explode('-', $slugged);
            $newString = collect($slugsWUnderscore)->map(function ($slug) {
                return ucfirst($slug);
            });

            $slugged = implode(' ', $newString->all());
        }

        return $slugged;
    }
}
