<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MealPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::resource('stocks', StockController::class)->only('index','store','edit','update');

    Route::get('/orders/create/{item}', [OrderController::class, 'create'])->name('orders.create');
    Route::resource('orders', OrderController::class)->except('create', 'show');

    Route::resource('items', ItemController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('meal_plans', MealPlanController::class);
});
