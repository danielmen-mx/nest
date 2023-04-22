<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $fillable = [
        'autor',
        'content'
    ];

    protected $casts = [
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    
}
