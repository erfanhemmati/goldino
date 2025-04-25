<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Trade;
use App\Enums\OrderType;
use App\Events\TradeExecuted;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\MatchingServiceInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\TradeRepositoryInterface;
use App\Services\Interfaces\FeeCalculatorServiceInterface;

class MatchingService implements MatchingServiceInterface
{
    private OrderRepositoryInterface        $orderRepository;
    /**
     * @var TradeRepositoryInterface        $tradeRepository
     */
    private TradeRepositoryInterface        $tradeRepository;
    /**
     * @var FeeCalculatorServiceInterface   $feeCalculator
     */
    private FeeCalculatorServiceInterface   $feeCalculator;

    /**
     * @param OrderRepositoryInterface      $orderRepository
     * @param TradeRepositoryInterface      $tradeRepository
     * @param FeeCalculatorServiceInterface $feeCalculator
     */
    public function __construct
    (
        OrderRepositoryInterface            $orderRepository,
        TradeRepositoryInterface            $tradeRepository,
        FeeCalculatorServiceInterface       $feeCalculator,
    ) {
        $this->orderRepository            = $orderRepository;
        $this->tradeRepository            = $tradeRepository;
        $this->feeCalculator              = $feeCalculator;
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

                // Calculate fee for this trade via FeeCalculatorService
                $buyerFee  = $this->feeCalculator->calculateFee($fill, $price, $buyerOrder->type);
                $sellerFee = $this->feeCalculator->calculateFee($fill, $price, $sellerOrder->type);

                // Compute new status and remaining amounts
                $newTakerRemaining = $takerRemaining - $fill;
                $newMakerRemaining = $makerRemaining - $fill;
                $newTakerStatus    = $newTakerRemaining > 0 ? OrderStatus::OPEN->value : OrderStatus::COMPLETED->value;
                $newMakerStatus    = $newMakerRemaining > 0 ? OrderStatus::OPEN->value : OrderStatus::COMPLETED->value;

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
                    'buyer_fee'         => $buyerFee,
                    'seller_fee'        => $sellerFee,
                ]);

                /**
                 * @var Trade $trade
                 */
                TradeExecuted::dispatch($trade);

                $filledTrades[] = $trade;
            }

            return $filledTrades;
        });
    }
}
