<?php

namespace App\UseCases\Order;

use App\Enums\OrderType;
use App\UseCases\BaseUseCase;
use Illuminate\Support\Facades\DB;
use App\DataTransferObjects\Order\OrderDto;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\BalanceServiceInterface;

class CancelOrderUseCase extends BaseUseCase
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
     * @param OrderServiceInterface     $orderService
     * @param BalanceServiceInterface   $balanceService
     */
    public function __construct
    (
        OrderServiceInterface           $orderService,
        BalanceServiceInterface         $balanceService
    )
    {
        $this->orderService           = $orderService;
        $this->balanceService         = $balanceService;
    }

    /**
     * Cancel an existing order
     *
     * @param array $data Must contain 'order_id' and 'user_id' keys
     * @return OrderDto
     */
    public function execute(array $data): OrderDto
    {
        $order = DB::transaction(function () use ($data) {

            // cancel the order
            $canceledOrder = OrderDto::fromModel(
                $this->orderService->cancelOrder($data['order_id'], $data['user_id'])
            );

            $unlockCoinId  = ($canceledOrder->type == OrderType::BUY->value) ?
                $canceledOrder->quoteCoinId :
                $canceledOrder->baseCoinId;

            $unlockAmount  = ($canceledOrder->type == OrderType::BUY->value) ?
                ($canceledOrder->remainingAmount * $canceledOrder->price) * 10 :
                $canceledOrder->remainingAmount;

            // unlock funds
            $this->balanceService->unlockFunds($canceledOrder->userId, $unlockCoinId, $unlockAmount);

            return $canceledOrder;
        });

        return $order;

        // TODO: matching process.
    }
}
