<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\{ Post, Reaction, User };
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ReactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $post = Post::factory()->create();

        return [
            "user_id" => User::first()->id,
            // "model_type" => $this->faker->randomElement([Post::class, Product::class])
            "model_type" => Post::class,
            "model_id" => $post->id,
            "reaction" => true
        ];
    }
}
