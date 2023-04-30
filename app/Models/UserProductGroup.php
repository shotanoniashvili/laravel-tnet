<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProductGroup extends Pivot
{
    protected $table = 'user_product_groups';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'user_id',
        'discount'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,
            'product_group_items',
            'group_id',
            'product_id',
            'product_id',
            'group_id'
        );
    }
}
