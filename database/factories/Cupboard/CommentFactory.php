<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\{Comment, Post, Review, User };
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $modelType = Post::class;

        return [
            'user_id' => User::first()->id,
            'model_type' => $modelType,
            'model_id' => $modelType::first()->id,
            'comment' => $this->faker->sentence()
        ];
    }

    public function afterState()
    {
        return $this->afterCreating(function(Comment $comment) {
            //
        });
    }
}
