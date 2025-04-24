<?php

namespace App\UseCases\Order;

use App\DataTransferObjects\Trade\TradeDto;
use App\Services\Interfaces\MatchingServiceInterface;

class ProcessOrderMatchingUseCase
{
    private MatchingServiceInterface $matchingService;

    public function __construct(MatchingServiceInterface $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Execute the matching process for the given order and return generated trades.
     *
     * @param int $orderId
     * @return TradeDto[]
     */
    public function execute(int $orderId): array
    {
        $trades = $this->matchingService->processOrder($orderId);

        return array_map(fn($trade) => TradeDto::fromModel($trade), $trades);
    }
}
