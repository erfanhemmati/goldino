<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(\App\Repositories\Interfaces\UserRepositoryInterface::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\CoinRepositoryInterface::class, \App\Repositories\CoinRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\OrderRepositoryInterface::class, \App\Repositories\OrderRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\BalanceRepositoryInterface::class, \App\Repositories\BalanceRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\TradeRepositoryInterface::class, \App\Repositories\TradeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\FeeRepositoryInterface::class, \App\Repositories\FeeRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\MatchingRepositoryInterface::class, \App\Repositories\MatchingRepository::class);
        
        // Bind services
        $this->app->bind(\App\Services\Interfaces\AuthServiceInterface::class, \App\Services\AuthService::class);
        $this->app->bind(\App\Services\Interfaces\BalanceServiceInterface::class, \App\Services\BalanceService::class);
        $this->app->bind(\App\Services\Interfaces\OrderServiceInterface::class, \App\Services\OrderService::class);
        $this->app->bind(\App\Services\Interfaces\TradeServiceInterface::class, \App\Services\TradeService::class);
        $this->app->bind(\App\Services\Interfaces\FeeCalculatorServiceInterface::class, \App\Services\FeeCalculatorService::class);
        $this->app->bind(\App\Services\Interfaces\MatchingServiceInterface::class, \App\Services\MatchingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
