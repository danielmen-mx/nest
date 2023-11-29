<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\{ Product, Review, User };
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->sentence;
        $description = $this->faker->sentence(20);
        $user = User::first();

        return [
            'name' => "New product " . $name,
            'price' => rand(10, 200),
            'shipping_price' => rand(20, 100),
            'stock' => rand(1, 20),
            'description' => $description,
            'assets' => null,
            'user_id' => $user->id
        ];
    }

    public function withReview()
    {
        return $this->afterCreating(function(Product $product) {
            $review = Review::create([
                'model_type' => Product::class,
                "model_id"   => $product->id
            ]);

            $product->update(['review_id' => $review->id]);
        });
    }
}
