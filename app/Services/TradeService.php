<?php

namespace App\Services;

use App\Repositories\Interfaces\TradeRepositoryInterface;
use App\Services\Interfaces\TradeServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TradeService implements TradeServiceInterface
{
    protected TradeRepositoryInterface $tradeRepository;
    
    /**
     * Constructor
     * 
     * @param TradeRepositoryInterface $tradeRepository
     */
    public function __construct(TradeRepositoryInterface $tradeRepository)
    {
        $this->tradeRepository = $tradeRepository;
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
    public function getOrderTrades(int $orderId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->tradeRepository->getOrderTrades($orderId, $perPage);
    }
}
