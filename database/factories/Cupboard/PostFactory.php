<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\Post;
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

        return [
            'name' => "New post " . $name,
            'autor' => $autor,
            'description' => $description
        ];
    }
}
