<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {    

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
    public function store(Request $request)
    {
        try {
            
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
    public function update(Request $request, $uuid)
    {
        try {
            
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
            
        } catch (\Exception $e) {
          return $this->responseWithError($e, 'cart.delete');
        }
    }
}
