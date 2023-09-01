<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\Post;
use App\Models\Cupboard\Review;
use App\Models\Cupboard\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->sentence;
        $autor = $this->faker->firstName . " " . $this->faker->lastName;
        $description = $this->faker->sentence(20);
        $user = User::first();

        return [
            'name' => "New post " . $name,
            'autor' => $autor,
            'description' => $description,
            'user_id' => $user->id
        ];
    }

    public function withReview()
    {
        return $this->afterCreating(function(Post $post) {
            $review = Review::create([
                'post_id' => $post->id
            ]);

            $post->update(['review_id' => $review->id]);
        });
    }
}
