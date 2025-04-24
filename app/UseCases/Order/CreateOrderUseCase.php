<?php

namespace App\UseCases\Order;

use App\Enums\OrderType;
use App\UseCases\BaseUseCase;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Order\OrderDto;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\BalanceServiceInterface;
use App\Services\Interfaces\MatchingServiceInterface;

class CreateOrderUseCase extends BaseUseCase
{
    /**
     * @var OrderServiceInterface       $orderService
     */
    private OrderServiceInterface       $orderService;

    /**
     * @var BalanceServiceInterface     $balanceService
     */
    private BalanceServiceInterface     $balanceService;

    /**
     * @var MatchingServiceInterface    $matchingService
     */
    private MatchingServiceInterface    $matchingService;

    /**
     * @param OrderServiceInterface     $orderService
     * @param BalanceServiceInterface   $balanceService
     * @param MatchingServiceInterface  $matchingService
     */
    public function __construct
    (
        OrderServiceInterface           $orderService,
        BalanceServiceInterface         $balanceService,
        MatchingServiceInterface        $matchingService
    )
    {
        $this->orderService           = $orderService;
        $this->balanceService         = $balanceService;
        $this->matchingService        = $matchingService;
    }

    /**
     * Create a new order
     *
     * @param array $data
     * @return OrderDto
     */
    public function execute(array $data): OrderDto
    {
        $order = DB::transaction(function () use ($data) {

            $lockCoinId     = ($data['type'] == OrderType::BUY->value) ?
                $data['quote_coin_id'] :
                $data['base_coin_id'];

            $lockAmount     = ($data['type'] == OrderType::BUY->value) ?
                ($data['amount'] * $data['price']) * 10 :
                $data['amount'];

            // lock and check balance
            $this->balanceService->lockFunds(
                $data['user_id'],
                $lockCoinId,
                $lockAmount
            );

            // create order
            return $this->orderService->createOrder($data);
        });

        // perform matching after order creation
        $this->matchingService->processOrder($order->id);

        return OrderDto::fromModel($order);
    }
}
