<?php

namespace App\Models\Cupboard;

use App\Models\Traits\{HasUuidTrait, GetModelTrait};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Artisan;

class Review extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait, GetModelTrait;

    protected $maxReview = 5;

    protected $fillable = [
        'review',
        'model_type',
        'model_id'
    ];

    protected $casts = [
        'review' => 'integer'
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function posts()
    {
        return $this->where("model_type", Post::class);
    }

    public function products()
    {
        // return $this->where("model_type", Product::class);
    }

    public function generateReview()
    {
        $modelType = $this->getModelName($this->model_type);
        Artisan::call("cboard:make-review ".$modelType." ".$this->model_id);
    }
}
