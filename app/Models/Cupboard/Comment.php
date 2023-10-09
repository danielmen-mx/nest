<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'comment'
    ];

    protected $casts = [
        //
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return Post::query()
                ->where('id', $this->model_id)
                ->first();
    }
    
}
