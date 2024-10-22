<?php

use App\Http\Controllers\VendingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //
});

Route::get('payment/settle/{purchase_id}', [VendingController::class, 'callback'])->name('vending.callback');
