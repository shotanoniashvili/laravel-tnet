<?php

namespace Tests\Feature;

use App\Exceptions\ProductDoesntExistInCartException;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductGroupItem;
use App\Models\User;
use App\Models\UserProductGroup;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CartTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);

        $this->user = User::find(1);
    }

    public function testAddProduct(): void
    {
        $product1 = Product::factory()->createOne();
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product1->product_id,
            'quantity' => 1
        ]);

        // Test for existing product in cart
        $response = $this->post('/api/cart/' . $product1->product_id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonFragment([
                'quantity' => 2
            ]);

        // Test for new product in cart
        $product2 = Product::factory()->createOne();
        $response = $this->post('/api/cart/' . $product2->product_id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonFragment([
                'quantity' => 1
            ]);
    }

    public function testRemoveProduct(): void
    {
        $product = Product::factory()->createOne();
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product->product_id,
            'quantity' => 2
        ]);

        // Decrement quantity
        $response = $this->delete('/api/cart/' . $product->product_id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);
        $this->assertDatabaseHas(Cart::class, [
            'user_id' => $this->user->id,
            'product_id' => $product->product_id,
            'quantity' => 1
        ]);

        // Remove record from DB
        $response = $this->delete('/api/cart/' . $product->product_id);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);
        $this->assertDatabaseMissing(Cart::class, [
            'user_id' => $this->user->id,
            'product_id' => $product->product_id
        ]);

        // Throw exception if product doesn't exist into cart
        $this->expectException(ProductDoesntExistInCartException::class);
        Cart::removeProduct($product, $this->user);
    }


    public function testSetQuantity(): void
    {
        $product = Product::factory()->createOne();

        // Create new cart item and set quantity
        $response = $this->patch('/api/cart/' . $product->product_id, [
            'quantity' => 2
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);
        $this->assertDatabaseHas(Cart::class, [
            'user_id' => $this->user->id,
            'product_id' => $product->product_id,
            'quantity' => 2
        ]);

        // Change quantity of existing cart item
        $response = $this->patch('/api/cart/' . $product->product_id, [
            'quantity' => 4
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);
        $this->assertDatabaseHas(Cart::class, [
            'user_id' => $this->user->id,
            'product_id' => $product->product_id,
            'quantity' => 4
        ]);

        // Remove cart item
        $response = $this->patch('/api/cart/' . $product->product_id, [
            'quantity' => 0
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ]);
        $this->assertDatabaseMissing(Cart::class, [
            'user_id' => $this->user->id,
            'product_id' => $product->product_id,
        ]);
    }

    public function testGetCart(): void
    {
        // Clear records
        Cart::where('user_id', $this->user->id)->delete();

        $product1 = Product::factory()->createOne();
        $product2 = Product::factory()->createOne();
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product1->product_id,
            'quantity' => 2
        ]);
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product2->product_id,
            'quantity' => 3
        ]);

        $response = $this->get('/api/cart');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonFragment([
                'product_id' => $product1->product_id,
                'quantity'   => 2,
                'price'      => $product1->price,
            ])
            ->assertJsonFragment([
                'product_id' => $product2->product_id,
                'quantity'   => 3,
                'price'      => $product2->price,
            ]);
    }

    public function testDiscount(): void
    {
        // Clear records
        Cart::where('user_id', $this->user->id)->delete();
        ProductGroupItem::query()->delete();
        UserProductGroup::query()->delete();

        $product1 = Product::factory()->createOne();
        $product2 = Product::factory()->createOne();
        $discount = 20;
        UserProductGroup::firstOrCreate([
            'group_id' => 1,
            'user_id'  => 1,
            'discount' => $discount
        ]);

        ProductGroupItem::firstOrCreate([
            'product_id' => $product1->product_id,
            'group_id' => 1
        ]);
        ProductGroupItem::firstOrCreate([
            'product_id' => $product2->product_id,
            'group_id' => 1
        ]);

        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product1->product_id,
            'quantity' => 2
        ]);

        // If all products from the group aren't into the cart
        $response = $this->get('/api/cart');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonFragment([
                'discount' => 0,
            ]);

        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $product2->product_id,
            'quantity' => 3
        ]);

        // If all products from the group aren't into the cart
        $response = $this->get('/api/cart');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
            ])
            ->assertJsonFragment([
                'discount' => (int)(2 * ($product1->price + $product2->price) * $discount / 100),
            ]);
    }
}
