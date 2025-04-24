<?php

namespace App\Http\Controllers\API\V1\Balance;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\UseCases\Balance\GetUserBalanceUseCase;
use App\UseCases\Balance\GetUserBalancesUseCase;

class BalanceController extends Controller
{
    /**
     * Get all balances for the authenticated user
     *
     * @param GetUserBalancesUseCase $useCase
     * @return JsonResponse
     */
    public function index(GetUserBalancesUseCase $useCase): JsonResponse
    {
        $balances = $useCase->execute([
            'user_id' => auth()->id()
        ]);

        return $this->respondSuccess($balances);
    }

    /**
     * Get balance for a specific coin
     *
     * @param int $coinId
     * @param GetUserBalanceUseCase $useCase
     * @return JsonResponse
     */
    public function show(int $coinId, GetUserBalanceUseCase $useCase): JsonResponse
    {
        $balance = $useCase->execute([
            'user_id' => auth()->id(),
            'coin_id' => $coinId
        ]);

        if (! $balance) {
            return $this->respondNotFound('Balance not found');
        }

        return $this->respondSuccess($balance);
    }
}
