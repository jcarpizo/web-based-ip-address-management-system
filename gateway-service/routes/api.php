<?php

use App\Http\Controllers\AuthServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'gateway'], function () {
    Route::post('/login', [AuthServiceController::class, 'authLogin'])->name('authLogin');
    Route::post('/register', [AuthServiceController::class, 'authRegister'])->name('authRegister');
    Route::post('/verify', [AuthServiceController::class, 'authVerifyToken'])->name('authVerifyToken');
    Route::post('/refresh', [AuthServiceController::class, 'authRefreshToken'])->name('authRefreshToken');
    Route::post('/logout', [AuthServiceController::class, 'authLogout'])->name('authLogout');
});
