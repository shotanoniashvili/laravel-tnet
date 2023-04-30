<?php

namespace App\Models;

use App\Exceptions\ProductDoesntExistInCartException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function getCartByProduct(Product $product, User $user): Cart
    {
        return self::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->product_id
        ]);
    }

    public static function addProduct(Product $product, User $user): Cart
    {
        $cart = self::getCartByProduct($product, $user);

        if ($cart->exists) {
            $cart->increment('quantity');
        } else {
            $cart->quantity = 1;
            $cart->save();
        }

        return $cart;
    }

    public static function setQuantity(Product $product, User $user, int $qty): bool
    {
        if ($qty === 0) {
            return self::where([
                'product_id' => $product->product_id,
                'user_id'    => $user->id
            ])->delete();
        }

        $cart = self::updateOrCreate([
            'product_id' => $product->product_id,
            'user_id'    => $user->id
        ], [
            'quantity' => $qty
        ]);

        return $cart instanceof self;
    }

    public static function removeProduct(Product $product, User $user): bool
    {
        $cart = self::getCartByProduct($product, $user);

        if (!$cart->exists) {
            throw new ProductDoesntExistInCartException();
        }

        if ($cart->quantity === 1) {
            return $cart->delete();
        }

        return $cart->decrement('quantity') !== false;
    }

    public static function getDiscount(User $user): int
    {
        $discount = UserProductGroup::join('product_group_items as pgi', 'pgi.group_id', '=', 'user_product_groups.group_id')
            ->join('products as p', 'p.product_id', '=', 'pgi.product_id')
            ->leftJoin('carts as c', 'c.product_id', '=', 'pgi.product_id')
            ->where('user_product_groups.user_id', $user->id)
            ->where('c.user_id', $user->id)
            ->select('p.product_id', 'p.price', 'pgi.group_id', 'c.id', 'c.quantity', 'discount')
            ->get();

        $allDiscountedProductsCount = ProductGroupItem::where('group_id', $discount->first()?->group_id)->count();

        if (!$discount->count() || $discount->count() < $allDiscountedProductsCount) {
            return 0;
        }

        $minQuantity = $discount->sortBy('quantity')->first();
        $discountPercent = $discount->first()->discount;

        return $discount->sum('price') * $minQuantity->quantity * $discountPercent / 100;
    }
}
