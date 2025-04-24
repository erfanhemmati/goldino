<?php

namespace App\Repositories;

use App\Models\Balance;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\BalanceRepositoryInterface;

class BalanceRepository extends BaseRepository implements BalanceRepositoryInterface
{
    /**
     * BalanceRepository constructor.
     *
     * @param Balance $model
     */
    public function __construct(Balance $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUserBalances(int $userId): Collection
    {
        return $this->model->query()->with('coin')
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getUserBalance(int $userId, int $coinId): ?Balance
    {
        return $this->model->query()->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getUserBalanceWithLock(int $userId, int $coinId): ?Balance
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->lockForUpdate()
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function lockFunds(int $userId, int $coinId, float $amount): Balance
    {
        // Get the balance with locking
        $balance = $this->model->query()->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->lockForUpdate()
            ->firstOrFail();

        // Update the balance
        $balance->available_amount  -= $amount;
        $balance->locked_amount     += $amount;
        $balance->save();

        return $balance;
    }

    /**
     * @inheritDoc
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): Balance
    {
        // Get the balance with locking
        $balance = $this->model->query()->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->lockForUpdate()
            ->firstOrFail();

        // Update the balance
        $balance->locked_amount     -= $amount;
        $balance->available_amount  += $amount;
        $balance->save();

        return $balance;
    }
}
