<?php

namespace App\Http\Controllers\Cupboard;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Cupboard\Post\Store;
use App\Http\Requests\Cupboard\Post\Update;
use App\Http\Resources\Cupboard\Post as ResourcesPost;
use App\Http\Resources\Cupboard\PostCollection;
use Illuminate\Http\Request;
use App\Models\Cupboard\Post;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = Post::get();

            return $this->responseWithData(new PostCollection($posts), 'posts.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.index');
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
            $post = Post::create($data);

            return $this->responseWithData(new ResourcesPost($post), 'posts.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.store');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $uuid)
    {
        try {
            $post = Post::where('uuid', $uuid)->firstOrFail();
            $data = $request->validated();

            $post->update($data);

            return $this->responseWithData(new ResourcesPost($post), 'posts.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.store');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
