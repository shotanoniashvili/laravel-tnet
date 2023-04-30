<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuantityRequest;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\CartItemResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Exception;

class CartController extends Controller
{
    protected User $user;

    public function __construct()
    {
        $this->user = User::find(1);
    }

    public function add(Product $product)
    {
        try {
            $cart = Cart::addProduct($product, $this->user);

            return new BaseJsonResource(true, $cart);
        } catch (Exception $e) {
            return new BaseJsonResource(false);
        }
    }

    public function remove(Product $product)
    {
        try {
            $result = Cart::removeProduct($product, $this->user);

            return new BaseJsonResource($result);
        } catch (Exception $e) {
            return new BaseJsonResource(false);
        }
    }

    public function setQuantity(QuantityRequest $request, Product $product)
    {
        try {
            $result = Cart::setQuantity($product, $this->user, $request->quantity);

            return new BaseJsonResource($result);
        } catch (Exception $e) {
            return new BaseJsonResource(false);
        }
    }

    public function getCart()
    {
        $cartItems = $this->user->cartItems;

        return new BaseJsonResource(true, [
            'products' => CartItemResource::collection($cartItems),
            'discount' => Cart::getDiscount($this->user)
        ]);
    }
}
