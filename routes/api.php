<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CuttingOrdersController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\RemarksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'index']);
    
    Route::get('/colors', [ColorController::class, 'index']);
    Route::get('cutting-orders', [CuttingOrdersController::class, 'index']);
    Route::get('cutting-orders/{serial_number}', [CuttingOrdersController::class, 'show']);
    Route::post('cutting-orders', [CuttingOrdersController::class, 'store']);
    Route::put('cutting-orders/{id}', [CuttingOrdersController::class, 'update']);
    Route::delete('cutting-orders/{id}', [CuttingOrdersController::class, 'destroy']);

    Route::get('remarks', [RemarksController::class, 'index']);

});
