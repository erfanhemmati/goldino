<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_user_id',
        'seller_user_id',
        'sell_order_id',
        'buy_order_id',
        'order_id',
        'base_coin_id',
        'quote_coin_id',
        'amount',
        'price',
        'total',
        'fee',
    ];

    protected $casts = [
        'amount'    => 'decimal:8',
        'price'     => 'decimal:8',
        'total'     => 'decimal:8',
        'fee'       => 'decimal:8',
    ];

    /**
     * Get the buyer user.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    /**
     * Get the seller user.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    /**
     * Get the sell order.
     */
    public function sellOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'sell_order_id');
    }

    /**
     * Get the buy order.
     */
    public function buyOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'buy_order_id');
    }

    /**
     * Get the order that initiated this trade.
     */
    public function initiatingOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the base coin.
     */
    public function baseCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'base_coin_id');
    }

    /**
     * Get the quote coin.
     */
    public function quoteCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'quote_coin_id');
    }
}
