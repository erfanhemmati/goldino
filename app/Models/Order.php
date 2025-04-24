<?php

namespace App\Models;

use App\Enums\OrderType;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    const TYPE_BUY = 'BUY';
    const TYPE_SELL = 'SELL';

    const STATUS_OPEN = 'OPEN';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELED = 'CANCELED';

    protected $fillable = [
        'user_id',
        'base_coin_id',
        'quote_coin_id',
        'type',
        'amount',
        'remaining_amount',
        'filled_amount',
        'price',
        'total',
        'status',
    ];

    protected $casts = [
        'amount'            => 'decimal:8',
        'remaining_amount'  => 'decimal:8',
        'filled_amount'     => 'decimal:8',
        'price'             => 'decimal:8',
        'total'             => 'decimal:8',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the base coin for this order.
     */
    public function baseCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'base_coin_id');
    }

    /**
     * Get the quote coin for this order.
     */
    public function quoteCoin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'quote_coin_id');
    }

    /**
     * Get the buy trades for this order.
     */
    public function buyTrades(): HasMany
    {
        return $this->hasMany(Trade::class, 'buy_order_id');
    }

    /**
     * Get the sell trades for this order.
     */
    public function sellTrades(): HasMany
    {
        return $this->hasMany(Trade::class, 'sell_order_id');
    }

    /**
     * Determine if the order is a buy order.
     */
    public function isBuyOrder(): bool
    {
        return $this->type === OrderType::BUY->value;
    }

    /**
     * Determine if the order is a sell order.
     */
    public function isSellOrder(): bool
    {
        return $this->type === OrderType::SELL->value;
    }

    /**
     * Determine if the order is open.
     */
    public function isOpen(): bool
    {
        return $this->status === OrderStatus::OPEN->value;
    }

    /**
     * Determine if the order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED->value;
    }

    /**
     * Determine if the order is canceled.
     */
    public function isCanceled(): bool
    {
        return $this->status === OrderStatus::CANCELED->value;
    }
}
