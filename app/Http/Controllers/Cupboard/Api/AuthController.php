<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Requests\Cupboard\Auth\Login as LoginRequest;
use App\Http\Requests\Cupboard\Auth\UserCreateRequest;
use App\Http\Resources\Cupboard\User as UserResource;
use App\Http\Controllers\Cupboard\ApiController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\LoginException;
use Laravel\Passport\Passport;
use Illuminate\Http\Response;
use App\Models\Cupboard\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class AuthController extends ApiController
{
    public function register(UserCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::query()->create($data);
            $accessToken = $user->createToken('token')->accessToken;

            return response([
              'user' => $user,
              'token' => $accessToken,
              'message' => __('api.auth.register')
            ]);
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'auth.register');
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

                    return response([
                      'data' => $data
                    ]);
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

    public function logout(Request $request)
    {
        try {
            // TODO: complete validation of authentication in laravel, some api's are not secured
            $user = User::where('uuid', $request->all()['user'])->firstOrFail();
            $token = $user->token();

            if ($token) {
                $token->revoke();
            }

            // if (Auth::check()) {
            //     $token = Auth::user()->token();
            //     $token->revoke();
            // } else {

            // }

            return response('User is logout');
        } catch (\Exception $e) {
            return response(['error'=>'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
