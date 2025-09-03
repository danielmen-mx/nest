<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use app\Models\Cupboard\User;

class PasswordResetController extends ApiController
{
    public function sendResetLink(Request $request)
    {
      try {
          $request->validate(['email' => 'required|email|exists:users,email']);
          Password::sendResetLink(
              $request->only('email')
          );

          return $this->responseWithMessage('password.reset.send');
      } catch (\Throwable $th) {
          return $this->responseWithError($th, 'password.reset.send');
      }
    }

    public function reset(Request $request)
    {
        #TODO: Before you got excited about this method, its isnt completed, finish this later
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->save();
                }
            );

            if ($status == Password::INVALID_TOKEN) {
                return $this->responseWithError(null, 'password.reset.token', [], null, 422);
            }

            return $this->responseWithMessage('password.reset.success');
        } catch (\Throwable $th) {
            return $this->responseWithError($th, 'password.reset');
        }
    }
}