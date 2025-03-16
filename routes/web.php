<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('dashboard');
    Route::get('/calendar/{year?}', function() { return view('calendar'); })->middleware(['strava.token'])->name('calendar');  
    Route::get('/activities', function() { return view('activities'); })->name('activities');
    Route::get('/help', function() { return view('help'); })->name('help');

    Route::get('/strava/connect', [StravaController::class, 'showConnect'])->name('strava.connect');
    Route::get('strava/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
    Route::get('strava/callback', [StravaController::class, 'handleCallback']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

