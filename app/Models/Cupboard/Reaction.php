<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    // protected $with = ['post'];

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
        return $this->belongsTo(Post::class, 'model_id', 'id');
    }
}
