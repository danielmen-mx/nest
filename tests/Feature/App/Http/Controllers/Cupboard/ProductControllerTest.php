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
        $this->user = User::where('is_admin', true)->first();
        $this->payload = $this->createPayload();
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

    /** @test */
    function store_new_product_success()
    {
        $response = $this->requestResource('POST', 'products', $this->payload);
        $this->assertResponseSuccess($response);

        $data = $this->getData($response);
        $this->assertDatabaseHas('products', [
            'uuid' => $data->id,
            'name' => $data->name,
            'price' => $data->price,
            'shipping_price' => $data->shipping_price,
            'quantity' => $data->quantity,
            'description' => $data->description,
        ]);
    }

    private function mockProducts($quantity = 1)
    {
        Product::factory($quantity)->withReview()->create();
    }

    private function createPayload()
    {
        return [
            'name' => "New product " . $this->faker->sentence,
            'price' => number_format(rand(10,200), 2),
            'shipping_price' => number_format(rand(20,100), 2),
            'quantity' => rand(1, 10),
            'description' => $this->faker->sentence,
            'user_id' => $this->user->uuid
        ];
    }
}
