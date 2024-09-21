<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SuburbController;
use App\Http\Controllers\TariffGroupController;
use App\Http\Controllers\VendingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot', [AuthController::class, 'forgot']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::match(['get', 'post'],'logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'dashboard'], function() {
        Route::get('properties', [DashboardController::class, 'properties']);
        Route::get('balances', [DashboardController::class, 'balances']);
        Route::get('activities', [DashboardController::class, 'activities']);
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('', [ProfileController::class, 'user']);
        Route::post('change-password', [ProfileController::class, 'changePassword']);
    });

    Route::resource('portal-users', PortalUserController::class);
    Route::resource('activity-log', ActivityLogController::class);
    Route::resource('suburbs', SuburbController::class);
    Route::resource('services', ServiceController::class);
    Route::post('services/reorder', [ServiceController::class, 'reorder']);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('tariff-groups', TariffGroupController::class);
    Route::resource('properties', PropertyController::class);
    Route::resource('property-types', PropertyTypeController::class);

    Route::group(['prefix' => 'vending'], function () {
        Route::post('meter-lookup', [VendingController::class, 'meterLookup']);
        Route::post('buy', [VendingController::class, 'buy']);
    });
});

