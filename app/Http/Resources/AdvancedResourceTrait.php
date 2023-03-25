<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\MissingValue;

trait AdvancedResourceTrait
{
    public function hasAttribute($key, $value = null)
    {
        $key = strtolower(trim($key));
        $loadedAttributes =  array_keys($this->getRawOriginal());
        if (in_array($key, $loadedAttributes)) {
            $value = $value ? $value : $this->{$key};

            return $value;
        }

        return new MissingValue();
    }
}
