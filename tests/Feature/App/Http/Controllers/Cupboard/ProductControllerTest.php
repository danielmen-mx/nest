<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\{ Product, User };
use Illuminate\Support\Str;
use Tests\TestCase;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/ProductControllerTest.php --filter={test-name}
class ProductControllerTest extends TestCase
{
    protected $user;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    function get_index_of_products_from_admin_success()
    {
        $this->mockProducts(16);
        $response = $this->requestResource('GET', 'products', ['per_page' => 15]);
        $this->assertResponseSuccess($response);
        $this->assertTrue(count($this->getData($response)) === 15);
    }

    /** @test */
    function get_index_of_products_from_products_home_page_success()
    {
        $this->mockProducts(7);
        $response = $this->requestResource('GET', 'products');
        $this->assertResponseSuccess($response);
        $this->assertTrue(count($this->getData($response)) === 6);
    }

    private function mockProducts($quantity = 1)
    {
        Product::factory($quantity)->withReview()->create();
    }
}
