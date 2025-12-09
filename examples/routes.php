<?php

use Examples\Components;
use Examples\Users;
use Examples\Users\Endpoints\Controller;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');

/**
 * フロントエンド用のルーティング
 */
Route::get('/', Components\Welcome::class);
Route::get('/users', Users\Components\Cards::class);

/**
 * バックエンド用のルーティング
 */
Route::prefix('api/users')->group(function () {
    Route::get('/', [Controller::class, 'get']);
    Route::get('/{id}', [Controller::class, 'find']);
    Route::post('/', [Controller::class, 'create']);
    Route::put('/{id}', [Controller::class, 'update']);
    Route::delete('/{id}', [Controller::class, 'delete']);
});
