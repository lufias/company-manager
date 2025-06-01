<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

$names = require __DIR__ . '/names.php';

Route::get('/', function () use ($names) {
    if (Auth::check()) {
        return redirect()->route('company.index');
    }
    return view('welcome');
})->name($names['home']);

Route::resource('auth', AuthController::class)
    ->only(['index', 'store'])
    ->names($names['auth']);

Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

Route::resource('companies', \App\Http\Controllers\CompanyController::class)
    ->names($names['company']);
