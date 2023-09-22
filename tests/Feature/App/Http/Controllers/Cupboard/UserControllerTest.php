<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\User;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/UserControllerTest.php
class UserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    function get_user_lists_successfully()
    {
        $response = $this->requestResource('GET', 'users', []);
        $allUsers = User::get()->count();

        $response->assertSuccessful();
        $this->assertTrue(count($response->getData()->data) == $allUsers);
    }

    /** @test */
    function update_user_data_success()
    {
        $user = User::factory()->create();
        $payload = [
            "id" => $user->uuid,
            "email" => $user->username.now()->timestamp."@webunderdevelopment.com",
            "first_name" => "Test" . now()->timestamp,
            "last_name" => "Example" . now()->timestamp,
            "username" => $user->username."-".now()->timestamp,
            "language" => $user->language == 'es' ? 'en' : 'es',
            "password" => "test-example-update"
        ];

        $response = $this->requestResource('PUT', "users/$user->uuid", $payload);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users", [
            'uuid' => $user->uuid,
            "email" => $payload['email'],
            "first_name" => $payload['first_name'],
            "last_name" => $payload['last_name'],
            "username" => $payload['username'],
            "language" => $payload['language'],
        ]);

        $this->assertTrue(!password_verify($user->password, $payload['password']));
    }
}
