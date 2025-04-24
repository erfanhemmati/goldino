<?php

namespace App\Services\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\OrderCannotBeCancelledException;

interface OrderServiceInterface
{
    /**
     * Get user's orders with pagination
     *
     * @param int $userId User ID
     * @param array $filters Optional filters
     * @param int $perPage Items per page
     * @return LengthAwarePaginator Paginated orders
     */
    public function getUserOrders(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new order
     *
     * @param array $data Order data
     * @return Order The created order
     */
    public function createOrder(array $data): Order;

    /**
     * Cancel an order
     *
     * @param int $orderId Order ID
     * @param int $userId User ID (for validation)
     * @return Order Updated order
     * @throws OrderCannotBeCancelledException
     */
    public function cancelOrder(int $orderId, int $userId): Order;

    /**
     * Find an order by ID with FOR UPDATE lock.
     *
     * @param int $orderId
     * @return Order|null
     */
    public function findByIdWithLock(int $orderId): ?Order;
}
