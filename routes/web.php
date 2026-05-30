<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use const Dom\INDEX_SIZE_ERR;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::resource('stocks', StockController::class)->only('index','show','store','update');

    Route::resource('items', ItemController::class);
});
