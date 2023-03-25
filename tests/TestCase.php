<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Faker\Generator;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    protected $faker;
    protected $domain = "127.0.0.1:8000";

    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
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
