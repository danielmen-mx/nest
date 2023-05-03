<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Comment\Index;
use App\Http\Requests\Cupboard\Comment\Store;
use App\Http\Requests\Cupboard\Comment\Update;
use App\Http\Resources\Cupboard\Comment as ResourceComment;
use App\Http\Resources\Cupboard\CommentCollection;
use App\Models\Cupboard\{Comment, Post, User};

class CommentController extends ApiController
{
    public function index(Index $request)
    {
        try {
            $data = $request->validated();
            $post = Post::where('uuid', $data['post_id'])->firstOrFail();
            $comments = Comment::where('post_id', $post->id)->get();

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
            $post = Post::where('uuid', $data['post_id'])->firstOrFail();

            $comment = Comment::create([
              'user_id' => $user->id,
              'post_id' => $post->id,
              'comment' => $data['comment']
            ]);

            $comment->load(['user', 'post']);

            return $this->responseWithData(new ResourceComment($comment), 'comment.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.store');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($postId)
    {
        try {
            $post = Post::where('uuid', $postId)->firstOrFail();

            $comments = Comment::where('post_id', $postId)->get();

            return $this->responseWithData(new ResourceComment($comments), 'comment.show');
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
