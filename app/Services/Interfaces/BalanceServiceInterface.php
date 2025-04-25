<?php

namespace App\Services\Interfaces;

use App\Models\Balance;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\InsufficientBalanceException;

interface BalanceServiceInterface
{
    /**
     * Get user balances.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserBalances(int $userId): Collection;

    /**
     * Get user balance for a specific coin.
     *
     * @param int $userId
     * @param int $coinId
     * @return Balance|null
     */
    public function getUserBalance(int $userId, int $coinId): ?Balance;

    /**
     * Lock funds for an order.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return void
     * @throws InsufficientBalanceException
     */
    public function lockFunds(int $userId, int $coinId, float $amount): void;

    /**
     * Unlock funds for an order.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return void
     * @throws InsufficientBalanceException
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): void;

    /**
     * Transfer funds between users after trade executed.
     *
     * @param int $buyerUserId
     * @param int $sellerUserId
     * @param int $baseCoinId
     * @param int $quoteCoinId
     * @param float $amount
     * @param float $total
     * @param float $buyerFee
     * @param float $sellerFee
     * @return void
     */
    public function transferFunds(int $buyerUserId, int $sellerUserId, int $baseCoinId, int $quoteCoinId, float $amount, float $total, float $buyerFee, float $sellerFee): void;
}
