<?php

namespace App\DataTransferObjects\Order;

use Carbon\Carbon;
use App\Models\Order;

final class OrderDto
{
    public function __construct
    (
        public int                  $id,
        public int                  $userId,
        public int                  $baseCoinId,
        public int                  $quoteCoinId,
        public string               $type,
        public float                $amount,
        public float                $remainingAmount,
        public float                $filledAmount,
        public float                $price,
        public float                $total,
        public string               $status,
        public Carbon               $createdAt,
        public Carbon               $updatedAt,
    ) {}

    public static function fromModel(Order $order): self
    {
        return new self
        (
            id:                     $order->id,
            userId:                 $order->user_id,
            baseCoinId:             $order->base_coin_id,
            quoteCoinId:            $order->quote_coin_id,
            type:                   $order->type,
            amount:                 (float) $order->amount,
            remainingAmount:        (float) $order->remaining_amount,
            filledAmount:           (float) $order->filled_amount,
            price:                  (float) $order->price,
            total:                  (float) $order->total,
            status:                 $order->status,
            createdAt:              Carbon::parse($order->created_at),
            updatedAt:              Carbon::parse($order->updated_at),
        );
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'user_id'            => $this->userId,
            'base_coin_id'       => $this->baseCoinId,
            'quote_coin_id'      => $this->quoteCoinId,
            'type'               => $this->type,
            'amount'             => $this->amount,
            'remaining_amount'   => $this->remainingAmount,
            'filled_amount'      => $this->filledAmount,
            'price'              => $this->price,
            'total'              => $this->total,
            'status'             => $this->status,
            'created_at'         => $this->createdAt,
            'updated_at'         => $this->updatedAt,
        ];
    }
}
