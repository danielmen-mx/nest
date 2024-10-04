<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Exceptions\Cart\QuantityException;
use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Cart\{ Index, Store, Update};
use App\Http\Resources\Cupboard\Cart as ResourceCart;
use App\Http\Resources\Cupboard\CartCollection;
use App\Models\Cupboard\{ Cart, Product, User };
use App\Models\Traits\PaginationTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends ApiController
{
    use PaginationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        try {
            $userId = User::where('uuid', $request->user_id)->firstOrFail()->id;
            $carts = Cart::query()
                        ->where('user_id', $userId)
                        ->where('status', $request->status)
                        ->orderBy('created_at', 'asc')
                        ->paginate($request->per_page??8);

            $resource = $this->loadRequestResource($carts, $request->per_page);

            return $this->responseWithPaginationResource(new CartCollection($carts), $resource, 'cart.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'cart.index');
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
            $product = Product::where('uuid', $data['product_id'])->firstOrFail();
            $cart = Cart::where("user_id", $user->id)->where("product_id", $product->id)->first();

            if ($cart) $this->validateStockAvailable($product->stock, $cart->quantity, $data['quantity']);

            $attributes = [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'status' => 'standby',
                'quantity' => $data['quantity'],
            ];

            $cart
                ? $cart->update(['quantity' => $cart->quantity + $data['quantity']])
                : $cart = Cart::create($attributes);

            $cart->load(['user', 'product']);

            return $this->responseWithData(new ResourceCart($cart), 'cart.store');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'cart.store');
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
            $cart = Cart::query()
                        ->where('uuid', $uuid)
                        ->firstOrFail();

            return $this->responseWithData(new ResourceCart($cart), 'cart.show');
        } catch (\Exception $e) {
          return $this->responseWithError($e, 'cart.show');
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
            $cart = Cart::where('uuid', $uuid)->firstOrFail();
            $data = $request->validated();

            $cart->update($data);

            return $this->responseWithData(new ResourceCart($cart), 'cart.update');
        } catch (\Exception $e) {
          return $this->responseWithError($e, 'cart.update');
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
            $cart = Cart::where('uuid', $uuid)->firstOrFail();
            $cart->delete();

            return $this->responseWithMessage('cart.delete');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'cart.delete');
        }
    }

    private function validateStockAvailable($productStock, $cartQuantity, $quantityReq)
    {
        if (($cartQuantity + $quantityReq) > $productStock) throw new QuantityException("cart.exceptions.quantity");
    }
}
