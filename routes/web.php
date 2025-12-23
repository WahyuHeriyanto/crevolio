<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
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
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



require __DIR__.'/auth.php';

Route::get('/{username}', [ProfileController::class, 'show'])
    ->where('username', '[a-zA-Z0-9_-]+')
    ->name('profile.show');
