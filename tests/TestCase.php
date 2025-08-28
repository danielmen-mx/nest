<?php

namespace Tests;

use App\Models\Cupboard\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Faker\Generator;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    protected $faker;
    protected $domain = "127.0.0.1:8000";
    protected $userLogged;
    protected $userId;
    const USER_ADMIN_EMAIL = "pablomen1131@gmail.com";
    const USER_ADMIN_PASSWORD = "admin";
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
        $this->faker = $this->withFaker();
        $this->userId = $this->userLogged->uuid;
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    private function login()
    {
        $this->userLogged = User::where('email', self::USER_ADMIN_EMAIL)->first();
        if(!$this->userLogged) {
            $this->userLogged = User::factory()->create([
                'username' => 'admin',
                'email' => self::USER_ADMIN_EMAIL,
                'first_name' => 'Daniel',
                'last_name' => 'Mendez',
                'is_admin' => true,
                'password' => bcrypt(self::USER_ADMIN_PASSWORD)
            ]);
        }

        Passport::actingAs($this->userLogged, [], "api");
    }

    protected function assertResponseFailForValidation($response)
    {
        $this->assertTrue(
            $response->getStatusCode() === 500 || $response->getStatusCode() === 422,
            $response['message']
        );
    }

    protected function assertResponseFailure($response)
    {
        $this->assertTrue(
            $response->getStatusCode() === 417 || $response->getStatusCode() === 400,
            $response['message']
        );
    }

    protected function assertResponseSuccess($response)
    {
        $this->assertTrue(
            $response->getStatusCode() === 201 || $response->getStatusCode() === 200,
            $response['message']
        );
    }

    protected function getData($resp)
    {
        return $resp->getData()->data;
    }

    protected function createRequest(string $method, array $attributes): Request
    {
        $request = new \Illuminate\Http\Request();
        $request->setMethod($method);
        collect($attributes)->each(function ($value, $key) use (&$request) {
            $request->request->add([$key => $value]);
        });

        return $request;
    }

    protected function requestResource($method, $endpoint = "", $payload = []): TestResponse
    {
        return $this->json($method, "http://{$this->domain}/api/{$endpoint}", $payload);
    }
}
