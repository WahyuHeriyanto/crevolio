<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

//Guest
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('landing');
    })->name('landing');
});

Route::middleware(['auth', 'profile.completed.redirect'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])
        ->name('onboarding.index');

    Route::post('/onboarding/store', [OnboardingController::class, 'store'])
        ->name('onboarding.store');
});

Route::middleware(['auth', 'profile.completed'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/projects/create', [ProjectController::class, 'create'])
        ->name('projects.create');

    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');

        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
        ->name('projects.edit');

    Route::put('/projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update');

    // Delete
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    // Join project
    Route::post('/projects/{project}/join', [ProjectController::class, 'join'])
        ->name('projects.join');
});



Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::get('/{username}', [ProfileController::class, 'show'])
    ->where('username', '[a-zA-Z0-9_-]+')
    ->name('profile.show');

require __DIR__.'/auth.php'; 
