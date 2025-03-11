<?php

require __DIR__.'/auth.php';
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TrainingController;

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('main.dashboard');
    Route::get('/calendar/{year?}', function() { return view('calendar-wrapper'); })->middleware(['strava.token'])->name('calendar.index');
    Route::get('/test', function() { return view('test'); });

    Route::get('/trainings/routine', [TrainingController::class, 'createRoutine'])->name('trainings.create-routine');
    Route::post('/trainings/routine', [TrainingController::class, 'storeRoutine'])->name('trainings.store-routine');
    Route::delete('/trainings/destroy-all', [TrainingController::class, 'destroyAll'])->name('trainings.destroy-all');
    Route::resource('trainings', TrainingController::class)->except(['index']);
    Route::get('/trainings/create/{date}', [TrainingController::class, 'create'])->name('trainings.create'); 
    Route::patch('/trainings/{training}/update-date', [TrainingController::class, 'updateDate'])->name('trainings.update-date');   

    Route::get('/strava/connect', [StravaController::class, 'showConnect'])->name('strava.connect');
    Route::get('/strava/sync', [StravaController::class, 'sync'])->name('strava.sync');
    Route::get('strava/redirect', [StravaController::class, 'redirect'])->name('strava.redirect');
    Route::get('strava/callback', [StravaController::class, 'handleCallback']);

    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::delete('activities', [ActivityController::class, 'destroyAll'])->name('activities.destroy-all');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

