<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Post::query()->inRandomOrder()->get()->each(function (Post $post) use ($users) {
            $comments = Comment::factory()
                ->count(rand(2, 5))
                ->recycle($users)
                ->forPost($post)
                ->create();

            $comments->each(function (Comment $parent) use ($users) {
                Comment::factory()
                    ->count(rand(0, 2))
                    ->recycle($users)
                    ->asReplyTo($parent)
                    ->create();
            });
        });
    }
}
