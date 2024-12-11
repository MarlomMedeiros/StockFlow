<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\StockMovementPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        StockMovement::class => StockMovementPolicy::class,
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
