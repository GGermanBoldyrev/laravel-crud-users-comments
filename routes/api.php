<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Посты
Route::get('/posts/{userId}/active', [PostController::class, 'userActive']);
Route::get('/posts/mine', [PostController::class, 'mine']);

// Комментарии
Route::get('/comments/{userId}/to-active-posts', [CommentController::class, 'userToActivePosts']);
Route::get('/comments/{commentId}/replies', [CommentController::class, 'replies']);
Route::get('/comments/mine', [CommentController::class, 'mine']);
Route::get('/posts/{postId}/comments', [CommentController::class, 'byPost']);

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('comments', CommentController::class);
