<?php

namespace App\DataTransferObjects\Trade;

use Carbon\Carbon;
use App\Models\Trade;

final class TradeDto
{
    public function __construct(
        public int    $id,
        public int    $buyerUserId,
        public int    $sellerUserId,
        public int    $buyOrderId,
        public int    $sellOrderId,
        public int    $orderId,
        public int    $baseCoinId,
        public int    $quoteCoinId,
        public float  $amount,
        public float  $price,
        public float  $total,
        public float  $fee,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {}

    public static function fromModel(Trade $trade): self
    {
        return new self(
            id:              $trade->id,
            buyerUserId:     $trade->buyer_user_id,
            sellerUserId:    $trade->seller_user_id,
            buyOrderId:      $trade->buy_order_id,
            sellOrderId:     $trade->sell_order_id,
            orderId:         $trade->order_id,
            baseCoinId:      $trade->base_coin_id,
            quoteCoinId:     $trade->quote_coin_id,
            amount:          (float) $trade->amount,
            price:           (float) $trade->price,
            total:           (float) $trade->total,
            fee:             (float) $trade->fee,
            createdAt:       Carbon::parse($trade->created_at),
            updatedAt:       Carbon::parse($trade->updated_at),
        );
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'buyer_user_id'   => $this->buyerUserId,
            'seller_user_id'  => $this->sellerUserId,
            'buy_order_id'    => $this->buyOrderId,
            'sell_order_id'   => $this->sellOrderId,
            'order_id'        => $this->orderId,
            'base_coin_id'    => $this->baseCoinId,
            'quote_coin_id'   => $this->quoteCoinId,
            'amount'          => $this->amount,
            'price'           => $this->price,
            'total'           => $this->total,
            'fee'             => $this->fee,
            'created_at'      => $this->createdAt,
            'updated_at'      => $this->updatedAt,
        ];
    }
} 