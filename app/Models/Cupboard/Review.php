<?php

namespace App\Models\Cupboard;

use App\Models\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $maxReview = 5;

    protected $fillable = [
        'post_id',
        'review'
    ];

    protected $casts = [
        'review' => 'integer'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function generateReview()
    {
        $postId = $this->post_id;
        Artisan::call("cboard:make-review " . $postId);
    }
}
