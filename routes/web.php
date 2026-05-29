<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// 仮ルート
Route::middleware('auth')->group(function () {

    Route::get('/stocks', fn () => '在庫管理一覧（準備中）')->name('stocks.index');

    Route::resource('items', ItemController::class);
});
