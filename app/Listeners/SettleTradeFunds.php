<?php

namespace App\Listeners;

use App\Events\TradeExecuted;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Interfaces\BalanceServiceInterface;

class SettleTradeFunds implements ShouldQueue
{
    /**
     * @var BalanceServiceInterface     $balanceService
     */
    private BalanceServiceInterface     $balanceService;

    /**
     * @param BalanceServiceInterface   $balanceService
     */
    public function __construct
    (
        BalanceServiceInterface         $balanceService
    )
    {
        $this->balanceService =         $balanceService;
    }

    /**
     * Handle the event.
     */
    public function handle(TradeExecuted $event): void
    {
        $trade = $event->trade;

        $this->balanceService->transferFunds(
            $trade->buyer_user_id,
            $trade->seller_user_id,
            $trade->base_coin_id,
            $trade->quote_coin_id,
            $trade->amount,
            $trade->total,
            $trade->buyer_fee,
            $trade->seller_fee
        );
    }
}

