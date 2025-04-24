<?php

namespace App\Repositories\Interfaces;

use App\Models\Coin;

interface CoinRepositoryInterface extends RepositoryInterface
{
    /**
     * Find coin by symbol.
     * 
     * @param string $symbol
     * @return Coin|null
     */
    public function findBySymbol(string $symbol): ?Coin;
    
    /**
     * Get active coins.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCoins();
} 