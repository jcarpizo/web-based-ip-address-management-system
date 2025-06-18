<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');
Route::view('/login', 'login');
Route::view('/dashboard', 'dashboard');
Route::view('/ip-audit-logs', 'ip-address-logs');
Route::view('/auth-dashboard-logs', 'auth-dashboard-logs');
