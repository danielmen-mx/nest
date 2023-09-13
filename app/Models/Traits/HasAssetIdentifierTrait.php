<?php

namespace App\Models\Traits;

trait HasAssetIdentifierTrait
{
    public static function booted(): void
    {   
        static::created(function ($model) {
            $model->asset_identifier = (string) \Illuminate\Support\Str::random(6);
            $model->save();
        });
    }

    public function scopeFindByIdentifier($query, $identifier)
    {
        return $query->where('asset_identifier', $identifier);
    }

    public function getRouteKey()
    {
        return 'asset_identifier';
    }
}