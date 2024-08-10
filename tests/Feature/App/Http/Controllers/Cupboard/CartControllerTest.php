<?php

namespace Tests\Feature\App\Http\Controllers\Cupboard;

use App\Models\Cupboard\{ User, Cart, Product };
use Illuminate\Support\Str;
use Tests\TestCase;

# vendor/bin/phpunit tests/Feature/App/Http/Controllers/Cupboard/CartControllerTest.php --filter={test-name}
class CartControllerTest extends TestCase
{
    protected $user;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockCart();
        // $this->payload = $this->createPayload();
    }

    /** @test */
    function get_index_standby_cart_success()
    {
        $response = $this->requestResource('GET', 'carts', $this->getIndexPayload('standby'));
        $cartCount = count($this->getCartRecordsByStatus('standby'));
        $cartResponseCount = count($this->getData($response));

        $this->assertResponseSuccess($response);
        $this->assertTrue($cartCount === $cartResponseCount);
    }

    /** @test */
    function get_index_cancelled_cart_success()
    {
        
        $response = $this->requestResource('GET', 'carts', $this->getIndexPayload('cancelled'));
        $cartCount = count($this->getCartRecordsByStatus('cancelled'));
        $cartResponseCount = count($this->getData($response));

        $this->assertResponseSuccess($response);
        $this->assertTrue($cartCount === $cartResponseCount);
    }

    /** @test */
    function get_index_declined_cart_success()
    {
        
        $response = $this->requestResource('GET', 'carts', $this->getIndexPayload('declined'));
        $cartCount = count($this->getCartRecordsByStatus('declined'));
        $cartResponseCount = count($this->getData($response));

        $this->assertResponseSuccess($response);
        $this->assertTrue($cartCount === $cartResponseCount);
    }

    /** @test */
    function store_new_cart_record_success()
    {
        $product = Product::factory()->withReview()->create();
        $qty = rand(1, $product->stock);
        $response = $this->requestResource('POST', "carts", [
            'user_id'    => $this->userId,
            'product_id' => $product->uuid,
            'quantity'   => $qty,
            'status'     => 'standby'
        ]);

        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('carts', [
            'user_id'    => $this->userLogged->id,
            'product_id' => $product->id,
            'quantity'   => $qty,
            'status'     => 'standby'
        ]);
    }

    /** @test */
    function get_show_of_cart_by_status()
    {
        $response = $this->requestResource('GET', "carts/{$this->userId}");
    }

    /** @test */
    function update_cart_successfully()
    {
        $cart = Cart::factory()->create([
            "status"   => "standby",
            "quantity" => 1
        ]);
        $nwQty = rand(2, 20);
        $response = $this->requestResource('PUT', "carts/{$cart->uuid}", [
            "user_id" => $cart->user->uuid,
            "product_id" => $cart->product->uuid,
            "status" => "accepted",
            "quantity" => $nwQty
        ]);
        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('carts', [
          'uuid'       => $cart->uuid,
          'user_id'    => $cart->user->id,
          'product_id' => $cart->product->id,
          'quantity'   => $nwQty,
          'status'     => 'accepted'
      ]);
    }

    /** @test */
    function remove_cart_successfully()
    {
        $cart = Cart::factory()->create();
        $response = $this->requestResource('DELETE', "carts/{$cart->uuid}");
        $this->assertResponseSuccess($response);
        $this->assertDatabaseMissing('carts', [
            'uuid' => $cart->uuid,
            'user_id' => $cart->user_id,
            'product_id' => $cart->product_id,
            'status' => $cart->status,
            'quantity' => $cart->quantity,
            'deleted_at' => null
        ]);
    }

    private function getCartRecordsByStatus($status)
    {
        return Cart::where('user_id', $this->userLogged->id)->where('status', $status)->take(30)->get();
    }

    private function mockCart()
    {
        return Cart::factory(2)->create(["user_id" => $this->userLogged->id]);
    }

    private function getIndexPayload($status)
    {
        return [
            'per_page'  => 30,
            'page'      => 1,
            'user_id'   => $this->userId,
            'status' => $status
        ];
    }
}