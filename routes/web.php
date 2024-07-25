<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayingPlanningsController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FabricConssController;
use App\Http\Controllers\FabricTypesController;
use App\Http\Controllers\GlsController;
use App\Http\Controllers\CuttingOrdersController;
use App\Http\Controllers\CuttingGroupReportController;
use App\Http\Controllers\CuttingTicketsController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RemarksController;
use App\Http\Controllers\FabricRequisitionsController;
use App\Http\Controllers\FabricIssuesController;
use App\Http\Controllers\DailyCuttingReportsController;
use App\Http\Controllers\BundleStocksController;
use App\Http\Controllers\BundleTransferNotesController;
use App\Http\Controllers\UpdateDatabasesController;
use App\Http\Controllers\CuttingOutputReportController;

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

// ## User Management
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\UsersController::class,
    'prefix' => 'user-management',
    'as' => 'user-management.',
],function() {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{user}/restore','restore')->name('restore');
    Route::get('{user}/reset-password','reset_password')->name('reset-password');
    Route::get('{user}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{user}', 'update')->name('update');
    Route::delete('{user}', 'destroy')->name('destroy');
});

// ## User Profile
Route::group([
    'middleware' => [
        'auth',
    ],
    'controller' => App\Http\Controllers\UsersController::class,
    'prefix' => 'profile',
    'as' => 'profile.',
],function() {
    Route::get('', 'profile')->name('index');
    Route::post('change-password', 'profile_change_password')->name('change-password');
});

// ## Cutting Group
Route::group([
    'middleware' => [
        'auth',
        'can:cutting-group-menu',
    ],
    'controller' => App\Http\Controllers\CuttingGroupsController::class,
    'prefix' => 'cutting-group',
    'as' => 'cutting-group.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('sync-old-data', 'sync_old_data')->name('sync-old-data');

    Route::get('{group}/show-members', 'show_members')->name('show-members');
    Route::put('{group}/update-members', 'update_members')->name('update-members');

    Route::get('', 'index')->name('index');
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');
});

// ## Department
Route::group([
    'middleware' => [
        'auth',
        'can:department-menu',
    ],
    'controller' => App\Http\Controllers\DepartmentsController::class,
    'prefix' => 'department',
    'as' => 'department.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');

    Route::get('', 'index')->name('index');
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');
});


// ## Permission Category
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\PermissionCategoryController::class,
    'prefix' => 'permission-category',
    'as' => 'permission-category.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');

    Route::get('', 'index')->name('index');
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');
});

// ## Permission
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\PermissionController::class,
    'prefix' => 'permission',
    'as' => 'permission.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');

    Route::get('', 'index')->name('index');
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');
});

// ## Role
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\RoleController::class,
    'prefix' => 'role',
    'as' => 'role.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('fill-empty-data', 'fill_empty_data')->name('fill-empty-data');

    Route::get('', 'index')->name('index');
    Route::get('{group}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{group}', 'update')->name('update');
    Route::delete('{group}', 'destroy')->name('destroy');

    Route::get('{group}/manage-permission', 'manage_permission')->name('manage-permission');
    Route::post('{group}/manage-permission', 'manage_permission_update')->name('manage-permission-update');

});

// ## Fetch Select
Route::group([
    'middleware' => [
        'auth',
        'can:user-menu',
    ],
    'controller' => App\Http\Controllers\FetchSelectController::class,
    'prefix' => 'fetch-select',
    'as' => 'fetch-select.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('user', 'select_user')->name('user');
    Route::get('user-multiple', 'select_user_multiple')->name('user-multiple');
});



// ## Personal Access Token
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\PersonalAccessTokensController::class,
    'prefix' => 'personal-access-token',
    'as' => 'personal-access-token.',
],function() {
    Route::get('dtable', 'dtable')->name('dtable');
    Route::post('revoke-token', 'revoke_token')->name('revoke-token');

    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store');
    Route::delete('{token}', 'destroy')->name('destroy');
});

// ## Authentication Log
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ],
    'controller' => App\Http\Controllers\AuthenticationLogController::class,
    'prefix' => 'authentication-log',
    'as' => 'authentication-log.',
],function() {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
});


