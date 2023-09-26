<?php

use App\Http\Controllers\Cupboard\Api\{ AuthController, CommentController, PostController, ReactionController, UserController };
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('login', 'AuthController@login');
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

// add middleware to avoid an user unidentified can send requests to our controllers
Route::resource('users', UserController::class);
Route::resource('comments', CommentController::class);
Route::resource('reactions', ReactionController::class);
Route::get('users/{userId}/validate-username', [UserController::class, 'validateUsername']);
Route::get('users/{userId}/validate-email', [UserController::class, 'validateEmail']);

// some of the posts routes doesn't need a user the same for the store
Route::resource('posts', PostController::class);
