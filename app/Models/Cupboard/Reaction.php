<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'reaction'
    ];

    protected $casts = [
        'reaction' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->where('model_type', Post::class);
    }
    
}
