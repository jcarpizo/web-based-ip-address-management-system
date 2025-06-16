<?php

use App\Http\Controllers\AuthController;
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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'authLogin'])->name('authLogin');
    Route::post('/logout', [AuthController::class, 'authLogout'])->name('authLogout');

    Route::post('/register', [AuthController::class, 'authRegister'])->name('authRegister');
    Route::post('/refresh', [AuthController::class, 'authRefresh'])->name('authRefresh');
    Route::post('/verify', [AuthController::class, 'authVerify'])->name('authVerify');
    Route::get('/logs', [AuthController::class, 'authUserLogs'])->name('authUserLogs');
});
