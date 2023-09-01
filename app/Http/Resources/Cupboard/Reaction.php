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
            'user'       => new User($this->user),
            'post'       => new Post($this->post),
            'reaction'   => $this->hasAttribute('reaction'),
            'created_at' => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
