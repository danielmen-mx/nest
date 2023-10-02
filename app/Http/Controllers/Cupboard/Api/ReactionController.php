<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Reaction\Store;
use App\Http\Requests\Cupboard\Reaction\Update;
use App\Http\Resources\Cupboard\Reaction as ReactionResource;
use App\Models\Cupboard\{ Post, Reaction, Review, User };
use App\Models\Traits\GetModelTrait;
use Illuminate\Http\Request;

class ReactionController extends ApiController
{
    use GetModelTrait;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        try {
            $data = $request->validated();
            
            $user = User::where('uuid', $data['user_id'])->firstOrFail();
            $model = $this->getModel($data['model_type'], $data['model_id']);

            $reaction = Reaction::create([
                'user_id' => $user->id,
                'model_type' => $data['model_type'],
                'model_id' => $model->id,
                'reaction' => $data['reaction']
            ]);

            $review = $model->review();
            $review->generateReview();
            $reaction->load(['user']);

            return $this->responseWithData(new ReactionResource($reaction), 'reactions.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'reactions.store');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cupboard\Reaction  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $uuid)
    {
        try {
            $data = $request->validated();
            $reaction = Reaction::where('uuid', $uuid)->firstOrFail();
            $model = $this->getModel($data['model_type'], $data['model_id']);

            $reaction->reaction = $data['reaction'];
            $reaction->save();

            $review = $model->review();
            $review->generateReview();
            $reaction->refresh();

            return $this->responseWithData(new ReactionResource($reaction), 'reactions.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'reactions.update');
        }
    }
}
