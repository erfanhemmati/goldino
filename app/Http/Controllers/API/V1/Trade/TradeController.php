<?php

namespace App\Http\Controllers\API\V1\Trade;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\UseCases\Trade\GetUserTradesUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    use ApiResponseTrait;
    
    /**
     * Display a listing of the user's trades
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request, GetUserTradesUseCase $useCase): JsonResponse
    {
        $trades = $useCase->execute([
            'user_id' => auth()->id(),
            'per_page' => $request->input('per_page', 15)
        ]);
        
        return $this->respondSuccess($trades);
    }
} 