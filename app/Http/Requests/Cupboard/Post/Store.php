<?php

namespace App\Http\Requests\Cupboard\Post;

use App\Models\Cupboard\Post;
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
        $post = Post::where('name', $this->name)->onlyTrashed()->first();
        if ($post) {
            $post->name = $post->name . '-deleted-' . Str::random(5);
            $post->save();
        }
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
            'name'        => 'required|unique:posts,name,NULL,deleted_at,deleted_at,NULL|max:255',
            'autor'       => 'required|max:255',
            'description' => 'required|min:1',
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
