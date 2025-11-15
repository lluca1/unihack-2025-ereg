<?php

use App\Models\Exposition;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->get('/expo/{exposition}', function (Exposition $exposition) {
    return $exposition->load('exhibits');
});
