<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanningsController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;
use App\Http\Controllers\FabricUsagesController;
use App\Http\Controllers\GlsController;
use App\Http\Controllers\CuttingOrdersController;
use App\Http\Controllers\SubconCuttingController;
use App\Http\Controllers\CuttingTicketsController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RemarksController;
use App\Http\Controllers\FabricRequisitionsController;
use App\Http\Controllers\FabricIssuesController;
use App\Http\Controllers\DailyCuttingReportsController;
use App\Http\Controllers\PalletsController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\BundleCutsController;
use App\Http\Controllers\BundleStocksController;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;

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

    Route::get('profile', [UsersController::class,'profile'])->name('profile.index');
    Route::post('profile/change_password', [UsersController::class,'profile_change_password'])->name('profile.change-password');
});

// ## Route for Datatable
Route::group(['middleware' => ['auth']], function () {
    Route::get('/user-data', [UsersController::class, 'dataUser']);
    Route::get('/buyer-data', [BuyerController::class, 'dataBuyer']);
    Route::get('/size-data', [SizesController::class, 'dataSize']);
    Route::get('/color-data', [ColorsController::class, 'dataColor']);
    Route::get('/fabric-cons-data', [FabricConssController::class, 'dataFabricCons']);
    Route::get('/fabric-type-data', [FabricTypesController::class, 'dataFabricType']);
    Route::get('/fabric-usage-data', [FabricUsagesController::class, 'dataFabricUsage']);
    Route::get('/remark-data', [RemarksController::class, 'dataRemark']);
    Route::get('/gl-data', [GlsController::class, 'dataGl']);
    Route::get('/style-data', [StylesController::class, 'dataStyle']);
    Route::get('/laying-planning-data', [LayingPlanningsController::class, 'dataLayingPlanning']);
    Route::get('/laying-planning-detail-data/{id}', [LayingPlanningsController::class, 'dataLayingPlanningDetail'])->name('laying-planning-detail-data');
    Route::get('/cutting-order-data', [CuttingOrdersController::class, 'dataCuttingOrder']);
    Route::get('/subcon-cutting-data', [SubconCuttingController::class, 'dataCuttingOrder']);
    Route::get('/cutting-order-chart', [CuttingOrdersController::class, 'chartCuttingOrder']);
    Route::get('/cutting-ticket-data', [CuttingTicketsController::class, 'dataCuttingTicket']);
    Route::get('/cutting-ticket-detail-data/{id}', [CuttingTicketsController::class, 'dataCuttingTicketByCOR'])->name('cutting-ticket-detail-data'); // http://localhost/cutting-ticket-app/public/cutting-ticket-detail-data/COR-62843-MHG-001
    Route::get('/get-color-list', [ColorsController::class, 'get_color_list']);
    Route::get('/fabric-requisition-data', [FabricRequisitionsController::class, 'dataFabricRequisition']);
    Route::get('/fabric-requisition-serial-number', [FabricRequisitionsController::class, 'get_serial_number'])->name('fabric-requisition-serial-number');
    Route::get('/fabric-issue-data', [FabricIssuesController::class, 'dataFabricIssue']);
    Route::get('/daily-cutting-data', [DailyCuttingReportsController::class, 'dataDailyCutting']);
});

// ## Route for Master Data (Admin)
Route::group(['middleware' => ['auth','can:admin-only']], function () {
    Route::resource('user-management', UsersController::class);
    Route::get('/user-cutting-group', [UsersController::class,'cutting_group'])->name('user-cutting-group.index');
    Route::get('/group-data', [UsersController::class,'dataGroup'])->name('group-data');
    Route::get('/edit-group/{id}', [UsersController::class,'edit_group'])->name('edit-group');
    Route::post('/store-group', [UsersController::class,'store_group'])->name('store-group');
    Route::put('/update-group/{id}', [UsersController::class,'update_group'])->name('update-group');
    Route::delete('/delete-group/{id}', [UsersController::class,'delete_group'])->name('delete-group');
    Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    

    Route::resource('machine', MachineController::class);
    Route::get('/machine/print/{serial_number}', [MachineController::class, 'print'])->name('machine.print');
    Route::get('/machine/print-multiple/{id}', [MachineController::class, 'print_multiple'])->name('machine.print-multiple');
    Route::get('/machine-data', [MachineController::class, 'dataMachine'])->name('machine-data');
    Route::get('/machine-qr-code', [MachineController::class, 'qr_code'])->name('machine-qr-code');
    // Route::resource('buyer', BuyerController::class);
    // Route::resource('size', SizesController::class);
    // Route::resource('color', ColorsController::class);
    // Route::resource('remark', RemarksController::class);
    Route::resource('pallets', PalletsController::class);
    Route::get('/pallets-print', [PalletsController::class, 'print'])->name('pallets.print');
    Route::get('/pallets-printt', [PalletsController::class, 'printt'])->name('pallets.printt');
    Route::get('qrcode', function () {
        return QrCode::size(300)->generate('A basic example of QR code!');
    });
    
    Route::put('user-management/reset/{id}', [UsersController::class,'reset'])->name('user-management.reset');
});

