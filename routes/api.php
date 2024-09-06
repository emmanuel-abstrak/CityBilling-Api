<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('currencies', [CurrencyController::class, 'index']);
Route::get('currencies/{id}', [CurrencyController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::match(['get', 'post'],'logout', [AuthController::class, 'logout']);

    Route::get('user', [ProfileController::class, 'user']);
});

