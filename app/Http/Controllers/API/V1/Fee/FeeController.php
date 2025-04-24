<?php

namespace App\Http\Controllers\API\V1\Fee;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Fee\CalculateFeeRequest;
use App\Http\Requests\API\V1\Fee\CalculateUserFeeRequest;
use App\Http\Requests\API\V1\Fee\GetFeePercentageRequest;
use App\UseCases\Fee\CalculateFeeUseCase;
use App\UseCases\Fee\CalculateUserSpecificFeeUseCase;
use App\UseCases\Fee\GetFeePercentageUseCase;
use Illuminate\Http\JsonResponse;

class FeeController extends Controller
{
    /**
     * Calculate fee for a trade
     *
     * @param CalculateFeeRequest $request
     * @return JsonResponse
     */
    public function calculate(CalculateFeeRequest $request, CalculateFeeUseCase $useCase): JsonResponse
    {
        $fee = $useCase->execute([
            'amount' => $request->validated('amount'),
            'price' => $request->validated('price')
        ]);
        
        return response()->json(['data' => ['fee' => $fee]]);
    }
    
    /**
     * Calculate user-specific fee
     *
     * @param CalculateUserFeeRequest $request
     * @return JsonResponse
     */
    public function calculateUserFee(CalculateUserFeeRequest $request, CalculateUserSpecificFeeUseCase $useCase): JsonResponse
    {
        $fee = $useCase->execute([
            'user_id' => auth()->id(),
            'amount' => $request->validated('amount'),
            'is_maker' => $request->validated('is_maker', false)
        ]);
        
        return response()->json(['data' => ['fee' => $fee]]);
    }
    
    /**
     * Get fee percentage for an amount
     *
     * @param GetFeePercentageRequest $request
     * @return JsonResponse
     */
    public function getPercentage(GetFeePercentageRequest $request, GetFeePercentageUseCase $useCase): JsonResponse
    {
        $percentage = $useCase->execute([
            'amount' => $request->validated('amount')
        ]);
        
        return response()->json(['data' => ['percentage' => $percentage]]);
    }
} 