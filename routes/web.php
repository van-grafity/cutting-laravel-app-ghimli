<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanning;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;

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

Route::middleware(['auth'])->group(function () {
    Route::resource('home', App\Http\Controllers\HomeController::class);
    Route::get('/qrcode', [App\Http\Controllers\CuttingQrCodeController::class, 'index']);
    Route::get('/qrcode/1', [App\Http\Controllers\CuttingQrCodeController::class, 'show']);
    Route::resource('cutting', App\Http\Controllers\CuttingController::class)->middleware('accessSuperAdmin','accessCutting');
    Route::resource('clothroll', App\Http\Controllers\ClothRollController::class)->middleware('accessSuperAdmin');
    Route::resource('purchaseorder', App\Http\Controllers\PurchaseOrderController::class)->middleware('accessSuperAdmin');
    Route::resource('buyer', App\Http\Controllers\BuyerController::class)->middleware('accessSuperAdmin','accessSewing');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('laying-planning',LayingPlanning\LayingPlanningsController::class)->middleware('accessSuperAdmin');

    Route::resource('color', ColorsController::class)->middleware('accessSuperAdmin');
    Route::resource('fabric-cons', FabricConssController::class)->middleware('accessSuperAdmin');
    Route::resource('fabric-type', FabricTypesController::class)->middleware('accessSuperAdmin');
});