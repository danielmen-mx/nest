<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Review extends JsonResource
{
    use AdvancedResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->hasAttribute('uuid'),
            // 'post'        => new Post($this->post),
            // 'post'        => $this->post->uuid,
            'model_type'  => $this->hasAttribute('model_type'),
            'model_id'    => $this->hasAttribute('model_id'),
            'review'      => $this->hasAttribute('review'),
            'created_at'  => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
