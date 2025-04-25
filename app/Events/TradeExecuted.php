<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class TradeExecuted implements ShouldDispatchAfterCommit
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
