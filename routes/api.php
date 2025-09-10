<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('/posts/{userId}/active', [PostController::class, 'userActive']);
Route::get('/comments/{userId}/to-active-posts', [CommentController::class, 'userToActivePosts']);
Route::get('/comments/{commentId}/replies', [CommentController::class, 'replies']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'byPost']);

Route::apiResource('users', UserController::class)->only(['index', 'show']);
Route::apiResource('posts', PostController::class)->only(['index', 'show']);
Route::apiResource('comments', CommentController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts/mine', [PostController::class, 'mine']);
    Route::get('/comments/mine', [CommentController::class, 'mine']);

    Route::apiResource('users', UserController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('posts', PostController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('comments', CommentController::class)->only(['store', 'update', 'destroy']);
});
