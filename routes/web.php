<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StravaMiddleware;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChangelogController;

// Public routes - accessible without authentication
// Home page - Landing page with marketing content
Route::get('/', [HomeController::class, 'index'])->name('home');

// Legal pages - Terms of Service and Privacy Policy
Route::get('/terms', function() {
    return view('footer.terms');
})->name('terms');

Route::get('/privacy', function() {
    return view('footer.privacy');
})->name('privacy');

// Help - Documentation and user guide
Route::get('/help', function() { 
    return view('help'); 
})->name('help');

// Changelog and Roadmap - Display recent updates and upcoming features
Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Main Application Routes
    // ----------------------
    // These routes represent the primary navigation elements of the application
    
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
    
    // Strava Integration
    // -----------------
    // Routes for connecting and syncing with Strava API
    Route::prefix('strava')->group(function () {
        Route::get('/connect', [StravaController::class, 'showConnect'])->name('strava.connect');
        Route::get('/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
        Route::get('/callback', [StravaController::class, 'handleCallback']);
        Route::get('/disconnect', [StravaController::class, 'disconnect'])->name('strava.disconnect');
    });
    
    // User Profile Management
    // ----------------------
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    // User Settings
    // ----------------------
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/update-timezone', [SettingsController::class, 'updateTimezone'])->name('settings.update-timezone');
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
            return view('admin.index'); 
        })->name('admin');
        
        Route::get('/users', function() {
            return view('admin.users');
        })->name('admin.users');
        
        Route::get('/visits', function() {
            return view('admin.visits');
        })->name('admin.visits');

        Route::get('/user/{userId}', function($userId) {
            return view('admin.user-detail', ['userId' => $userId]);
        })->name('user.detail');
    });
});