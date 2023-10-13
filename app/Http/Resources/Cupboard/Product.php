<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\{ AdvancedResourceTrait, GetReviewTrait };
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
        return [
            'id'             => $this->hasAttribute('uuid'),
            'name'           => $this->hasAttribute('name'),
            'price'          => $this->hasAttribute('price'),
            'shipping_price' => $this->hasAttribute('shipping_price'),
            'quantity'       => $this->hasAttribute('quantity'),
            'description'    => $this->hasAttribute('description'),
            'assets'         => $this->hasAttribute('assets'),
            'rating'         => new Review($this->whenLoaded('review')),
            'reactions'      => Reaction::collection($this->whenLoaded('reactions')),
            'comments'       => Comment::collection($this->whenLoaded('comments')),
            'created_at'     => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
