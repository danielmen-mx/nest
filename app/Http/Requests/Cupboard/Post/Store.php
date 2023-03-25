<?php

namespace App\Http\Requests\Cupboard\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
        return [
            'name'        => 'required|unique:posts|max:255',
            'autor'       => 'required|max:255',
            'description' => 'required',
            'image'       => 'nullable|max:255',
            'tags'        => 'nullable|max:255',
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
