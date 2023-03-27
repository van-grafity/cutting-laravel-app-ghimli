<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanning\LayingPlanningsController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;
use App\Http\Controllers\GlsController;
use App\Http\Controllers\CuttingOrdersController;
use App\Http\Controllers\CuttingTicketsController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\UsersController;

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
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user-data', [UsersController::class, 'dataUser']);
    Route::get('/buyer-data', [BuyerController::class, 'dataBuyer']);
    Route::get('/size-data', [SizesController::class, 'dataSize']);
    Route::get('/color-data', [ColorsController::class, 'dataColor']);
    Route::get('/fabric-cons-data', [FabricConssController::class, 'dataFabricCons']);
    Route::get('/fabric-type-data', [FabricTypesController::class, 'dataFabricType']);
    Route::get('/gl-data', [GlsController::class, 'dataGl']);
    Route::get('/style-data', [StylesController::class, 'dataStyle']);
    Route::get('/get-color-list', [ColorsController::class, 'get_color_list']);
});

Route::group(['middleware' => ['auth','can:admin-only']], function () {
    Route::resource('user-management', UsersController::class);
    Route::resource('buyer', BuyerController::class);
    Route::resource('size', SizesController::class);
    Route::resource('color', ColorsController::class);
    Route::resource('fabric-cons', FabricConssController::class);
    Route::resource('fabric-type', FabricTypesController::class);
    Route::resource('gl', GlsController::class);
    Route::resource('style', StylesController::class);
});

Route::group(['middleware' => ['auth','can:clerk']], function () {
    Route::resource('laying-planning',LayingPlanningsController::class);
    Route::get('/laying-planning-create', [LayingPlanningsController::class, 'layingCreate']);
    Route::get('/laying-planning-qrcode/{id}', [LayingPlanningsController::class, 'layingQrcode']);
    
    Route::controller(LayingPlanningsController::class)
    ->prefix('laying-planning-detail')->name('laying-planning.')->group(function(){
        route::post('/create', 'detail_create')->name('detail-create');
        route::put('/{id}', 'detail_update')->name('detail-update');
        route::delete('/{id}', 'detail_delete')->name('detail-delete');
        route::get('/{id}/edit', 'detail_edit')->name('detail-edit');
    });

    Route::resource('cutting-order', CuttingOrdersController::class);
    Route::get('cutting-order-create/{id}', [CuttingOrdersController::class,'createNota'])->name('cutting-order.createNota');
    Route::get('cutting-order-print/{id}', [CuttingOrdersController::class,'print_pdf'])->name('cutting-order.print');

    Route::resource('cutting-ticket', CuttingTicketsController::class);
    Route::prefix('cutting-ticket')->name('cutting-ticket.')->group(function(){
        Route::post('/generate', [CuttingTicketsController::class, 'generate_ticket'])->name('generate');
    });
});

Route::middleware(['auth','can:clerk'])->prefix('fetch')->name('fetch.')->group(function(){
    Route::get('/',[FetchController::class, 'index'])->name('index');
    Route::get('buyer', [FetchController::class, 'buyer'])->name('buyer');
    Route::get('style', [FetchController::class, 'style'])->name('style');
    Route::get('color', [FetchController::class, 'color'])->name('color');
    
    Route::get('get-laying-sheet/{id}', [CuttingTicketsController::class, 'get_laying_sheet'])->name('laying-sheet');
});