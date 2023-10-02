<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\{ AdvancedResourceTrait, GetReviewTrait };
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    use AdvancedResourceTrait, GetReviewTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $review = $this->getReview($this->hasAttribute('review_id'));

        return [
            'id'          => $this->hasAttribute('uuid'),
            'name'        => $this->hasAttribute('name'),
            'autor'       => $this->hasAttribute('autor'),
            'description' => $this->hasAttribute('description'),
            'image'       => $this->hasAttribute('image'),
            'tags'        => $this->hasAttribute('tags'),
            'rating'      => new Review($review),
            'reactions'   => Reaction::collection($this->whenLoaded('reactions')),
            'comments'    => Comment::collection($this->whenLoaded('comments')),
            'created_at'  => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
