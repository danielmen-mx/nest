<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Reaction\Store;
use App\Http\Requests\Cupboard\Reaction\Update;
use App\Http\Resources\Cupboard\Reaction as ReactionResource;
use App\Models\Cupboard\{ Post, Reaction, Review, User };
use Illuminate\Http\Request;

class ReactionController extends ApiController
{
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
            $post = Post::where('uuid', $data['post_id'])->firstOrFail();

            $reaction = Reaction::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'reaction' => $data['reaction']
            ]);

            $post->review->generateReview();
            $reaction->load(['user', 'post']);

            return $this->responseWithData(new ReactionResource($reaction), 'reaction.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'reaction.store');
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
            $reaction = Reaction::where('uuid', $uuid)->firstOrFail();
            $data = $request->validated();

            $reaction->reaction = $data['reaction'];
            $reaction->save();
            $reaction->post->review->generateReview();
            $reaction->refresh();

            return $this->responseWithData(new ReactionResource($reaction), 'reaction.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'reaction.update');
        }
    }
}
