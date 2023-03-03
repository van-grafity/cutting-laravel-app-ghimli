<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanning;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;
use App\Http\Controllers\GlsController;

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
    Route::resource('buyer', App\Http\Controllers\BuyerController::class)->middleware('accessSewing');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('laying-planning',LayingPlanning\LayingPlanningsController::class)->middleware('accessCutting');

    Route::resource('color', ColorsController::class)->middleware('accessSuperAdmin');
    Route::resource('fabric-cons', FabricConssController::class)->middleware('accessSuperAdmin');
    Route::resource('fabric-type', FabricTypesController::class)->middleware('accessSuperAdmin');
    Route::resource('gl', GlsController::class)->middleware('accessSuperAdmin');
});