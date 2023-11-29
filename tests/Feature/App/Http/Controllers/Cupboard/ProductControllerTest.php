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
            'uuid'           => $data->id,
            'name'           => $data->name,
            'price'          => $data->price,
            'shipping_price' => $data->shipping_price,
            'stock'          => $data->stock,
            'description'    => $data->description,
        ]);
    }

    /** @test */
    function show_product_success()
    {
        $product = $this->mockProducts()->first();
        $response = $this->requestResource('GET', "products/{$product->uuid}");
        $this->assertResponseSuccess($response);
        $data = $this->getData($response);
        $this->assertTrue(
            $product->uuid == $data->id,
            $product->name == $data->name,
            $product->price == $data->price,
            $product->shipping_price == $data->shipping_price,
            $product->description == $data->description,
        );
    }

    /** @test */
    function update_product_success()
    {
        $product = $this->mockProducts()->first();
        $response = $this->requestResource('PUT', "products/{$product->uuid}", $this->updatePayload($product));

        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('products', [
            'id'             => $product->id,
            'name'           => $this->payload['name'],
            'price'          => $this->payload['price'],
            'shipping_price' => $this->payload['shipping_price'],
            'stock'          => $this->payload['stock'],
            'description'    => $this->payload['description'],
        ]);
    }

    /** @test */
    function delete_product_success()
    {
        $product = $this->mockProducts()->first();
        $response = $this->requestResource('DELETE', "products/{$product->uuid}");

        $this->assertResponseSuccess($response);
        $this->assertDatabaseMissing('products', [
            'uuid'           => $product->uuid,
            'name'           => $product->name,
            'price'          => $product->price,
            'shipping_price' => $product->shipping_price,
            'stock'          => $product->stock,
            'description'    => $product->description,
            'deleted_at'     => null
        ]);
    }

    private function mockProducts($stock = 1)
    {
        return Product::factory($stock)->withReview()->create();
    }

    private function updatePayload($product)
    {
        $this->payload['name'] = "Product updated ".$this->faker->sentence;
        $this->payload['price'] = number_format($product->price + rand(10,99), 2);
        $this->payload['shipping_price'] = number_format($product->shipping_price + rand(10, 99), 2);
        $this->payload['stock'] = $product->stock + rand(2,10);
        $this->payload['description'] = "Description updated ".$this->faker->sentence;

        return $this->payload;
    }

    private function createPayload()
    {
        return [
            'name'           => "New product " . $this->faker->sentence,
            'price'          => number_format(rand(10,200), 2),
            'shipping_price' => number_format(rand(20,100), 2),
            'stock'          => rand(1, 10),
            'description'    => $this->faker->sentence,
            'user_id'        => $this->user->uuid
        ];
    }
}
