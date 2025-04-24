<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($order) {
                return new OrderResource($order);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}