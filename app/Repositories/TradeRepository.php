<?php

namespace App\Repositories;

use App\Models\Trade;
use App\Repositories\Interfaces\TradeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $query = $this->model->where(function ($q) use ($userId) {
            $q->where('buyer_user_id', $userId)
              ->orWhere('seller_user_id', $userId);
        });
        
        // Order by created_at desc
        $query->orderBy('created_at', 'desc');
        
        return $query->paginate($perPage);
    }
    
    /**
     * @inheritDoc
     */
    public function getOrderTrades(int $orderId, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->where('order_id', $orderId)
            ->orWhere('buy_order_id', $orderId)
            ->orWhere('sell_order_id', $orderId);
            
        $query->orderBy('created_at', 'desc');
        
        return $query->paginate($perPage);
    }
} 