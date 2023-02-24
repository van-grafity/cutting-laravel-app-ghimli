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

Auth::routes();

Route::get('datatable/{url}', [App\Http\Controllers\datatableController::class, 'index']);
Route::middleware(['auth'])->group(function () {
    Route::resource('home', App\Http\Controllers\HomeController::class);
    Route::resource('cutting', App\Http\Controllers\CuttingController::class);
    Route::resource('clothroll', App\Http\Controllers\ClothRollController::class);
    Route::resource('purchaseorder', App\Http\Controllers\PurchaseOrderController::class);
});