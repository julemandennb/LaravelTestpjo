<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DeveloperDashboardController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/token', [ProfileController::class, 'makeAToken'])->name('profile.makeAToken');
    Route::delete('/profile/token',[ProfileController::class, 'deleteAToken'])->name('profile.deleteAToken');




    Route::middleware('permission:orderDashboard')->resource('/order', OrderController::class);


    Route::middleware('permission:livechatDashboard')->group(function () {
        Route::get('/livechat',[LiveChatController::class, 'index'])->name('livechat.index');
        Route::get('/livechat/{user}',[LiveChatController::class, 'chat'])->name('livechat.chat');
        Route::post('/livechat/{user}',[LiveChatController::class, 'send'])->name('livechat.send');
    });


    Route::middleware('permission:activityLogDashboard')->get('/activityLog' ,ActivityLogController::class)->name('activityLog.index');

    Route::middleware('permission:developerDashboard')->get('/developerDashboard' ,DeveloperDashboardController::class)->name('developerDashboard.index');




});

require __DIR__.'/auth.php';
