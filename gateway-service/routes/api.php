<?php

use App\Http\Controllers\AuthServiceController;
use App\Http\Controllers\IpAddressServiceController;
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

Route::fallback(function () {
    // For an API request, return JSON
    return response()->json([
        'message' => 'Resource not found.'
    ], 404);
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthServiceController::class, 'authLogin'])->name('authLogin');
    Route::post('/register', [AuthServiceController::class, 'authRegister'])->name('authRegister');
    Route::post('/verify', [AuthServiceController::class, 'authVerifyToken'])->name('authVerifyToken');
    Route::post('/refresh', [AuthServiceController::class, 'authRefreshToken'])->name('authRefreshToken');
    Route::post('/logout', [AuthServiceController::class, 'authLogout'])->name('authLogout');
});

Route::group(['prefix' => 'ip'], function () {
    Route::post('/create', [IpAddressServiceController::class, 'ipCreate'])->name('ipCreate');
    Route::put('/update/{id}', [IpAddressServiceController::class, 'ipUpdate'])->name('ipUpdate');
    Route::get('/get/{id}', [IpAddressServiceController::class, 'ipGet'])->name('ipGet');
    Route::get('/list/{userId?}', [IpAddressServiceController::class, 'ipList'])->name('ipList');
    Route::delete('/delete/{id}', [IpAddressServiceController::class, 'ipDelete'])->name('ipDelete');
    Route::get('/logs', [IpAddressServiceController::class, 'ipLogs'])->name('ipLogs');
});
