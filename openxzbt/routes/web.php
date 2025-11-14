<?php

use App\Livewire\ExpositionExhibits;
use App\Livewire\ExpositionsManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/expositions', ExpositionsManager::class)->name('expositions.index');
Route::get('/expositions/{exposition}', ExpositionExhibits::class)->name('expositions.show');
