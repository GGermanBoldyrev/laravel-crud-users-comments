<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => $this->faker->sentence(),
        ];
    }

    public function forPost(?Post $post = null): self
    {
        return $this->state(function () use ($post) {
            $post = $post ?? Post::factory()->create();
            return [
                'commentable_id' => $post->id,
                'commentable_type' => $post::class,
            ];
        });
    }

    public function asReplyTo(?Comment $parent = null): self
    {
        return $this->state(function () use ($parent) {
            $parent = $parent ?? Comment::factory()->forPost()->create();
            return [
                'commentable_id' => $parent->id,
                'commentable_type' => $parent::class,
            ];
        });
    }
}
