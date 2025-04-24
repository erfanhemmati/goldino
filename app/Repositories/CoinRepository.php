<?php

namespace App\Repositories;

use App\Models\Coin;
use App\Repositories\Interfaces\CoinRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CoinRepository extends BaseRepository implements CoinRepositoryInterface
{
    /**
     * CoinRepository constructor.
     * 
     * @param Coin $model
     */
    public function __construct(Coin $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findBySymbol(string $symbol): ?Coin
    {
        return $this->model->where('symbol', $symbol)->first();
    }
    
    /**
     * @inheritDoc
     */
    public function getActiveCoins(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }
} 