// ## Laying Planning Detail and Cutting Order Record
Route::group([
    'middleware' => [
        'auth',
    ],
    'controller' => App\Http\Controllers\LayingPlanningDetailController::class,
    'prefix' => 'laying-planning-detail',
    'as' => 'laying-planning-detail.',
],function() {
    Route::get('unprint-cor', 'unprint_cor')->name('unprint-cor')->middleware('can:laying-planning-detail.unprint-cor');
    Route::get('unprint-fbr', 'unprint_fbr')->name('unprint-fbr')->middleware('can:laying-planning-detail.unprint-fbr');
});


Route::group([
    'middleware' => [
        'auth',
    ],
    'controller' => App\Http\Controllers\LayingPlanningReportsController::class,
    'prefix' => 'laying-planning-report',
    'as' => 'laying-planning-report.',
],function() {
    Route::get('{laying_planning_id}/marker-requirement', 'markerRequirement')->name('marker-requirement')->middleware('can:laying-planning-report.marker-requirement');
});


// ## daily cutting output report
Route::group([
    'middleware' => [
        'auth',
        'hasAnyPermission:cutting-record,daily-cutting-report.access',
    ],
    'controller' => App\Http\Controllers\DailyCuttingReportsController::class,
    'prefix' => 'daily-cutting-report',
    'as' => 'daily-cutting-report.',
],function() {
    Route::get('', 'index')->name('index');
    Route::get('print', 'print')->name('print');
    Route::get('print-yds', 'print_yds')->name('print-yds')->middleware('can:daily-cutting-report.print-yds');
});


// !! ini hapus punya ridwan, awalnya mau buat controller baru tapi belum selesai. udah di lanjutkan sama josh
// ## Cutting Completion Report
Route::group([
    'middleware' => [
        'auth',
        'hasAnyPermission:cutting-record,cutting-completion-report.access',
    ],
    'controller' => App\Http\Controllers\CuttingOrdersController::class,
    // 'prefix' => 'cutting-order',
    'as' => 'cutting-order.',
],function() {
    Route::get('cutting-order-completion', 'cuttingCompletion')->name('cutting-completion');
    Route::get('cutting-order-completion-report', 'cuttingCompletionReport')->name('cutting-completion-report');
});

// ## Fabric Consumption
Route::group([
    'middleware' => [
        'auth',
        'hasAnyPermission:fabric-consumption.access',
    ],
    'controller' => App\Http\Controllers\FabricConsumptionsController::class,
    'prefix' => 'fabric-consumption',
    'as' => 'fabric-consumption.',
],function() {
    Route::get('', 'index')->name('index');
    Route::get('print-preview', 'print_preview')->name('print-preview');
});

// ## Create new Controller and Route from cutting-order-completion to cutting-completion-report
// ## Cutting Completion Report
Route::group([
    'middleware'=> [
        'auth',
        'hasAnyPermission:cutting-record,cutting-completion-report.access',
    ],
    'controller' =>App\Http\Controllers\CuttingCompletionReportsController::class,
    'prefix' => 'cutting-completion-report',
    'as'=> 'cutting-completion-report.',
], function() {
    Route::get('', 'index')->name('index');
    Route::get('print', 'print')->name('print');
});
// ## end
// ## ---------------------------------------------------------------------------------



Route::middleware(['auth'])->group(function () {
    Route::resource('home', App\Http\Controllers\HomeController::class);
});

