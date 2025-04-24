<?php

namespace App\Repositories\Interfaces;

use App\Models\Balance;
use Illuminate\Database\Eloquent\Collection;

interface BalanceRepositoryInterface extends RepositoryInterface
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
     * Get user balance for a specific coin with locked.
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
     */
    public function lockFunds(int $userId, int $coinId, float $amount): Balance;

    /**
     * Unlock funds for an order.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): Balance;

    /**
     * Permanently withdraw funds from the locked balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     */
    public function withdrawLockedFunds(int $userId, int $coinId, float $amount): Balance;

    /**
     * Credit funds to the available balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     */
    public function creditFunds(int $userId, int $coinId, float $amount): Balance;
}
