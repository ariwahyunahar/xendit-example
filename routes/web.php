<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [\App\Http\Controllers\DonasiController::class, 'index']);
Route::post('/donasi', [\App\Http\Controllers\DonasiController::class, 'do_donasi']);
Route::get('/cekdonasi/{trx_id}', [\App\Http\Controllers\DonasiController::class, 'cekdonasi']);
Route::get('/donasi/ok', [\App\Http\Controllers\DonasiController::class, 'donasiok']);
Route::get('/cektrx', [\App\Http\Controllers\DonasiController::class, 'cektrx']);
Route::post('/cektrx', [\App\Http\Controllers\DonasiController::class, 'cektrx']);
