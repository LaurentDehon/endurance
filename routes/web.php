<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\StravaAuthController;

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('main.dashboard');

    Route::get('/calendar/{year?}', [CalendarController::class, 'index'])->name('calendar.yearly');
    Route::patch('/update-week-type/{week_id}', [CalendarController::class, 'updateWeekType'])->name('calendar.update-week-type');

    Route::get('/trainings/routine', [TrainingController::class, 'createRoutine'])->name('trainings.create-routine');
    Route::post('/trainings/routine', [TrainingController::class, 'storeRoutine'])->name('trainings.store-routine');
    Route::delete('/trainings/destroy-all', [TrainingController::class, 'destroyAll'])->name('trainings.destroy-all');
    Route::resource('trainings', TrainingController::class)->except(['index']);
    Route::get('/trainings/create/{date}', [TrainingController::class, 'create'])->name('trainings.create');

    Route::get('/strava/login', [StravaAuthController::class, 'redirectToStrava'])->name('strava.login');
    Route::get('/strava/callback', [StravaAuthController::class, 'handleCallback']);
    Route::get('/strava/success', [StravaAuthController::class, 'success']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

