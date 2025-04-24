<?php

namespace App\UseCases\Order;

final class CancelOrderCommand
{
    public function __construct(
        public int $orderId,
        public int $userId,
    ) {}
}
