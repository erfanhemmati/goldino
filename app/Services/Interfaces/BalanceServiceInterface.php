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
     * Get user balance for a specific coin with lock.
     *
     * @param int $userId
     * @param int $coinId
     * @return Balance|null
     */
    public function getUserBalanceWithLock(int $userId, int $coinId): ?Balance;

    /**
     * Lock funds for an order.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     * @throws InsufficientBalanceException
     */
    public function lockFunds(int $userId, int $coinId, float $amount): Balance;

    /**
     * Unlock funds for an order.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     * @throws InsufficientBalanceException
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): Balance;
}
