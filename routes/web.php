<?php

use App\Http\Controllers\ProviderController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('providers', ProviderController::class);

Route::prefix('test')->group(function () {
    Route::get('provider-one', [TestController::class, 'providerOne']);
    Route::get('provider-two', [TestController::class, 'providerTwo']);
});
