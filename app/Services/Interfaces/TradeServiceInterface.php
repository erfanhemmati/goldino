<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface TradeServiceInterface
{
    /**
     * Get user's trades with pagination
     *
     * @param int $userId User ID
     * @param array $filters Optional filters
     * @param int $perPage Items per page
     * @return LengthAwarePaginator Paginated trades
     */
    public function getUserTrades(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get trades for a specific order
     *
     * @param int $userId User ID
     * @param int $orderId Order ID
     * @param int $perPage Items per page
     * @return LengthAwarePaginator Paginated trades
     */
    public function getUserOrderTrades(int $userId, int $orderId, int $perPage = 15): LengthAwarePaginator;
}
