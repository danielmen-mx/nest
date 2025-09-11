<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
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
            'id'         => $this->uuid,
            'user_id'    => $this->user->uuid,
            'user'       => new User($this->user),
            'product_id' => $this->product->uuid,
            'product'    => new Product($this->product),
            'status'   => $this->hasAttribute('status'),
            'quantity'   => $this->hasAttribute('quantity'),
            'created_at' => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
