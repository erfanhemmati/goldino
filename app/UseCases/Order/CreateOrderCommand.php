<?php

namespace App\UseCases\Order;

final class CreateOrderCommand
{
    public function __construct(
        public int    $userId,
        public string $type,
        public int    $baseCoinId,
        public int    $quoteCoinId,
        public float  $amount,
        public float  $price,
    ) {}
}
