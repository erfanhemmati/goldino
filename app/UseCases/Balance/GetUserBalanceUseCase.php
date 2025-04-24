<?php

namespace App\UseCases\Balance;

use App\Models\Balance;
use App\UseCases\BaseUseCase;
use App\Services\Interfaces\BalanceServiceInterface;

class GetUserBalanceUseCase extends BaseUseCase
{
    /**
     * @var BalanceServiceInterface     $balanceService
     */
    private BalanceServiceInterface     $balanceService;

    /**
     * @param BalanceServiceInterface   $balanceService
     */
    public function __construct
    (
        BalanceServiceInterface         $balanceService
    )
    {
        $this->balanceService         = $balanceService;
    }

    /**
     * Get a user's balance for a specific coin
     *
     * @param array $data Must contain 'user_id' and 'coin_id' keys
     * @return Balance|null User balance for the specified coin
     */
    public function execute(array $data): ?Balance
    {
        return $this->balanceService->getUserBalance(
            $data['user_id'],
            $data['coin_id']
        );
    }
}
