<?php

use App\Http\Controllers\IpAddressController;
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

Route::prefix('ip')->group(
    function () {
        Route::post('/create', [IpAddressController::class, 'ipCreate'])->name('ipCreate');
        Route::put('/update/{id}', [IpAddressController::class, 'ipUpdate'])->name('ipUpdate');
        Route::get('/get/{id}', [IpAddressController::class, 'ipGet'])->name('ipGet');
        Route::get('/list/{id?}', [IpAddressController::class, 'ipList'])->name('ipList');
        Route::delete('/delete/{id}', [IpAddressController::class, 'ipDelete'])->name('ipDelete');
        Route::get('/logs', [IpAddressController::class, 'ipLogList'])->name('ipLogList');
    }
);


