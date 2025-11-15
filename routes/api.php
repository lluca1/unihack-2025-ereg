<?php

use App\Http\Controllers\Api\ExpositionController;
use Illuminate\Support\Facades\Route;

Route::get('/expositions/{exposition}', [ExpositionController::class, 'show'])
    ->name('api.expositions.show');

// Legacy endpoint kept for the 3D client references inside the UI copy.
Route::get('/expo/{exposition}', [ExpositionController::class, 'show']);
