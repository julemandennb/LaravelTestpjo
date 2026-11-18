<?php

use App\Http\Controllers\Api\ProduktSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/produkt-search', ProduktSearchController::class)
    ->name('produkt.search');
