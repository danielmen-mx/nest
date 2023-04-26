<?php

use App\Http\Controllers\Cupboard\{ CommentController, PostController, ReactionController, UserController };
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UserController::class);
Route::resource('comment', CommentController::class);
Route::resource('reaction', ReactionController::class);

// some of the posts routes doesn't need a user the same for the store
Route::resource('posts', PostController::class);
