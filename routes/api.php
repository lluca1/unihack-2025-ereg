<?php

use App\Http\Controllers\Api\ExpositionController;
use Illuminate\Support\Facades\Route;

Route::get('/expositions/{exposition}', [ExpositionController::class, 'show'])
    ->name('api.expositions.show');
