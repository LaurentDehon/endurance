<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StravaMiddleware;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Main Application Routes
    // ----------------------
    // These routes represent the primary navigation elements of the application
    
    // Home page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Dashboard - The main user interface showing stats and upcoming workouts
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(StravaMiddleware::class)
        ->name('dashboard');
    
    // Calendar - Workout planning and visualization
    Route::get('/calendar/{year?}', function ($year = null) { 
        return view('calendar', ['year' => $year]); 
    })->middleware(StravaMiddleware::class)->name('calendar');
    Route::redirect('/calendar', '/calendar/'.now()->year);
    
    // Activities - Displays user activity history
    Route::get('/activities', function() { 
        return view('activities'); 
    })->middleware(StravaMiddleware::class)->name('activities');
    
    // Help - Documentation and user guide
    Route::get('/help', function() { 
        return view('help'); 
    })->name('help');
    
    // Strava Integration
    // -----------------
    // Routes for connecting and syncing with Strava API
    Route::prefix('strava')->group(function () {
        Route::get('/connect', [StravaController::class, 'showConnect'])->name('strava.connect');
        Route::get('/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
        Route::get('/callback', [StravaController::class, 'handleCallback']);
    });
    
    // User Profile Management
    // ----------------------
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    // Contact/Feedback System
    // ----------------------
    Route::prefix('contact')->group(function () {
        Route::get('/', [ContactController::class, 'show'])->name('contact.show');
        Route::post('/', [ContactController::class, 'send'])->name('contact.send');
    });
    
    // Admin Area
    // ---------
    // Protected by AdminMiddleware to ensure only admins can access
    Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {
        Route::get('/', function() { 
            return view('admin'); 
        })->name('admin');

        Route::get('/user/{userId}', function($userId) {
            return view('user-detail', ['userId' => $userId]);
        })->name('user.detail');
    });
});