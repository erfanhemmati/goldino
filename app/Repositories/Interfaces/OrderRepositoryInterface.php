<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Find user orders with filtering.
     *
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserOrders(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find user specific cancelable order.
     *
     * @param int $userId
     * @return Order|null
     */
    public function findUserCancelableOrderWithLock(int $userId): ?Order;

    /**
     * Find an order by ID and apply FOR UPDATE lock.
     *
     * @param int $orderId
     * @return Order|null
     */
    public function findByIdWithLock(int $orderId): ?Order;

    /**
     * Find a matchable buy orders for selected sell order.
     *
     * @param int $baseCoinId
     * @param int $quoteCoinId
     * @param float $price
     * @param int $excludeUserId
     * @return Collection
     */
    public function findBuyMatchingOrders(int $baseCoinId, int $quoteCoinId, float $price, int $excludeUserId): Collection;

    /**
     * Find a matchable sell orders for selected buy order.
     *
     * @param int $baseCoinId
     * @param int $quoteCoinId
     * @param float $price
     * @param int $excludeUserId
     * @return Collection
     */
    public function findSellMatchingOrders(int $baseCoinId, int $quoteCoinId, float $price, int $excludeUserId): Collection;
}
