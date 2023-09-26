<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\User;
use Tests\TestCase;
use Illuminate\Support\Str;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/UserControllerTest.php --filter={test-name}
class UserControllerTest extends TestCase
{
    protected $user;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->payload = $this->createPayload();
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
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $response->assertSuccessful();
        $this->assertDatabaseHas("users", [
            'uuid' => $this->user->uuid,
            "email" => $this->payload['email'],
            "first_name" => $this->payload['first_name'],
            "last_name" => $this->payload['last_name'],
            "username" => $this->payload['username'],
            "language" => $this->payload['language'],
        ]);

        $this->assertTrue(!password_verify($this->user->password, $this->payload['password']));
    }

    /** @test */
    function update_user_fail_for_email_duplicated()
    {
        $this->payload['email'] = $this->user->email;
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("duplicated_email"));
    }

    /** @test */
    function update_user_fail_for_email_in_use_for_anohter_user()
    {
        $user = User::first();
        $this->payload['email'] = $user->email;
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("email_in_use"));
    }

    /** @test */
    function update_user_fail_for_username_duplicated()
    {
        $this->payload['username'] = $this->user->username;
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("duplicated_username"));
    }

    /** @test */
    function update_user_fail_for_username_in_use_for_anohter_user()
    {
        $user = User::first();
        $this->payload['username'] = $user->username;
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("username_in_use"));
    }

    /** @test */
    function update_user_fail_for_password_duplicated()
    {
        $this->payload['password'] = 'test-example';
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("duplicated_password"));
    }

    /** @test */
    function update_user_fail_for_incorrect_language_option()
    {
        $this->payload['language'] = 'bra';
        $response = $this->requestResource('PUT', "users/{$this->user->uuid}", $this->payload);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("language"));
    }

    /** @test */
    function show_user_success()
    {
        $response = $this->requestResource('GET', "users/{$this->user->uuid}");
        $this->assertResponseSuccess($response);

        $data = $response->getData()->data;
        $this->assertTrue(
            $data->id === $this->user->uuid,
            $data->username === $this->user->username,
            $data->email === $this->user->email,
            $data->language === $this->user->language,
            $data->fullname === $this->user->getFullName()
        );
    }

    /** @test */
    function validate_username_success()
    {
        $newUsername = $this->user->username . Str::random(6);
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-username", [
            "username" => $newUsername
        ]);
        $this->assertResponseSuccess($response);
        $this->assertTrue($response->getData()->message === $this->translation("username"));
    }

    /** @test */
    function validate_username_fail_for_username_duplicated()
    {
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-username", [
            "username" => $this->user->username
        ]);
        $this->assertResponseFailure($response);
        $this->assertTrue($response->getData()->exception === $this->validationTranslation("duplicated_username"));
    }

    /** @test */
    function validate_username_fail_for_username_in_use()
    {
        $anotherUser = User::first();
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-username", [
            "username" => $anotherUser->username
        ]);
        $this->assertResponseFailure($response);
        $this->assertTrue($response->getData()->exception === $this->validationTranslation("username_in_use"));
    }

    /** @test */
    function validate_email_success()
    {
        $newEmail = $this->user->username . Str::random(6). "@webunderdevelopment.com";
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-email", [
            "email" => $newEmail
        ]);
        $this->assertResponseSuccess($response);
        $this->assertTrue($response->getData()->message === $this->translation("email"));
    }

    /** @test */
    function validate_email_fail_for_email_duplicated()
    {
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-email", [
            "email" => $this->user->email
        ]);
        $this->assertResponseFailure($response);
        $this->assertTrue($response->getData()->exception === $this->validationTranslation("duplicated_email"));
    }

    /** @test */
    function validate_email_fail_for_invalid_email()
    {
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-email", [
            "email" => $this->user->username
        ]);
        $this->assertResponseFailForValidation($response);
        $this->assertTrue($response->getData()->message === $this->validationTranslation("email_validation"));
    }

    /** @test */
    function validate_email_fail_for_email_in_use()
    {
        $anotherUser = User::first();
        $response = $this->requestResource('GET', "users/{$this->user->uuid}/validate-email", [
            "email" => $anotherUser->email
        ]);
        $this->assertResponseFailure($response);
        $this->assertTrue($response->getData()->exception === $this->validationTranslation("email_in_use"));
    }

    private function createPayload()
    {
        return [
            "id" => $this->user->uuid,
            "email" => $this->user->username.now()->timestamp."@webunderdevelopment.com",
            "first_name" => "Test" . now()->timestamp,
            "last_name" => "Example" . now()->timestamp,
            "username" => $this->user->username."-".now()->timestamp,
            "language" => $this->user->language == 'es' ? 'en' : 'es',
            "password" => "test-example-update"
        ];
    }

    private function validationTranslation($key)
    {
        return __('api_error.users.validation.' . $key);
    }

    private function translation($key)
    {
        return __('api.users.' . $key);
    }
}
