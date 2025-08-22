<?php

namespace App\Http\Controllers\Cupboard\Api;

use App\Http\Controllers\Cupboard\ApiController;
use App\Http\Requests\Cupboard\User\EmailValidation;
use App\Http\Requests\Cupboard\User\Index;
use App\Http\Requests\Cupboard\User\PasswordValidation;
use App\Http\Requests\Cupboard\User\Store;
use App\Http\Requests\Cupboard\User\Update;
use App\Http\Requests\Cupboard\User\ValidateFieldTrait;
use App\Http\Resources\Cupboard\User as ResourceUser;
use App\Http\Resources\Cupboard\UserCollection;
use App\Models\Cupboard\User;
use App\Models\Traits\PaginationTrait;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    use ValidateFieldTrait, PaginationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        try {
            $users = User::query()
                // ->with(['comments', 'reactions', 'review'])
                ->orderBy('created_at', 'asc')
                ->paginate($request->per_page ?? 6);
            
            $resource = $this->loadRequestResource($users, $request->per_page);

            return $this->responseWithPaginationResource(new UserCollection($users), $resource, 'users.index');
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

    public function switchAdmin($userId, Request $request)
    {
        try {
            $landlordUser = User::findByUuid($userId)->firstOrFail();
            if ($landlordUser->is_landlord == 0) return $this->responseWithErrorMessage('users.switch_only_allowed_from_landlord');
            $user = User::findByUuid($request['id'])->firstOrFail();
            $user->is_admin = $request['is_admin'];
            $user->save();
            $user->refresh();

            return $this->responseWithData(new ResourceUser($user), "users.switch_admin");
        } catch (\Exception $e) {
            return $this->responseWithError($e, "users.switch_admin");
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

    public function removeUser($landlordId, Request $userId)
    {
        try {
            $landlordUser = User::findByUuid($landlordId)->firstOrFail();
            if ($landlordUser->is_landlord == 0) return $this->responseWithErrorMessage('users.remove_only_allowed_from_landlord');
            $user = User::findByUuid($userId["id"])->firstOrFail();
            $user->delete();

            return $this->responseWithMessage('users.remove');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.remove');
        }
    }

    public function validateUsername($userId, Request $request)
    {       
        try {
            $this->setUser($userId);
            $this->validateField('username', $request->username);

            return $this->responseWithMessage('users.username');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.username');
        }
    }

    public function validateEmail($userId, EmailValidation $request)
    {
        try {
            $this->setUser($userId);
            $this->validateField('email', $request->email);

            return $this->responseWithMessage('users.email');
        } catch (\Exception $e) {
            return $this->responseWithError($e, 'users.email');
        }
    }

    public function changePassword($userId, PasswordValidation $request)
    {
        try {
            $user = User::findByUuid($userId)->firstOrFail();
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user->update($data);
            return $this->responseWithData(new ResourceUser($user), "users.password");
        } catch (\Exception $e) {
            return $this->responseWithError($e, "users.password");
        }
    }
}
