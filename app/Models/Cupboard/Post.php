<?php

namespace App\Models\Cupboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAssetIdentifierTrait;
use App\Models\Traits\HasUuidTrait;

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
        return $this->morphMany(Comment::class, 'model');

    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'model');
    }

    public function review()
    {
        return $this->morphOne(Review::class, 'model');
    }

    public function getAssetIdentifier()
    {
        return $this->asset_identifier;
    }
}
