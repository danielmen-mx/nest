<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Product\Index;
use App\Http\Resources\Cupboard\ProductCollection;
use App\Models\Cupboard\{ Product };
use App\Models\Traits\AssetsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends ApiController
{
    use AssetsTrait;

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

            $resource = [
                'per_page' => $request->per_page ?? 6,
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'first_item' => $products->firstItem(),
                'last_item' => $products->lastItem(),
                'total' => $products->total()
            ];

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
    public function store(Request $request)
    {
        try {
            

            return $this->responseWithData('products.store');
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
            

            return $this->responseWithData('products.show');
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
    public function update(Request $request, $uuid)
    {
        try {
            return $this->responseWithData('products.update');
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
            return $this->responseWithMessage('products.delete');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'products.delete');
        }
    }
}
