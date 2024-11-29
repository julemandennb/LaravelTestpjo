<?php

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


    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/show/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::put('/order/update/{order}', [OrderController::class, 'update'])->name('order.update');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('order.delete');


    Route::get('/profile/token', [ProfileController::class, 'getAToken'])->name('profile.getAToken');


});

require __DIR__.'/auth.php';
