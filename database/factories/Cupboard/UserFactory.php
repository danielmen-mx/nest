<?php

namespace Database\Factories\Cupboard;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $email = fake()->unique()->email();
        return [
            'username'          => explode('@', $email)[0] . now()->timestamp,
            'email'             => $email,
            'is_admin'          => false,
            'first_name'        => $this->faker->firstName,
            'last_name'         => ucfirst(Str::slug($this->faker->lastName)),
            'language'          => "en",
            'email_verified_at' => now(),
            'password'          => bcrypt('test-example'),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
