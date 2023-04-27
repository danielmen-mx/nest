<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/UserControllerTest.php
class UserControllerTest extends TestCase
{
    /** @test */
    public function store_new_user_successfully()
    {
        $response = $this->requestResource('POST', 'users', []);
        dd($response);
    }
}