Route::group(['middleware' => ['auth','can:warehouse']], function () {
    Route::resource('fabric-issue', FabricIssuesController::class);
    Route::get('fabric-issue-print/{id}', [FabricIssuesController::class,'print'])->name('fabric-issue.print');
});

// ## Route for Master Data (Cutting Department)
Route::group(['middleware' => ['auth','can:clerk-cutting']], function () {
    Route::resource('gl', GlsController::class);
    Route::resource('style', StylesController::class);

    Route::resource('fabric-cons', FabricConssController::class);
    Route::resource('fabric-type', FabricTypesController::class);
    Route::resource('fabric-usage', FabricUsagesController::class);

    Route::resource('buyer', BuyerController::class);
    Route::resource('size', SizesController::class);
    Route::resource('color', ColorsController::class);
    Route::resource('remark', RemarksController::class);
});

Route::group(['middleware' => ['auth','can:clerk']], function () {
    Route::resource('laying-planning',LayingPlanningsController::class);
    Route::get('/laying-planning-create', [LayingPlanningsController::class, 'layingCreate']);
    Route::get('/laying-planning-qrcode/{id}', [LayingPlanningsController::class, 'layingQrcode']);
    Route::get('/laying-planning-report/{id}', [LayingPlanningsController::class, 'layingPlanningReport'])->name('laying-planning.report');
    Route::get('/laying-plannings-report/{id}', [LayingPlanningsController::class, 'layingPlanningv2Report'])->name('laying-planningv2.report');
    Route::get('/cutting-orders-report/{id}', [LayingPlanningsController::class, 'cuttingOrderv2Report'])->name('cutting-orderv2.report');

    Route::controller(LayingPlanningsController::class)
    ->prefix('laying-planning-detail')->name('laying-planning.')->group(function(){
        route::post('/create', 'detail_create')->name('detail-create');
        route::put('/{id}', 'detail_update')->name('detail-update');
        route::delete('/{id}', 'detail_delete')->name('detail-delete');
        route::get('/{id}/edit', 'detail_edit')->name('detail-edit');
        route::post('/detail-duplicate','detail_duplicate')->name('detail-duplicate');
        route::get('/{id}/duplication', 'duplicate')->name('duplicate');
    });

    Route::resource('cutting-order', CuttingOrdersController::class);
    Route::get('cutting-order-create/{id}', [CuttingOrdersController::class,'createNota'])->name('cutting-order.createNota');
    Route::get('cutting-order-print/{id}', [CuttingOrdersController::class,'print_pdf'])->name('cutting-order.print');
    Route::get('cutting-order-report/{id}', [CuttingOrdersController::class,'print_report_pdf'])->name('cutting-order.report');
    Route::get('cutting-order-detail/{id}', [CuttingOrdersController::class,'cutting_order_detail'])->name('cutting-order.detail');
    Route::get('cutting-order-approve-pilot-run/{id}', [CuttingOrdersController::class,'approve_pilot_run'])->name('cutting-order.approve-pilot-run');
    Route::get('cutting-order-record/{date}', [CuttingOrdersController::class,'getCuttingOrderRecordByDate'])->name('cutting-order.date');
    Route::get('print-multiple/{id}', [CuttingOrdersController::class,'print_multiple'])->name('cutting-order.print-multiple');
    Route::get('status-cutting-order-record', [CuttingOrdersController::class,'statusCuttingOrderRecord'])->name('cutting-order.status-cutting-order-record');
    Route::get('print-status-cutting-order-record', [CuttingOrdersController::class,'printStatusCuttingOrderRecord'])->name('cutting-order.print-status-cutting-order-record');
    Route::resource('subcon-cutting', SubconCuttingController::class);
    Route::get('cutting-report-subcon/{id}', [SubconCuttingController::class,'cutting_report_subcon'])->name('subcon-cutting.cutting-report-subcon');
    Route::get('cutting-order-detail-delete/{id}', [CuttingOrdersController::class,'delete_cor_detail'])->name('cutting-order.detail-delete');

    Route::resource('cutting-ticket', CuttingTicketsController::class);
    Route::prefix('cutting-ticket')->name('cutting-ticket.')->group(function(){
        Route::post('/generate', [CuttingTicketsController::class, 'generate_ticket'])->name('generate');
        Route::get('/print/{id}', [CuttingTicketsController::class, 'print_ticket'])->name('print');
        Route::get('/print-multiple/{id}', [CuttingTicketsController::class, 'print_multiple'])->name('print-multiple');
        Route::get('/detail/{id}', [CuttingTicketsController::class, 'ticketListByCOR'])->name('detail');

        Route::get('/report/{id}', [CuttingTicketsController::class, 'print_report_pdf'])->name('report');
        
        Route::delete('/delete/{id}', [CuttingTicketsController::class, 'delete_ticket'])->name('delete-ticket');
        Route::get('/refresh-ticket/{id}', [CuttingTicketsController::class, 'refresh_ticket'])->name('refresh-ticket');
    });

    Route::get('/cut-piece-stock-detail-data/{gl_number}', [BundleCutsController::class, 'cut_piece_stock_detail_data'])->name('cut-piece-stock-detail-data');
    Route::get('/cut-piece-stock', [BundleCutsController::class, 'cut_piece_stock'])->name('cut-piece-stock');
    Route::get('/cut-piece-stock-report', [BundleCutsController::class, 'cut_piece_stock_report'])->name('cut-piece-stock-report');
    Route::get('/cut-piece-stock-detail', [BundleCutsController::class, 'index'])->name('cut-piece-stock-detail');


    Route::controller(BundleStocksController::class)->prefix('bundle-stock')->name('bundle-stock.')->group(function(){
        route::get('/report', 'report')->name('report');
    });
});




