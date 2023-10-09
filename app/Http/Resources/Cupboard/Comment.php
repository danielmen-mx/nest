<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use App\Models\Traits\GetModelTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
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
        $model = $this->getModel($this->model_type, $this->model_id);
        // $resource = $model['model_type'] === Post::class ? new Post($model) : new Product($model);
        $resource = new Post($model);

        return [
            'id'         => $this->hasAttribute('uuid'),
            // 'user_id'    => new User($this->whenLoaded('user')),
            'user_id'    => $this->hasAttribute('user_id'),
            'user'       => new User($this->user),
            'model_type' => $this->hasAttribute('model_type'),
            'model_id'   => $this->hasAttribute('model_id'),
            'model'      => $resource,
            'comment'    => $this->hasAttribute('comment'),
            'created_at' => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
