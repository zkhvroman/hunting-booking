<?php

declare(strict_types=1);

namespace Src\HunterBooking;

use Illuminate\Support\ServiceProvider;
use Src\HunterBooking\Services\HuntingBookingManager;

final class HuntingBookingProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HuntingBookingManager::class, static fn(): HuntingBookingManager => new HuntingBookingManager());
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('src/HunterBooking/Http/routes.php'));
    }
}
