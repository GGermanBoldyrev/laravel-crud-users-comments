<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::all()->each(function (Post $post) {
            $comments = Comment::factory(rand(2, 5))
                ->forPost($post)
                ->create();

            $comments->each(function (Comment $comment) {
                Comment::factory(rand(0, 2))->asReplyTo($comment)->create();
            });
        });
    }
}
