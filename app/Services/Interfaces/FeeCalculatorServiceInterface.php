<?php

namespace App\Services\Interfaces;

interface FeeCalculatorServiceInterface
{
    /**
     * Calculate the fee for a given trade.
     *
     * @param float $amount The trade amount in base currency.
     * @param float $price The trade price (quote currency per base unit).
     * @param string $type The order type, matching OrderType enum.
     * @return float The calculated fee.
     */
    public function calculateFee(float $amount, float $price, string $type): float;
}
