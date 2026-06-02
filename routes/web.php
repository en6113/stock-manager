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

    Route::resource('items', ItemController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('meal_plans', MealPlanController::class);
    Route::resource('orders', OrderController::class);
});
