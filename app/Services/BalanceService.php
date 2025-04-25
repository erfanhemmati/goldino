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
    public function lockFunds(int $userId, int $coinId, float $amount): void
    {
        $this->cache->forget($this->getUserBalancesCacheKey($userId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

        $balance = $this->balanceRepository->getUserBalanceWithLock($userId, $coinId);

        if (! $balance || $balance->available_amount < $amount) {
            throw new InsufficientBalanceException('Insufficient balance');
        }

        // lock balances
        $this->balanceRepository->lockFunds($userId, $coinId, $amount);
    }

    /**
     * @inheritDoc
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): void
    {
        $this->cache->forget($this->getUserBalancesCacheKey($userId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($userId, $coinId));

        $balance = $this->balanceRepository->getUserBalanceWithLock($userId, $coinId);

        if (! $balance || $balance->locked_amount < $amount) {
            throw new InsufficientBalanceException('Insufficient locked funds');
        }

        $this->balanceRepository->unlockFunds($userId, $coinId, $amount);
    }

    /**
     * @inheritDoc
     */
    public function transferFunds(int $buyerUserId, int $sellerUserId, int $baseCoinId, int $quoteCoinId, float $amount, float $total, float $buyerFee, float $sellerFee): void
    {
        $this->cache->forget($this->getUserBalancesCacheKey($buyerUserId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($buyerUserId, $baseCoinId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($buyerUserId, $quoteCoinId));

        $this->cache->forget($this->getUserBalancesCacheKey($sellerUserId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($sellerUserId, $baseCoinId));
        $this->cache->forget($this->getUserCoinBalanceCacheKey($sellerUserId, $quoteCoinId));

        DB::transaction(function () use ($buyerUserId, $sellerUserId, $baseCoinId, $quoteCoinId, $amount, $total, $buyerFee, $sellerFee) {

            // buyer
            $this->balanceRepository->withdrawLockedFunds($buyerUserId, $quoteCoinId, bcmul($total, 1, 8));
            $this->balanceRepository->creditFunds($buyerUserId, $baseCoinId, bcsub($amount, $buyerFee, 8));

            // seller
            $this->balanceRepository->withdrawLockedFunds($sellerUserId, $baseCoinId, bcmul($amount, 1, 8));
            $this->balanceRepository->creditFunds($sellerUserId, $quoteCoinId, bcsub($total, $sellerFee, 8));

        });
    }
}
