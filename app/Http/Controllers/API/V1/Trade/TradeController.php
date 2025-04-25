<?php

namespace App\Http\Controllers\API\V1\Trade;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\UseCases\Trade\GetUserTradesUseCase;

class TradeController extends Controller
{
    /**
     * Display a listing of the user's trades
     *
     * @param Request $request
     * @param GetUserTradesUseCase $useCase
     * @return JsonResponse
     */
    public function index(Request $request, GetUserTradesUseCase $useCase): JsonResponse
    {
        $trades = $useCase->execute([
            'user_id'   => auth()->id(),
            'per_page'  => min($request->input('per_page', 15), 15)
        ]);

        return $this->respondSuccess($trades);
    }
}
