<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

$names = require __DIR__.'/names.php';

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name($names['home']);

Route::resource('auth', AuthController::class)
    ->only(['index', 'store', 'destroy'])
    ->names($names['auth']);

Route::resource('companies', \App\Http\Controllers\CompanyController::class)
    ->names($names['company']);
