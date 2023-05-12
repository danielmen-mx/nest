<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Reaction extends JsonResource
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
            'id'         => $this->hasAttribute('uuid'),
            // 'user_id'    => new User($this->whenLoaded('user')),
            // 'user_id'    => $this->hasAttribute('user_id'),
            'user'       => new User($this->user),
            // 'post_id'    => $this->hasAttribute('post_id'),
            'post'       => new Post($this->post),
            'reaction'   => $this->hasAttribute('reaction'),
            'created_at' => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
