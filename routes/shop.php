<?php

use App\Http\Controllers\Shop\ProduktController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ShopController::class,'index'])->name("shop");

Route::get('/shop/produkts', [ProduktController::class, 'index'])
    ->name('shop.produkt.index');
