<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanning\LayingPlanningsController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;
use App\Http\Controllers\GlsController;
use App\Http\Controllers\CuttingOrdersController;
use App\Http\Controllers\CuttingTicketsController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FetchController;

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

    Route::resource('color', ColorsController::class)->middleware('accessSuperAdmin');
    Route::get('/color-data', [ColorsController::class, 'dataColor'])->middleware('accessSuperAdmin');

    Route::get('/get-color-list', [ColorsController::class, 'get_color_list']);

    Route::resource('fabric-cons', FabricConssController::class)->middleware('accessSuperAdmin');
    Route::resource('fabric-type', FabricTypesController::class)->middleware('accessSuperAdmin');
    Route::resource('gl', GlsController::class)->middleware('accessSuperAdmin');
});

Route::group(['middleware' => ['auth','accessSuperAdmin','accessCutting']], function () {
    Route::resource('laying-planning',LayingPlanningsController::class);
    Route::get('/laying-planning-create', [LayingPlanningsController::class, 'layingCreate']);
    Route::get('/laying-planning-qrcode/{id}', [LayingPlanningsController::class, 'layingQrcode']);
    
    Route::prefix('laying-planning-detail')->name('laying-planning.')->group(function(){
        route::post('/create', [LayingPlanningsController::class, 'detail_create'])->name('detail-create');
        route::put('/{id}', [LayingPlanningsController::class, 'detail_update'])->name('detail-update');
        route::delete('/{id}', [LayingPlanningsController::class, 'detail_delete'])->name('detail-delete');
        route::get('/{id}/edit', [LayingPlanningsController::class, 'detail_edit'])->name('detail-edit');
    });

    Route::resource('cutting-order', CuttingOrdersController::class);
    Route::get('cutting-order-create/{id}', [CuttingOrdersController::class,'createNota'])->name('cutting-order.createNota');
    Route::get('cutting-order-print/{id}', [CuttingOrdersController::class,'print_pdf'])->name('cutting-order.print');

    Route::resource('cutting-ticket', CuttingTicketsController::class);
    Route::prefix('cutting-ticket')->name('cutting-ticket.')->group(function(){
        Route::post('/generate', [CuttingTicketsController::class, 'generate_ticket'])->name('generate');
    });
});

Route::group(['middleware' => ['auth','accessSuperAdmin','accessCutting']], function () {

    Route::prefix('fetch')->name('fetch.')->group(function(){
        Route::get('/',[FetchController::class, 'index'])->name('index');
        Route::get('buyer', [FetchController::class, 'buyer'])->name('buyer');
        Route::get('style', [FetchController::class, 'style'])->name('style');
        Route::get('color', [FetchController::class, 'color'])->name('color');
        
        Route::get('get-laying-sheet/{id}', [CuttingTicketsController::class, 'get_laying_sheet'])->name('laying-sheet');
    });
});