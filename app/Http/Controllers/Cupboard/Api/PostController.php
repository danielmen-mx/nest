<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Post\Index;
use App\Http\Requests\Cupboard\Post\Store;
use App\Http\Requests\Cupboard\Post\Update;
use App\Http\Resources\Cupboard\Post as ResourcesPost;
use App\Http\Resources\Cupboard\PostCollection;
use App\Models\Cupboard\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        try {
            $posts = Post::query()
                ->with(['user', 'comments', 'reactions'])
                ->orderBy('created_at', 'asc')
                ->paginate($request->per_page ?? 6);

            // dd($posts);

            $resource = [
                'per_page' => $request->per_page ?? 6,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'first_item' => $posts->firstItem(),
                'last_item' => $posts->lastItem(),
                'total' => $posts->total()
            ];

            return $this->responseWithPaginationResource(new PostCollection($posts), $resource, 'posts.index');
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

            if ($request->hasFile('image')) {
                $fileName = $this->processImage($request);

                $fileIdentifier = $post->nomenclatureImage($fileName);
                $post->image = $fileIdentifier;
                $post->save();
            }

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
    public function show($uuid)
    {
        try {
            $post = Post::where('uuid', $uuid)
                ->with(['user', 'comments', 'reactions'])
                ->firstOrFail();

            return $this->responseWithData(new ResourcesPost($post), 'posts.show');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.show');
        }
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

            if ($request->hasFile('image')) {
                $fileName = $this->processImage($request);

                $fileIdentifier = $post->nomenclatureImage($fileName);
                $post->image = $fileIdentifier;
                $post->save();
            }

            return $this->responseWithData(new ResourcesPost($post), 'posts.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        try {
            $post = Post::where('uuid', $uuid)->firstOrFail();
            $post->delete();

            return $this->responseWithMessage('posts.delete');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'posts.delete');
        }
    }

    private function searchOldImage()
    {
        // add logic to remove last image added with the current slug
    }

    private function processImage($request)
    {
        $file = $request->file('image');
        $fileName = $this->getFileName($file->getClientOriginalName(), $file->getClientOriginalExtension(), Str::slug($request['name']));
        $file->move(public_path('images'), $fileName);

        return $fileName;
    }

    private function getFileName($originalName, $extension, $slug)
    {
        $newName = $originalName;
        if (str_contains($originalName, '.')) {
            $splinters = explode('.', $originalName);
            array_pop($splinters);

            $newName = implode($splinters);
        }

        return $newName .'.'. $extension;
    }
}
