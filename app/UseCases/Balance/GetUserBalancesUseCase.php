<?php

namespace App\UseCases\Balance;

use App\UseCases\BaseUseCase;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Interfaces\BalanceServiceInterface;

class GetUserBalancesUseCase extends BaseUseCase
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
     * Get all balances for a user
     *
     * @param array $data Must contain 'user_id' key
     * @return Collection User balances
     */
    public function execute(array $data): Collection
    {
        return $this->balanceService->getUserBalances($data['user_id']);
    }
}
