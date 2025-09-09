<?php

namespace App\Providers;

use App\Services\CommentService;
use App\Services\Contracts\CommentServiceInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
