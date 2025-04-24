<?php

namespace App\UseCases\Trade;

use App\Services\Interfaces\TradeServiceInterface;
use App\UseCases\BaseUseCase;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserTradesUseCase extends BaseUseCase
{
    private TradeServiceInterface $tradeService;

    public function __construct(TradeServiceInterface $tradeService)
    {
        $this->tradeService = $tradeService;
    }

    /**
     * Get trades for a user
     * 
     * @param array $data Must contain 'user_id' key, may contain 'per_page'
     * @return LengthAwarePaginator Paginated trades
     */
    public function execute(array $data): LengthAwarePaginator
    {
        $perPage = $data['per_page'] ?? 15;

        return $this->tradeService->getUserTrades(
            $data['user_id'],
            [],
            $perPage
        );
    }
} 