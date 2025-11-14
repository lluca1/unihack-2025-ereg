<?php

use App\Livewire\ExpositionExhibits;
use App\Livewire\ExpositionsManager;
use App\Models\Exposition;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $expositions = Exposition::query()
        ->with('user')
        ->latest()
        ->take(6)
        ->get();

    return view('pages.home', compact('expositions'));
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    
Route::get('/expositions', ExpositionsManager::class)->name('expositions.index');
Route::get('/expositions/{exposition}', ExpositionExhibits::class)->name('expositions.show');

require __DIR__.'/auth.php';
