<?php

namespace App\Repositories;

use App\Models\Trade;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\TradeRepositoryInterface;

class TradeRepository extends BaseRepository implements TradeRepositoryInterface
{
    /**
     * TradeRepository constructor.
     *
     * @param Trade $model
     */
    public function __construct(Trade $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUserTrades(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->where(function ($q) use ($userId) {
                $q->where('buyer_user_id', $userId)
                    ->orWhere('seller_user_id', $userId);
        });

        $query->orderByDesc('created_at');

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getUserOrderTrades(int $userId, int $orderId, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->where(function ($q) use ($userId) {
                $q->where('buyer_user_id', $userId)
                    ->orWhere('seller_user_id', $userId);
            })
            ->where(function ($q) use ($orderId) {
                $q->where('order_id', $orderId)
                    ->orWhere('buy_order_id', $orderId)
                    ->orWhere('sell_order_id', $orderId);
            });

        $query->orderByDesc('created_at');

        return $query->paginate($perPage);
    }
}