Route::group(['middleware' => ['auth','can:form']], function () {
    Route::resource('fabric-requisition', FabricRequisitionsController::class);
    Route::get('fabric-requisition-create/{id}', [FabricRequisitionsController::class,'createNota'])->name('fabric-requisition.createNota');
    Route::get('fabric-requisition-print/{id}', [FabricRequisitionsController::class,'print_pdf'])->name('fabric-requisition.print');
    Route::get('fabric-requisition-detail/{id}', [FabricRequisitionsController::class,'fabric_requisition_detail'])->name('fabric-requisition.detail');
    Route::get('print-multiple-fabric-requisition/{id}', [FabricRequisitionsController::class,'print_multiple_fabric_requisition'])->name('fabric-requisition.print-multiple');    
});

Route::group(['middleware' => ['auth','can:cutting-record']], function () {
    Route::resource('daily-cutting-report', DailyCuttingReportsController::class);
    Route::get('daily-cutting-detail', [DailyCuttingReportsController::class,'dailyCuttingDetail'])->name('daily-cutting.detail');
    Route::get('daily-cutting-report-print', [DailyCuttingReportsController::class,'dailyCuttingReport'])->name('daily-cutting.print-report');

    Route::get('status-cutting-order-record', [CuttingOrdersController::class,'statusCuttingOrderRecord'])->name('cutting-order.status-cutting-order-record');
    Route::get('print-status-cutting-order-record', [CuttingOrdersController::class,'printStatusCuttingOrderRecord'])->name('cutting-order.print-status-cutting-order-record');

    Route::get('summary-report-group-cutting-order-record', [SubconCuttingController::class,'summary_report_group_cutting_order_record'])->name('subcon-cutting.summary-report-group-cutting-order-record');
    Route::get('cutting-print-subcon', [SubconCuttingController::class,'print'])->name('subcon-cutting.cutting-print-subcon');
    
    Route::get('cutting-order-completion', [CuttingOrdersController::class,'cuttingCompletion'])->name('cutting-order.cutting-completion');
    Route::get('cutting-order-completion-report', [CuttingOrdersController::class,'cuttingCompletionReport'])->name('cutting-order.cutting-completion-report');
    
    Route::get('tracking-fabric-usage', [FabricIssuesController::class,'trackingFabricUsage'])->name('fabric-issue.tracking-fabric-usage');
    Route::get('tracking-fabric-usage-report', [FabricIssuesController::class,'trackingFabricUsageReport'])->name('fabric-issue.tracking-fabric-usage-report');
});

// ## Route for Fetch Select2 Form
Route::middleware(['auth','can:clerk'])->prefix('fetch')->name('fetch.')->group(function(){
    Route::get('/',[FetchController::class, 'index'])->name('index');
    Route::get('buyer', [FetchController::class, 'buyer'])->name('buyer');
    Route::get('style', [FetchController::class, 'style'])->name('style');
    Route::get('color', [FetchController::class, 'color'])->name('color');
    Route::get('fabric-type', [FetchController::class, 'fabric_type'])->name('fabric-type');
    Route::get('gl-combine', [FetchController::class, 'gl_combine'])->name('gl-combine');
    
    Route::get('get-cutting-order/{id}', [CuttingTicketsController::class, 'get_cutting_order'])->name('cutting-order');
});

// ## Route for Fetch
Route::middleware(['auth','can:clerk'])->prefix('fetch')->name('fetch.')->group(function(){
    Route::get('cutting-table', [FetchController::class, 'cutting_table'])->name('cutting-table');
});