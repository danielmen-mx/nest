<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Comment\Index;
use App\Http\Requests\Cupboard\Comment\Store;
use App\Http\Requests\Cupboard\Comment\Update;
use App\Http\Resources\Cupboard\Comment as ResourceComment;
use App\Http\Resources\Cupboard\CommentCollection;
use App\Models\Cupboard\{Comment, Post, User};
use App\Models\Traits\GetModelTrait;

class CommentController extends ApiController
{
    use GetModelTrait;

    public function index(Index $request)
    {
        try {
            $data = $request->validated();
            $model = $this->getModel($data['model_type'], $data['model_id']);
            $comments = Comment::query()
                          ->where('model_type', $model::class)
                          ->where('model_id', $model->id)
                          ->get();

            return $this->responseWithData(new CommentCollection($comments), 'comment.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.index');
        }
    }

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

            $comment = Comment::create([
              'user_id' => $user->id,
              'model_type' => $model::class,
              'model_id' => $model->id,
              'comment' => $data['comment']
            ]);

            $comment->load(['user']);

            return $this->responseWithData(new ResourceComment($comment), 'comment.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.store');
        }
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function show($commentId)
    {
        try {
            $comment = Comment::where('uuid', $commentId)->firstOrFail();

            return $this->responseWithData(new ResourceComment($comment), 'comment.show');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.show');
        }
    }

    public function update(Update $request, $uuid)
    {
        try {
            $comment = Comment::where('uuid', $uuid)->firstOrFail();
            $data = $request->validated();

            $comment->comment = $data['comment'];
            $comment->save();

            return $this->responseWithData(new ResourceComment($comment), 'comment.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.update');
        }
    }

    public function destroy($commentId)
    {   
        try {
            $comment = Comment::where('uuid', $commentId)->firstOrFail();
            $comment->delete();

            return $this->responseWithMessage('comment.delete');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.delete');
        }
    }
}
