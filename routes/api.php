<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::controller(OrderController::class)->group(function () {

    Route::post('/orders', 'create')->name('order.create.post');
    Route::get('/orders', 'index')->name('order.list.get');
    Route::post('/orders/{id}/status', 'update')->name('order.update.post');
});
