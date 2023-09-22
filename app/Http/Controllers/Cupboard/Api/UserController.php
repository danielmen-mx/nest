<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\User\Store;
use App\Http\Requests\Cupboard\User\Update;
use App\Http\Resources\Cupboard\User as ResourceUser;
use App\Http\Resources\Cupboard\UserCollection;
use App\Models\Cupboard\User;
// use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::get();

            return $this->responseWithData(new UserCollection($users), 'users.index');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.index');
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
        // try {
        //     $data = $request->validated();

        //     $user = User::create($data);
        //     return $this->responseWithMessage('hello there');
        // } catch (\Exception $e) {
        //     return $this->responseWithError($e, 'users.store');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {
            $user = User::findByUuid($userId)->firstOrFail();

            return $this->responseWithData(new ResourceUser($user), "users.show");
        } catch (\Exception $e) {
            return $this->responseWithError($e, "users.show");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $userId)
    {        
        try {
            $user = User::findByUuid($userId)->firstOrFail();
            $data = $request->validated();

            $user->update($data);
            return $this->responseWithData(new ResourceUser($user), "users.update");
        } catch (\Exception $e) {
            return $this->responseWithError($e, "users.update");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
