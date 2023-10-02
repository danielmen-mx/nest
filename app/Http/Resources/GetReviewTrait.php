<?php

namespace App\Http\Resources;

use App\Models\Cupboard\Review;

trait GetReviewTrait
{
    public function getReview($id)
    {
        return Review::where('id', $id)->first();
    }
}
