<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use App\Http\Resources\Cupboard\Post as CupboardPost;
use App\Models\Cupboard\Post;
use App\Models\Traits\GetModelTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Reaction extends JsonResource
{
    use AdvancedResourceTrait, GetModelTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $model = $this->hasAttribute('model_type') == Post::class ? $this->post : null;

        return [
            'id'         => $this->hasAttribute('uuid'),
            'user'       => new User($this->user),
            'model'      => $this->resource($this->hasAttribute('model_type'), $model),
            'rating'     => new Review($this->model->review),
            'reaction'   => $this->hasAttribute('reaction'),
            'created_at' => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }

    private function resource($modelType, $model)
    {
        if ($modelType == Post::class) return new CupboardPost($model);
        return null;
    }
}
