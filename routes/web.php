<?php

use App\Http\Controllers\ProviderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('providers', ProviderController::class);
