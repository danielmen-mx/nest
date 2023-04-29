<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Exceptions\LoginException;
use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\Auth\Login as LoginRequest;
use App\Models\Cupboard\User;
use App\Http\Requests\Cupboard\UserCreateRequest;
use App\Http\Resources\Cupboard\User as UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
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

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $query = User::query();

            $query
              ->where('email', $request->email)
              ->orWhere('username', $request->email);

            $user = $query->first();

            if ($user) {
                $pass = Hash::check($request->password, $user->password);

                if ($pass) {

                    if ($user->deactivated_at != null) {
                        // return $this->responseWithErrorMessage('auth.login.deactivated', [], 401);
                        throw new LoginException('auth.login.deactivated');
                    }

                    if ($request->has('remember') && $request->input('remember')) {
                        Passport::personalAccessTokensExpireIn(now()->addDays(15));
                        $dataToken = $user->createToken('token');
                    } else {                                                
                        Passport::personalAccessTokensExpireIn(now()->addHours(12));
                        $dataToken = $user->createToken('token');
                    }

                    $expireIn = Carbon::parse($dataToken->token->expires_at)->timestamp;

                    $data = [
                        'user'    => new UserResource($user),
                        'token'   => $dataToken->accessToken,
                        'expires' => $expireIn,
                    ];

                    return response(['data' => $data ]);
                } else {
                    // return $this->responseWithErrorMessage('auth.login.password', [], 401);
                    throw new LoginException('auth.login.password');
                }
            } else {
                // return $this->responseWithErrorMessage('auth.login.user', [], 401);
                throw new LoginException('auth.login.user');
            }
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'user.login');
        }
    }
}
