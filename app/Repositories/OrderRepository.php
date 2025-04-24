<?php

namespace App\Repositories;

use App\Enums\OrderType;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUserOrders(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function findUserCancelableOrderWithLock(int $userId): ?Order
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('status', OrderStatus::OPEN->value)
            ->lockForUpdate()
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function findByIdWithLock(int $orderId): ?Order
    {
        return $this->model->newQuery()
            ->where('id', $orderId)
            ->lockForUpdate()
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function findBuyMatchingOrders(int $baseCoinId, int $quoteCoinId, float $price, int $excludeUserId): Collection
    {
        return $this->model->query()
            ->where('status', OrderStatus::OPEN->value)
            ->where('base_coin_id', $baseCoinId)
            ->where('quote_coin_id', $quoteCoinId)
            ->where('user_id', '!=', $excludeUserId)
            ->where('remaining_amount', '>', 0)
            ->where('type', OrderType::BUY->value)
            ->where('price', '<=', $price)
            ->orderBy('price')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findSellMatchingOrders(int $baseCoinId, int $quoteCoinId, float $price, int $excludeUserId): Collection
    {
        return $this->model->query()
            ->where('status', Order::STATUS_OPEN)
            ->where('base_coin_id', $baseCoinId)
            ->where('quote_coin_id', $quoteCoinId)
            ->where('user_id', '!=', $excludeUserId)
            ->where('remaining_amount', '>', 0)
            ->where('type', OrderType::SELL->value)
            ->where('price', '>=', $price)
            ->orderByDesc('price')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();
    }
}
