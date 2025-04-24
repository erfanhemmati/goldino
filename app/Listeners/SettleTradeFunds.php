<?php

namespace App\Listeners;

use App\Events\TradeExecuted;
use App\Services\Interfaces\BalanceServiceInterface;
use Illuminate\Contracts\Events\Dispatcher;

class SettleTradeFunds
{
    private BalanceServiceInterface $balances;

    public function __construct(BalanceServiceInterface $balances)
    {
        $this->balances = $balances;
    }

    /**
     * Handle the event.
     */
    public function handle(TradeExecuted $event): void
    {
        $trade = $event->trade;

        // Buyer: burn locked quote, credit base
        $this->balances->withdrawLockedFunds(
            $trade->buyer_user_id,
            $trade->quote_coin_id,
            (float) $trade->total
        );
        $this->balances->creditFunds(
            $trade->buyer_user_id,
            $trade->base_coin_id,
            (float) $trade->amount
        );

        // Seller: burn locked base, credit quote minus fee
        $this->balances->withdrawLockedFunds(
            $trade->seller_user_id,
            $trade->base_coin_id,
            (float) $trade->amount
        );
        $this->balances->creditFunds(
            $trade->seller_user_id,
            $trade->quote_coin_id,
            (float) $trade->total - (float) $trade->fee
        );
    }
} 