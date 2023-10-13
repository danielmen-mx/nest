<?php

namespace App\Models\Cupboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasAssetIdentifierTrait;
use App\Models\Traits\HasUuidTrait;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait, HasAssetIdentifierTrait;

    protected $fillable = [
        'name',
        'price',
        'shipping_price',
        'quantity',
        'description',
        'assets',
        'review_id',
        'user_id'
    ];

    protected $casts = [
        
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
