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
            'first_name'  => $this->hasAttribute('first_name'),
            'last_name'   => $this->hasAttribute('last_name'),
            'language'    => $this->hasAttribute('language'),
            'fullname'    => $this->getName(),
            'is_admin'    => $this->hasAttribute('is_admin'),
            'created_at'  => $this->when($this->created_at, $this->created_at ? $this->created_at->toDateTimeString() : null),
        ];
    }

    private function getName()
    {
        $firstName = $this->hasAttribute('first_name');
        $lastName = $this->hasAttribute('last_name');

        if (!$firstName && !$lastName) {
          return null;
        }

        return $firstName.' '.$lastName;
    }
}