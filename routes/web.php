<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// 仮ルート
Route::middleware('auth')->group(function () {
    Route::post('/items', fn () => '食材登録（準備中）')->name('items.create');
    Route::put('/items/edit/{item}', fn () => '食材編集（準備中）')->name('items.edit');
    Route::get('/stocks', fn () => '在庫管理一覧（準備中）')->name('stocks.index');
});
