<?php

namespace App\Http\Controllers\Cupboard;

use App\Models\Cupboard\User;
use App\Http\Requests\Cupboard\UserCreateRequest;
// use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function register(UserCreateRequest $request)
    {
        try {
            $user = User::query()->create($request->all());

            $accessToken = $user->createToken('token')->accessToken;

            return response(['user' => $user, 'token' => $accessToken]);
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'user.register');
        }
    }

    public function login()
    {
        try {
          //code...
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'user.login');
        }
    }
}
