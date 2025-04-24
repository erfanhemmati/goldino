<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'symbol',
        'is_fiat',
        'is_active',
    ];

    protected $casts = [
        'is_fiat' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the balances for the coin.
     */
    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    /**
     * Get the base orders for the coin.
     */
    public function baseOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'base_coin_id');
    }

    /**
     * Get the quote orders for the coin.
     */
    public function quoteOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'quote_coin_id');
    }
}
