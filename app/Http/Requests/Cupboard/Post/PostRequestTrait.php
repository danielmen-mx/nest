<?php

namespace App\Http\Requests\Cupboard\Post;

use App\Models\Cupboard\Post;
use Illuminate\Support\Str;

trait PostRequestTrait
{
    public function makePascalCase($string): String
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

    public function checkNameAvailable()
    {   
        $post = Post::where('name', $this->name)->onlyTrashed()->first();

        if ($post) {
            $post->name = $post->name . '-deleted-' . Str::random(5);
            $post->save();
        }
    }
}