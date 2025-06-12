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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('ip_service')->group(
    function () {
        Route::post('/create', [IpAddressController::class, 'create'])->name('ip_create');
        Route::get('/list', [IpAddressController::class, 'list'])->name('ip_list');
        Route::put('/update/{id}', [IpAddressController::class, 'update'])->name('ip_update');
        Route::put('/delete/{id}', [IpAddressController::class, 'delete'])->name('ip_delete');
    }
);


