<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Auth use cases
        $this->app->bind(\App\UseCases\Auth\RegisterUseCase::class);
        $this->app->bind(\App\UseCases\Auth\LoginUseCase::class);
        $this->app->bind(\App\UseCases\Auth\LogoutUseCase::class);
        
        // Balance use cases
        $this->app->bind(\App\UseCases\Balance\GetUserBalancesUseCase::class);
        $this->app->bind(\App\UseCases\Balance\GetUserBalanceUseCase::class);
        
        // Order use cases
        $this->app->bind(\App\UseCases\Order\CreateOrderUseCase::class);
        $this->app->bind(\App\UseCases\Order\CancelOrderUseCase::class);
        $this->app->bind(\App\UseCases\Order\GetUserOrdersUseCase::class);
        
        // Trade use cases
        $this->app->bind(\App\UseCases\Trade\GetUserTradesUseCase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 