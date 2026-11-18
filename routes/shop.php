<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\ProduktController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;


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

});
