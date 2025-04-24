<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'base_coin_id' => $this->base_coin_id,
            'quote_coin_id' => $this->quote_coin_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'remaining_amount' => $this->remaining_amount,
            'price' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Add relationships if needed
            // 'base_coin' => new CoinResource($this->whenLoaded('baseCoin')),
            // 'quote_coin' => new CoinResource($this->whenLoaded('quoteCoin')),
        ];
    }
}