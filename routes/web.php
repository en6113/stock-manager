<?php

use App\Http\Controllers\ExportCsvController;
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

    // 在庫関係（一覧画面にて在庫の登録・更新のみ）
    Route::resource('stocks', StockController::class)->only('index','store','edit','update');

    // 発注・納品記録関係（在庫一覧から食材選択後に新規登録するため特別にcreateに{item}をもたせた）
    Route::get('/orders/create/{item}', [OrderController::class, 'create'])->name('orders.create');
    Route::resource('orders', OrderController::class)->except('create', 'show');

    // 食材・メニュー・献立関係
    Route::resource('items', ItemController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('meal_plans', MealPlanController::class);

    // CSV出力関係
    Route::get('/reports/export-csv', [ExportCsvController::class, 'index'])->name('reports.index');
    Route::post('/reports/export-csv', [ExportCsvController::class, 'export'])->name('reports.export');
});
