<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\User;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/PasswordResetControllerTest.php --filter={test-name}
class PasswordResetControllerTest extends TestCase
{
    protected $user;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createDummyUser();
    }

    /** @test */
    function send_reset_link_password_success()
    {
        $response = $this->requestResource('POST', "forgot-password", [
            'email' => $this->user->email
        ]);
        dd($response);
        $this->assertResponseSuccess($response);
    }

    private function createDummyUser()
    {
        if ($user = User::where('email', 'pablodanimen@gmail.com')->first()) return $user;
        return User::factory()->create([
            'username' => 'testuser',
            'email' => 'pablodanimen@gmail.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password' => bcrypt('password123')
        ]);
    }
}
