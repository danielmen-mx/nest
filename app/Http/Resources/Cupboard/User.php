<?php

namespace App\Http\Resources\Cupboard;

use App\Http\Resources\AdvancedResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'username'    => $this->hasAttribute('username'),
            'email'       => $this->hasAttribute('email'),
            'created_at'  => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }
}
