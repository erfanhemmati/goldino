<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Interfaces\TradeServiceInterface;
use App\Repositories\Interfaces\TradeRepositoryInterface;

class TradeService implements TradeServiceInterface
{
    /**
     * @var TradeRepositoryInterface    $tradeRepository
     */
    protected TradeRepositoryInterface  $tradeRepository;

    /**
     * @param TradeRepositoryInterface  $tradeRepository
     */
    public function __construct
    (
        TradeRepositoryInterface        $tradeRepository
    )
    {
        $this->tradeRepository        = $tradeRepository;
    }

    /**
     * @inheritDoc
     */
    public function getUserTrades(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->tradeRepository->getUserTrades($userId, $filters, $perPage);
    }

    /**
     * @inheritDoc
     */
    public function getUserOrderTrades(int $userId, int $orderId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->tradeRepository->getUserOrderTrades($userId, $orderId, $perPage);
    }
}