// ## Route for Datatable
Route::group(['middleware' => ['auth']], function () {
    Route::get('/buyer-data', [BuyerController::class, 'dataBuyer']);
    Route::get('/size-data', [SizesController::class, 'dataSize']);
    Route::get('/color-data', [ColorsController::class, 'dataColor']);
    Route::get('/fabric-cons-data', [FabricConssController::class, 'dataFabricCons']);
    Route::get('/fabric-type-data', [FabricTypesController::class, 'dataFabricType']);
    Route::get('/remark-data', [RemarksController::class, 'dataRemark']);
    Route::get('/gl-data', [GlsController::class, 'dataGl']);
    Route::get('/style-data', [StylesController::class, 'dataStyle']);
    Route::get('/laying-planning-data', [LayingPlanningsController::class, 'dataLayingPlanning']);
    Route::get('/laying-planning-detail-data/{id}', [LayingPlanningsController::class, 'dataLayingPlanningDetail'])->name('laying-planning-detail-data');
    Route::get('/cutting-order-data', [CuttingOrdersController::class, 'dataCuttingOrder']);

    Route::get('/cutting-order-chart', [CuttingOrdersController::class, 'chartCuttingOrder']);
    Route::get('/cutting-ticket-data', [CuttingTicketsController::class, 'dataCuttingTicket']);
    Route::get('/cutting-ticket-detail-data/{id}', [CuttingTicketsController::class, 'dataCuttingTicketByCOR'])->name('cutting-ticket-detail-data');
    Route::get('/get-color-list', [ColorsController::class, 'get_color_list']);
    Route::get('/fabric-requisition-data', [FabricRequisitionsController::class, 'dataFabricRequisition']);
    Route::get('/fabric-requisition-serial-number', [FabricRequisitionsController::class, 'get_serial_number'])->name('fabric-requisition-serial-number');
    Route::get('/fabric-issue-data', [FabricIssuesController::class, 'dataFabricIssue']);

    // !! delete this unused route
    // Route::get('/daily-cutting-data', [DailyCuttingReportsController::class, 'dataDailyCutting']);

});

// ## Route for Master Data (Admin)
Route::group(['middleware' => ['auth','can:admin-only']], function () {



    // !! nanti buat controller sendiri
    Route::get('/user-cutting-group', [UsersController::class,'cutting_group'])->name('user-cutting-group.index');
    Route::get('/group-data', [UsersController::class,'dataGroup'])->name('group-data');
    Route::get('/edit-group/{id}', [UsersController::class,'edit_group'])->name('edit-group');
    Route::post('/store-group', [UsersController::class,'store_group'])->name('store-group');
    Route::put('/update-group/{id}', [UsersController::class,'update_group'])->name('update-group');
    Route::delete('/delete-group/{id}', [UsersController::class,'delete_group'])->name('delete-group');
    Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
    // !! ============================

    Route::get('qrcode', function () {
        return QrCode::size(300)->generate('A basic example of QR code!');
    });

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

    Route::resource('buyer', BuyerController::class);
    Route::resource('size', SizesController::class);
    Route::resource('color', ColorsController::class);
    Route::resource('remark', RemarksController::class);
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('laying-planning',LayingPlanningsController::class);
    Route::get('/laying-planning-create', [LayingPlanningsController::class, 'layingCreate']);
    Route::get('/laying-planning-qrcode/{id}', [LayingPlanningsController::class, 'layingQrcode']);
    Route::get('/laying-planning-report/{id}', [LayingPlanningsController::class, 'layingPlanningReport'])->name('laying-planning.report');

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
    // Route::get('status-cutting-order-record', [CuttingOrdersController::class,'statusCuttingOrderRecord'])->name('cutting-order.status-cutting-order-record');
    Route::get('print-status-cutting-order-record', [CuttingOrdersController::class,'printStatusCuttingOrderRecord'])->name('cutting-order.print-status-cutting-order-record');

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

    Route::controller(BundleStocksController::class)->prefix('bundle-stock-report')->name('bundle-stock-report.')->middleware(['auth', 'hasAnyPermission:cutting-record,cut-piece-stock-report.access'])->group(function(){
        route::get('/', 'filter')->name('filter');
        route::get('/print', 'print')->name('print');
    });

    Route::controller(BundleStocksController::class)->prefix('bundle-stock')->name('bundle-stock.')
    ->middleware('can:cut-piece-stock.access')->group(function(){
        Route::group(['middleware'=> "can:cut-piece-stock.status"], function() {
            route::get('/', 'index')->name('index');
            route::get('/dtable', 'dataBundleStock')->name('dtable');
            route::get('/detail', 'detail')->name('detail');
        });

        route::get('/stock-in','stockIn')->middleware('can:cut-piece-stock.stock-in')->name('stock-in');
        route::get('/stock-out','stockOut')->middleware('can:cut-piece-stock.stock-out')->name('stock-out');
        Route::group(['middleware'=> "hasAnyPermission:cut-piece-stock.stock-in,cut-piece-stock.stock-out"], function() {
            route::post('/store-multiple', "storeMultiple")->name('store-multiple');
            route::post('/search-ticket', 'searchTicket')->name('search-ticket');
        });

        route::get('/report', 'report')->name('report');
        Route::get('/search-serial-number/{id}','search_serial_number')->name('search_serial_number');

    });

    Route::controller(BundleTransferNotesController::class)->prefix('bundle-transfer-note')
    ->name('bundle-transfer-note.')->middleware(['can:cut-piece-stock.transfer-note', 'can:cut-piece-stock.access'])->group(function(){
        route::get('/', 'index')->name('index');
        route::get('/dtable', 'dataTransferNote')->name('dtable');
        route::get('/detail/{id}', 'detail')->name('detail');
        route::get('/print/{id}', 'print')->name('print');
        route::get('/edit/{id}', 'edit')->middleware("can:super-admin")->name('edit');
        route::put('/update/{id}', 'updateTransferNote')->name('update');
    });
});




