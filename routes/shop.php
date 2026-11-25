<?php

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\ProduktController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\TrackingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CustomerOrderController;


Route::get('/', [ShopController::class,'index'])->name("shop");

Route::prefix('shop')->name('shop.')->group(function () {


    Route::get('/produkts', [ProduktController::class, 'index'])
        ->name('produkt.index');

    Route::get('/produkt/{produktid}', [ProduktController::class, 'show'])
        ->name('produkt.show');


    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::delete('/cart/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');


    Route::get('/tracking', [TrackingController::class, 'index'])
    ->name('tracking.index');
});

Route::middleware(['auth'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::put('/password', [ProfileController::class, 'updatePassword'])
            ->name('password.update');

        Route::get('/orders', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
            ->name('orders.show');
    });
