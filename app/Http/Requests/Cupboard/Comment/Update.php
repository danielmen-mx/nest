<?php

namespace App\Http\Requests\Cupboard\Comment;

use App\Models\Cupboard\Post;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,uuid',
            'post_id' => 'required|exists:posts,uuid',
            'comment' => 'required|min:1|max:255',
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
            'comment' => $this->validationTranslation('comment')
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.comments.validation.' . $key);
    }
}
