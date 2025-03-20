<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StravaMiddleware;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function() { return view('home'); })->name('home');
    Route::get('/dashboard', [MainController::class, 'index'])->middleware(StravaMiddleware::class)->name('dashboard');
    Route::get('/calendar/{year?}', function ($year = null) { return view('calendar', ['year' => $year]); })->middleware(StravaMiddleware::class)->name('calendar');
    Route::redirect('/calendar', '/calendar/'.now()->year);
    Route::get('/activities', function() { return view('activities'); })->middleware(StravaMiddleware::class)->name('activities');
    Route::get('/help', function() { return view('help'); })->name('help');
    Route::get('/admin', function() { return view('admin'); })->middleware(AdminMiddleware::class)->name('admin');

    Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    Route::get('/strava/connect', [StravaController::class, 'showConnect'])->name('strava.connect');
    Route::get('strava/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
    Route::get('strava/callback', [StravaController::class, 'handleCallback']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});