<?php

namespace App\Models\Cupboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAssetIdentifierTrait;
use App\Models\Traits\HasUuidTrait;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait, HasAssetIdentifierTrait;

    protected $fillable = [
        'name',
        'autor',
        'user_id',
        'description',
        'image',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function getAssetIdentifier()
    {
        return $this->asset_identifier;
    }
}
