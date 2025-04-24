<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Balance\BalanceController;
use App\Http\Controllers\API\V1\Order\OrderController;
use App\Http\Controllers\API\V1\Trade\TradeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    $service = app()->make(\App\Services\Interfaces\MatchingServiceInterface::class);

    dd($service->processOrder(3));
});

// API V1 Routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register',                [AuthController::class, 'register']);
    Route::post('/login',                   [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout',              [AuthController::class, 'logout']);

        // Balances
        Route::get('/balances',             [BalanceController::class, 'index']);
        Route::get('/balances/{coinId}',    [BalanceController::class, 'show']);

        // Orders
        Route::get('/orders',               [OrderController::class, 'index']);
        Route::post('/orders',              [OrderController::class, 'store']);
        Route::post('/orders/{id}/cancel',  [OrderController::class, 'cancel']);

        // Trades
        Route::get('/trades',               [TradeController::class, 'index']);
    });
});
