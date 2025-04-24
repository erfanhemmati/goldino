<?php

namespace App\Services;

use App\Models\Balance;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\InsufficientBalanceException;
use App\Services\Interfaces\BalanceServiceInterface;
use App\Repositories\Interfaces\BalanceRepositoryInterface;

class BalanceService implements BalanceServiceInterface
{
    private const BALANCES_CACHE_TTL      = 60; // in seconds

    protected BalanceRepositoryInterface    $balanceRepository;

    protected Repository                    $cache;

    /**
     * @param BalanceRepositoryInterface    $balanceRepository
     * @param Repository                    $cache
     */
    public function __construct
    (
        BalanceRepositoryInterface          $balanceRepository,
        Repository                          $cache
    )
    {
        $this->balanceRepository          = $balanceRepository;
        $this->cache                      = $cache;
    }

    private function getUserBalancesCacheKey(int $userId): string
    {
        return "user_balances_{$userId}";
    }

    private function getUserCoinBalanceCacheKey(int $userId, int $coinId): string
    {
        return "user_balance_{$userId}_coin_{$coinId}";
    }

    /**
     * @inheritDoc
     */
    public function getUserBalances(int $userId): Collection
    {
        return $this->cache->remember($this->getUserBalancesCacheKey($userId), self::BALANCES_CACHE_TTL, function () use ($userId) {
            return $this->balanceRepository->getUserBalances($userId);
        });
    }

    /**
     * @inheritDoc
     */
    public function getUserBalance(int $userId, int $coinId): ?Balance
    {
        return $this->cache->remember($this->getUserCoinBalanceCacheKey($userId, $coinId), self::BALANCES_CACHE_TTL, function () use ($userId, $coinId) {
            return $this->balanceRepository->getUserBalance($userId, $coinId);
        });
    }

    /**
     * @inheritDoc
     */
    public function getUserBalanceWithLock(int $userId, int $coinId): ?Balance
    {
        return $this->balanceRepository->getUserBalanceWithLock($userId, $coinId);
    }

    /**
     * @inheritDoc
     */
    public function lockFunds(int $userId, int $coinId, float $amount): Balance
    {
        return DB::transaction(function () use ($userId, $coinId, $amount) {
            // First check if user has enough available funds
            $balance = $this->getUserBalance($userId, $coinId);
            // $balance = $this->getUserBalanceWithLock($userId, $coinId);

            if (! $balance || $balance->available_amount < $amount) {
                throw new InsufficientBalanceException('Insufficient balance');
            }

            // Clear cache
            $this->cache->forget($this->getUserBalancesCacheKey($userId));
            $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

            return $this->balanceRepository->lockFunds($userId, $coinId, $amount);
        });
    }

    /**
     * @inheritDoc
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): Balance
    {
        return DB::transaction(function () use ($userId, $coinId, $amount) {
            // First check if user has enough locked funds
            $balance = $this->getUserBalance($userId, $coinId);
            // $balance = $this->getUserBalanceWithLock($userId, $coinId);

            if (! $balance || $balance->locked_amount < $amount) {
                throw new InsufficientBalanceException('Insufficient locked funds');
            }

            // Clear cache
            $this->cache->forget($this->getUserBalancesCacheKey($userId));
            $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

            return $this->balanceRepository->unlockFunds($userId, $coinId, $amount);
        });
    }

    /**
     * Permanently withdraw funds from the locked balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     */
    public function withdrawLockedFunds(int $userId, int $coinId, float $amount): Balance
    {
        return DB::transaction(function () use ($userId, $coinId, $amount) {
            // clear relevant caches
            $this->cache->forget($this->getUserBalancesCacheKey($userId));
            $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

            return $this->balanceRepository->withdrawLockedFunds($userId, $coinId, $amount);
        });
    }

    /**
     * Credit funds to the available balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return Balance
     */
    public function creditFunds(int $userId, int $coinId, float $amount): Balance
    {
        return DB::transaction(function () use ($userId, $coinId, $amount) {
            // clear relevant caches
            $this->cache->forget($this->getUserBalancesCacheKey($userId));
            $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

            return $this->balanceRepository->creditFunds($userId, $coinId, $amount);
        });
    }
}
