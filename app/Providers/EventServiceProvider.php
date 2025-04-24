<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TradeExecuted;
use App\Listeners\SettleTradeFunds;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TradeExecuted::class => [
            SettleTradeFunds::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
} 