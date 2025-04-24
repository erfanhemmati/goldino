<?php

namespace App\Http\Controllers\API\V1\Order;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\UseCases\Order\CancelOrderUseCase;
use App\UseCases\Order\CreateOrderUseCase;
use App\UseCases\Order\GetUserOrdersUseCase;
use App\Http\Requests\API\V1\Order\CreateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     *
     * @param Request $request
     * @param GetUserOrdersUseCase $useCase
     * @return JsonResponse
     */
    public function index(Request $request, GetUserOrdersUseCase $useCase): JsonResponse
    {
        $orders = $useCase->execute([
            'user_id'       => auth()->id(),
            'per_page'      => min($request->input('per_page', 15), 15)
        ]);

        return $this->respondSuccess($orders);
    }

    /**
     * Store a newly created order (buy/sell)
     *
     * @param CreateOrderRequest $request
     * @param CreateOrderUseCase $useCase
     * @return JsonResponse
     */
    public function store(CreateOrderRequest $request, CreateOrderUseCase $useCase): JsonResponse
    {
        $order = $useCase->execute([
            'user_id'       => auth()->id(),
            'base_coin_id'  => $request->validated('base_coin_id'),
            'quote_coin_id' => $request->validated('quote_coin_id'),
            'type'          => $request->validated('type'),
            'amount'        => $request->validated('amount'),
            'price'         => $request->validated('price')
        ]);

        return $this->respondCreated($order->toArray(), 'Order created successfully');
    }

    /**
     * Cancel the specified order
     *
     * @param int $id
     * @param CancelOrderUseCase $useCase
     * @return JsonResponse
     */
    public function cancel(int $id, CancelOrderUseCase $useCase): JsonResponse
    {
        $order = $useCase->execute([
            'order_id'  => $id,
            'user_id'   => auth()->id()
        ]);

        return $this->respondSuccess($order, 'Order cancelled successfully');
    }
}
