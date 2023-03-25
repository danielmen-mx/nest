<?php

namespace App\Models\Traits;

trait HasUuidTrait
{
    public static function bootHasUuidTrait(): void
    {
        static::creating(fn ($model) => $model->uuid = (string) \Illuminate\Support\Str::uuid());
    }

    public function scopeFindByUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    public function scopeFindByUuidFirstOrFail($query, $uuid, $fields = ['*'])
    {
        return $query->findByUuid($uuid)->firstOrFail($fields);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
