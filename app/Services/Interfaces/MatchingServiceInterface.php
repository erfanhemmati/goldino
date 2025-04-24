<?php

namespace App\Services\Interfaces;

interface MatchingServiceInterface
{
    /**
     * Process the given order ID: perform matching, update balances/orders, and create trade records.
     *
     * @param int $orderId
     * @return array of \App\Models\Trade
     */
    public function processOrder(int $orderId): array;
}