Route::group(['middleware' => ['auth','can:form']], function () {
    Route::resource('fabric-requisition', FabricRequisitionsController::class);
    Route::get('fabric-requisition-create/{id}', [FabricRequisitionsController::class,'createNota'])->name('fabric-requisition.createNota');
    Route::get('fabric-requisition-print/{id}', [FabricRequisitionsController::class,'print_pdf'])->name('fabric-requisition.print');
    Route::get('print-multiple-fabric-requisition/{id}', [FabricRequisitionsController::class,'print_multiple_fabric_requisition'])->name('fabric-requisition.print-multiple');
});


// !! next delete cutting order completion ini kodingan lama
//## ganti permission cutting-order-completion
Route::group(['middleware' => ['auth', 'hasAnyPermission:cutting-record,cutting-completion-report.access']], function(){
    Route::get('cutting-order-completion', [CuttingOrdersController::class,'cuttingCompletion'])->name('cutting-order.cutting-completion');
    Route::get('cutting-order-completion-report', [CuttingOrdersController::class,'cuttingCompletionReport'])->name('cutting-order.cutting-completion-report');
});

Route::group(['middleware' => ['auth']], function () {

    // !! delete this unsed route
    // Route::get('daily-cutting-detail', [DailyCuttingReportsController::class,'dailyCuttingDetail'])->name('daily-cutting.detail');

    Route::group(['middleware' => ['hasAnyPermission:cutting-record,status-cutting-order-record.access']], function() {
        Route::get('status-cutting-order-record', [CuttingOrdersController::class,'statusCuttingOrderRecord'])->name('cutting-order.status-cutting-order-record');
        Route::get('print-status-cutting-order-record', [CuttingOrdersController::class,'printStatusCuttingOrderRecord'])->name('cutting-order.print-status-cutting-order-record');
    });

    Route::group(['middleware' => ['hasAnyPermission:cutting-record,cutting-group-report.access']], function() {
        Route::get('cutting-group-report', [CuttingGroupReportController::class,'index'])->name('cutting-group-report.index');
        Route::get('cutting-group-report/print', [CuttingGroupReportController::class,'print'])->name('cutting-group-report.print');
    });

    // Route::get('cutting-order-completion', [CuttingOrdersController::class,'cuttingCompletion'])->name('cutting-order.cutting-completion');

    // Route::get('cutting-order-completion-report', [CuttingOrdersController::class,'cuttingCompletionReport'])->name('cutting-order.cutting-completion-report');
    Route::group(['middleware' => ['hasAnyPermission:cutting-record,tracking-fabric-usage.access']], function() {
        Route::get('tracking-fabric-usage', [FabricIssuesController::class,'trackingFabricUsage'])->name('fabric-issue.tracking-fabric-usage');
        Route::get('tracking-fabric-usage-report', [FabricIssuesController::class,'trackingFabricUsageReport'])->name('fabric-issue.tracking-fabric-usage-report');
    });

    Route::group(['middleware' => ['hasAnyPermission:cutting-record,cutting-output-report.access']], function() {
        Route::get('cutting-output-report', [CuttingOutputReportController::class,'index'])->name('cutting-output-report.index');
        Route::get('cutting-output-report-print', [CuttingOutputReportController::class,'print'])->name('cutting-output-report.print');
    });
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

Route::middleware(['auth','can:admin-only'])->controller(UpdateDatabasesController::class)->prefix('update-database')->name('update-database.')->group(function(){
    route::get('', 'index')->name('index');
    route::get('cor-cut-datetime', 'update_cor_cut_datetime')->name('cor-cut-datetime');
});
