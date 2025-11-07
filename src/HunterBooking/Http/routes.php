<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\HunterBooking\Http\Controllers\BookHuntingTourController;
use Src\HunterBooking\Http\Controllers\GetAllGuidesController;

Route::middleware(['api'])->prefix('/api')->name('api.')->group(static function (): void {
    Route::get('/guides', GetAllGuidesController::class)->name('guides.list');
    Route::post('/bookings', BookHuntingTourController::class)->name('bookings.book');
});
