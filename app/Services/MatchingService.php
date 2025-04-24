<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderType;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\MatchingServiceInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\TradeRepositoryInterface;

class MatchingService implements MatchingServiceInterface
{
    private const MIN_FEE = 50000;          // Minimum fee (e.g. 50,000)
    private const MAX_FEE = 5000000;        // Maximum fee (e.g. 5,000,000)

    private OrderRepositoryInterface        $orderRepository;
    /**
     * @var TradeRepositoryInterface        $tradeRepository
     */
    private TradeRepositoryInterface        $tradeRepository;

    /**
     * @param OrderRepositoryInterface      $orderRepository
     * @param TradeRepositoryInterface      $tradeRepository
     */
    public function __construct
    (
        OrderRepositoryInterface            $orderRepository,
        TradeRepositoryInterface            $tradeRepository,
    ) {
        $this->orderRepository            = $orderRepository;
        $this->tradeRepository            = $tradeRepository;
    }

    /**
     * @inheritDoc
     */
    public function processOrder(int $orderId): array
    {
        return DB::transaction(function () use ($orderId) {

            $taker = $this->orderRepository->findByIdWithLock($orderId);
            if (! $taker || ! $taker->isOpen()) {
                return [];
            }

            $filledTrades = [];
            $baseCoinId   = $taker->base_coin_id;
            $quoteCoinId  = $taker->quote_coin_id;
            $type         = $taker->type;
            $price        = (float) $taker->price;
            $excludeUser  = $taker->user_id;

            if ($type === OrderType::BUY->value) {
                $candidates = $this->orderRepository->findSellMatchingOrders(
                    $baseCoinId,
                    $quoteCoinId,
                    $price,
                    $excludeUser
                );
            } else {
                $candidates = $this->orderRepository->findBuyMatchingOrders(
                    $baseCoinId,
                    $quoteCoinId,
                    $price,
                    $excludeUser
                );
            }

            /**
             * @var Order $maker
             */
            foreach ($candidates as $maker) {
                // Refresh taker state under lock
                $taker = $this->orderRepository->findByIdWithLock($taker->id);
                if ($taker->remaining_amount <= 0) {
                    break;
                }

                $takerRemaining = (float) $taker->remaining_amount;
                $makerRemaining = (float) $maker->remaining_amount;
                $fill           = min($takerRemaining, $makerRemaining);

                if ($fill <= 0) {
                    continue;
                }

                $buyerOrder  = $taker->isBuyOrder() ? $taker : $maker;
                $sellerOrder = $taker->isSellOrder() ? $taker : $maker;

                // Calculate fee for this trade
                $fee = $this->calculateFee($fill, $price);

                // Compute new status and remaining amounts
                $newTakerRemaining = $takerRemaining - $fill;
                $newMakerRemaining = $makerRemaining - $fill;
                $newTakerStatus    = $newTakerRemaining > 0 ? Order::STATUS_OPEN : Order::STATUS_COMPLETED;
                $newMakerStatus    = $newMakerRemaining > 0 ? Order::STATUS_OPEN : Order::STATUS_COMPLETED;

                // Update taker order
                $taker = $this->orderRepository->update([
                    'remaining_amount'  => $newTakerRemaining,
                    'filled_amount'     => (float) $taker->filled_amount + $fill,
                    'status'            => $newTakerStatus,
                ], $taker->id);

                // Update maker order
                $maker = $this->orderRepository->update([
                    'remaining_amount'  => $newMakerRemaining,
                    'filled_amount'     => (float) $maker->filled_amount + $fill,
                    'status'            => $newMakerStatus,
                ], $maker->id);

                // Create a trade record
                $trade = $this->tradeRepository->create([
                    'buyer_user_id'     => $buyerOrder->user_id,
                    'seller_user_id'    => $sellerOrder->user_id,
                    'sell_order_id'     => $sellerOrder->id,
                    'buy_order_id'      => $buyerOrder->id,
                    'order_id'          => $orderId,
                    'base_coin_id'      => $baseCoinId,
                    'quote_coin_id'     => $quoteCoinId,
                    'amount'            => $fill,
                    'price'             => $price,
                    'total'             => $fill * $price,
                    'fee'               => $fee,
                ]);

                $filledTrades[] = $trade;
            }

            return $filledTrades;
        });
    }

    /**
     * @param float $amount
     * @param float $price
     * @return int
     */
    private function calculateFee(float $amount, float $price): int
    {
        if ($amount <= 1.0) {
            $percentage = 2.0;
        } elseif ($amount <= 10.0) {
            $percentage = 1.5;
        } else {
            $percentage = 1.0;
        }

        // Raw fee computation
        $rawFee = $amount * $price * ($percentage / 100);
        $fee = (int) round($rawFee);

        // Apply min/max caps
        if ($fee < self::MIN_FEE) {
            return self::MIN_FEE;
        }

        if ($fee > self::MAX_FEE) {
            return self::MAX_FEE;
        }

        return $fee;
    }
}
