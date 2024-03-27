<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CuttingOrdersController;
use App\Http\Controllers\API\LayingPlanningController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\RemarksController;
use App\Http\Controllers\API\CuttingTicketsController;
use App\Http\Controllers\API\BundleCutsController;
use App\Http\Controllers\API\BundleStocksController;
use App\Http\Controllers\API\BundleLocationsController;
use App\Http\Controllers\API\FabricRequestSyncController;

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
Route::get('cutting-orders/radius/check-range-within-radius', [CuttingOrdersController::class, 'checkRangeWithinRadius']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'index']);

    Route::get('/colors', [ColorController::class, 'index']);

    Route::get('laying-planning', [LayingPlanningController::class, 'index']);
    Route::post('laying-planning-show', [LayingPlanningController::class, 'show']);
    

    Route::get('cutting-orders', [CuttingOrdersController::class, 'index']);
    Route::get('cutting-orders/{serial_number}', [CuttingOrdersController::class, 'show']);
    Route::post('cutting-orders', [CuttingOrdersController::class, 'store']);
    Route::put('cutting-orders/{id}', [CuttingOrdersController::class, 'update']);
    Route::delete('cutting-orders/{id}', [CuttingOrdersController::class, 'destroy']);
    Route::get('cutting-record-remark', [RemarksController::class, 'index']);
    Route::get('cutting-orders/gl/{id}', [CuttingOrdersController::class, 'getCuttingOrderRecordByGlId']);
    Route::get('cutting-orders/cor/{id}', [CuttingOrdersController::class, 'getLayingPlanningDetailByCuttingOrderRecordId']);
    Route::post('cutting-orders/status-cut', [CuttingOrdersController::class, 'postStatusCut']);
    Route::post('cutting-orders/search', [CuttingOrdersController::class, 'search']);

    
    

    Route::get('cutting-tickets', [CuttingTicketsController::class, 'index']);
    Route::put('cutting-tickets', [CuttingTicketsController::class, 'show']);
    
    Route::post('upload-sticker-fabric', [CuttingOrdersController::class, 'uploadStickerFabric']);

    Route::resource('bundle-cuts', BundleCutsController::class);
    Route::get('bundle-status', [BundleCutsController::class, 'bundleStatusIndex']);

    
    Route::controller(BundleStocksController::class)->prefix('bundle-stocks')->name('bundle-stocks.')->group(function(){
        Route::get('/', [BundleStocksController::class, 'index']);
        Route::post('/', [BundleStocksController::class, 'store']);
        route::post('store-multiple', 'store_multiple')->name('store-multiple');
    });
    
    Route::get('bundle-locations', [BundleLocationsController::class,'index']);
    
    Route::controller(FabricRequestSyncController::class)->prefix('fabric-request-sync')->name('fabric-request-sync.')->group(function(){
        Route::get('/', [FabricRequestSyncController::class, 'index']);
        Route::get('/get-fabric-request', [FabricRequestSyncController::class, 'getFabricRequest']);
    });

    Route::controller(CuttingOrdersController::class)->prefix('cutting-orders')->name('cutting-orders.')->group(function(){
        Route::put('fabric-roll/{id}', 'updateFabricRollByCorId')->name('update-fabric-roll');
        Route::delete('fabric-roll/{id}', 'deleteFabricRoll')->name('delete-fabric-roll');
    });
});
