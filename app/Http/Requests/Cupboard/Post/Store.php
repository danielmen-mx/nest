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
        return [
            // 'name'        => ['required', Rule::unique('posts', 'name')->whereNull('deleted_at')],
            'name'        => 'required|unique:posts,name,NULL,deleted_at,deleted_at,NULL|max:255',
            'autor'       => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|max:255',
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
            'name' => 'Name is required and must be unique',
            'autor' => 'Autor is required',
            'description' => 'Description is required'
        ];
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
