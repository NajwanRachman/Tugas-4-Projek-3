<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatelessCartController;

Route::get('/', function () {
    return redirect()->route('stateless.cart.index');
});

Route::get('/stateless-cart', [StatelessCartController::class, 'index'])
    ->name('stateless.cart.index');

Route::post('/stateless-cart/add', [StatelessCartController::class, 'add'])
    ->name('stateless.cart.add');

Route::post('/stateless-cart/update', [StatelessCartController::class, 'update'])
    ->name('stateless.cart.update');
