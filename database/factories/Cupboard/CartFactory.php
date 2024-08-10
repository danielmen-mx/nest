<?php

namespace Database\Factories\Cupboard;

use App\Models\Cupboard\{Cart, Product, Review, User };
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::first();
        $product = Product::factory()->withReview()->create();
        $status = $this->faker->randomElement(['standby', /* 'accepted', */ 'cancelled', 'declined']);

        return [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => rand(1,10),
            'status' => $status
        ];
    }
}
