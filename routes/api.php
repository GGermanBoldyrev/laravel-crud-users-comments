<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Посты
Route::get('/posts/{userId}/active', [PostController::class, 'userActive']);
Route::get('/posts/mine', [PostController::class, 'mine']);

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class);
