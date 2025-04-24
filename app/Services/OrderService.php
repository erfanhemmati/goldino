<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Interfaces\OrderServiceInterface;
use App\Exceptions\OrderCannotBeCancelledException;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    /**
     * @var OrderRepositoryInterface        $orderRepository
     */
    protected OrderRepositoryInterface      $orderRepository;

    /**
     * @param OrderRepositoryInterface      $orderRepository
     */
    public function __construct
    (
        OrderRepositoryInterface            $orderRepository
    ) {
        $this->orderRepository            = $orderRepository;
    }

    /**
     * @inheritDoc
     */
    public function getUserOrders(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getUserOrders($userId, $filters, $perPage);
    }

    /**
     * @inheritDoc
     */
    public function createOrder(array $data): Order
    {
        $userId         = $data['user_id'];
        $baseCoinId     = $data['base_coin_id'];
        $quoteCoinId    = $data['quote_coin_id'];

        /**
         * @var Order $order
         */
        $order = $this->orderRepository->create([
            'user_id'           => $userId,
            'base_coin_id'      => $baseCoinId,
            'quote_coin_id'     => $quoteCoinId,
            'type'              => $data['type'],
            'amount'            => $data['amount'],
            'remaining_amount'  => $data['amount'],
            'price'             => $data['price'] * 10,
            'total'             => ($data['amount'] * $data['price']) * 10,
            'status'            => OrderStatus::OPEN->value,
        ]);

        return $order;
    }

    /**
     * @inheritDoc
     */
    public function cancelOrder(int $orderId, int $userId): Order
    {
        $order = $this->orderRepository->findUserCancelableOrderWithLock($userId);

        if (! $order) {
            throw new OrderCannotBeCancelledException('Order can not be cancelled');
        }

        /**
         * @var Order $order
         */
        $order = $this->orderRepository->update([
            'status' => OrderStatus::CANCELED->value,
        ], $orderId);

        return $order;
    }

    /**
     * @inheritDoc
     */
    public function findByIdWithLock(int $orderId): ?Order
    {
        return $this->orderRepository->findByIdWithLock($orderId);
    }
}
