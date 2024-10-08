<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Product\Index;
use App\Http\Requests\Cupboard\Product\Store;
use App\Http\Requests\Cupboard\Product\Update;
use App\Http\Resources\Cupboard\Product as ResourceProduct;
use App\Http\Resources\Cupboard\ProductCollection;
use App\Models\Cupboard\{ Product, Review, User};
use App\Models\Traits\AssetsTrait;
use App\Models\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends ApiController
{
    use AssetsTrait, PaginationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        try {    
            $products = Product::query()
                ->with(['user', 'comments', 'reactions', 'review'])
                ->orderBy('created_at', 'asc')
                ->paginate($request->per_page ?? 6);

            $resource = $this->loadRequestResource($products, $request->per_page);

            return $this->responseWithPaginationResource(new ProductCollection($products), $resource, 'products.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.index');
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
            $userId = User::where('uuid', $data['user_id'])->firstOrFail()->id;
            $data['user_id'] = $userId;

            $product = Product::create($data);
            $review = Review::create([
                'model_type' => Product::class,
                'model_id'   => $product->id
            ]);

            if ($request->hasFile('image')) {
                $this->processAsset($product, $request);
                $product->image = $this->getAssetStorePath($product, $request);
                $product->save();
            }

            $product->review_id = $review->id;
            $product->save();

            return $this->responseWithData(new ResourceProduct($product), 'products.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.store');
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
            $product = Product::where('uuid', $uuid)
                ->with(['user', 'reactions', 'review'])
                ->firstOrFail();

            return $this->responseWithData(new ResourceProduct($product), 'products.show');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.show');
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
            $product = Product::where('uuid', $uuid)->firstOrFail();
            $data = $request->validated();

            $product->update($data);

            if ($request->hasFile('image')) {
                $this->processAsset($product, $request);
                $product->image = $this->getAssetStorePath($product, $request);
                $product->save();
            }

            return $this->responseWithData(new ResourceProduct($product), 'products.update');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.update');
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
            $product = Product::where('uuid', $uuid)->firstOrFail();
            $product->delete();

            return $this->responseWithMessage('products.delete');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.delete');
        }
    }
}
