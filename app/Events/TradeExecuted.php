<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeExecuted
{
    use Dispatchable, SerializesModels;

    /**
     * The trade that was executed.
     *
     * @var Trade
     */
    public Trade $trade;

    /**
     * Create a new event instance.
     *
     * @param Trade $trade
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }
} 