<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversionController;

Route::middleware('throttle:60,1')->group(function () {
    Route::post('/convert', [ConversionController::class, 'convert']);
});

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/conversions', [ConversionController::class, 'index']);
});
