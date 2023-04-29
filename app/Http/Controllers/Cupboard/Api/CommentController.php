<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use Illuminate\Http\Request;

class CommentController extends ApiController
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
            

            // return $this->responseWithData();
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.store');
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

            $comments = $post->comments();

            return $this->responseWithData($comments, 'comment.show');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'comment.show');
        }
    }
}
