<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $fillable = [
      'name',
      'autor',
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
}
