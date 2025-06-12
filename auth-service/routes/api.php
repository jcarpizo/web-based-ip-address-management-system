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
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login');
    Route::post('/logout', [AuthController::class, 'postLogout'])->name('logout');

    Route::post('/register', [AuthController::class, 'postRegister'])->name('register');
    Route::post('/refresh', [AuthController::class, 'postRefresh'])->name('refresh');
    Route::post('/verify', [AuthController::class, 'postVerify'])->name('verify');
});
