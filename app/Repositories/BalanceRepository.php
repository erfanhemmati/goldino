<?php

namespace App\Repositories;

use App\Models\Balance;
use Illuminate\Support\Facades\DB;
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
    public function lockFunds(int $userId, int $coinId, float $amount): int
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->update([
                'available_amount'  => DB::raw("available_amount - {$amount}"),
                'locked_amount'     => DB::raw("locked_amount + {$amount}"),
            ]);
    }

    /**
     * @inheritDoc
     */
    public function unlockFunds(int $userId, int $coinId, float $amount): int
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->update([
                'locked_amount'     => DB::raw("locked_amount - {$amount}"),
                'available_amount'  => DB::raw("available_amount + {$amount}"),
            ]);
    }

    /**
     * Permanently withdraw funds from the locked balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return int
     */
    public function withdrawLockedFunds(int $userId, int $coinId, float $amount): int
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->lockForUpdate()
            ->update([
                'total_amount'      => DB::raw("total_amount  - {$amount}"),
                'locked_amount'     => DB::raw("locked_amount - {$amount}")
            ]);
    }

    /**
     * Credit funds to the available balance when a trade executes.
     *
     * @param int $userId
     * @param int $coinId
     * @param float $amount
     * @return int
     */
    public function creditFunds(int $userId, int $coinId, float $amount): int
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('coin_id', $coinId)
            ->lockForUpdate()
            ->update([
                'total_amount'      => DB::raw("total_amount + {$amount}"),
                'available_amount'  => DB::raw("available_amount + {$amount}")
            ]);
    }
}
