<?php

namespace App\Repositories\Interfaces;

use App\Models\Trade;
use Illuminate\Pagination\LengthAwarePaginator;

interface TradeRepositoryInterface extends RepositoryInterface
{
    /**
     * Get user trades with pagination and filters.
     * 
     * @param int $userId
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUserTrades(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get trades for a specific order with pagination.
     * 
     * @param int $orderId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOrderTrades(int $orderId, int $perPage = 15): LengthAwarePaginator;
} 