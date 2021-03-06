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

Route::get('/', [\App\Http\Controllers\shippingController::class,'index']);
Route::get('provinces', [\App\Http\Controllers\shippingController::class,'provinces']);
Route::get('city', [\App\Http\Controllers\shippingController::class,'city'])->name('get-city');
Route::get('costs', [\App\Http\Controllers\shippingController::class,'costs'])->name('get-costs');
