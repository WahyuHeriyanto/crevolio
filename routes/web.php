<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SavedProjectController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;


$baseDomain = str_replace(['http://', 'https://'], '', config('app.url'));

Route::domain('vectra.' . $baseDomain)->group(function () {
    
    // Landing Page Vectra
    Route::get('/', function () {
        return view('vectra.landing');
    })->name('vectra.index');

    Route::middleware(['auth', 'profile.completed'])->get('/dashboard', function () {
        return view('vectra.dashboard');
    })->name('vectra.dashboard');
});

//Guest
Route::middleware('guest')->group(function () {
    // Route::get('/', function () {
    //     return view('landing');
    // })->name('landing');
    Route::get('/', [LandingController::class, 'index'])->name('landing');
});

Route::view('/privacy-policy', 'pages.privacy')->name('privacy.policy');

Route::middleware(['auth', 'profile.completed.redirect'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])
        ->name('onboarding.index');

    Route::post('/onboarding/store', [OnboardingController::class, 'store'])
        ->name('onboarding.store');
});

Route::middleware(['auth', 'profile.completed'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

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

    Route::put('/projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update');

    // Delete
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    Route::post('/projects/{project}/like', [ProjectController::class, 'toggleLike'])
        ->name('projects.like');

    Route::post('/projects/{project}/save', [ProjectController::class, 'toggleSave'])
        ->name('projects.save');

    // Join project
    Route::post('/projects/{project}/join', [ProjectController::class, 'join'])
        ->name('projects.join');
    
    Route::delete('/projects/{project}/leave', [ProjectController::class, 'leave'])
        ->name('projects.leave');

    Route::get('/users/{user}/{type}', [FollowController::class, 'showList'])
        ->where('user', '[0-9]+')
        ->where('type', 'followers|following')
        ->name('follow.list');
    
    Route::post('/follow/{user}', [FollowController::class, 'follow'])
        ->name('follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])
        ->name('unfollow');
    Route::post('/cancel-request/{user}', [FollowController::class, 'cancelRequest'])
        ->name('follow.cancel');
    Route::post('/follow/{user}/toggle', [FollowController::class, 'toggleFollow'])
        ->name('follow.toggle');
    Route::get('/profile/follow-requests', [FollowController::class, 'followRequests'])
        ->name('profile.follow-requests');
    Route::post('/profile/follow-requests/{id}/accept', [FollowController::class, 'acceptRequest'])
        ->name('profile.accept-request');
    Route::post('/profile/follow-requests/{id}/decline', [FollowController::class, 'declineRequest'])
        ->name('profile.decline-request');

    Route::post('/projects/{project}/join', [ProjectController::class, 'join'])
        ->name('projects.join');
    Route::get('/my-requests', [ProjectController::class, 'requests'])
        ->name('projects.requests');
    Route::post('/requests/{request}/{action}', [ProjectController::class, 'handleRequest'])
        ->name('projects.handle-request');
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.markAllRead');
    Route::get('/projects/saved', [SavedProjectController::class, 'index'])
        ->name('projects.saved');

    Route::resource('portfolios', PortfolioController::class)->except(['index', 'show']);

    Route::get('/profile/{username}/export', [ProfileController::class, 'export'])
    ->name('profile.export');
});

require __DIR__.'/auth.php'; 

Route::get('/projects/{project:id}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/projects/{project}/export', [ProjectController::class, 'export'])->name('projects.export');

Route::get('/{username}', [ProfileController::class, 'show'])
    ->where('username', '[a-zA-Z0-9_-]+')
    ->name('profile.show');


