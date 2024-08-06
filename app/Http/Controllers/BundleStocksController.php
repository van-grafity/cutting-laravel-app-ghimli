<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gl;
use App\Models\LayingPlanning;
use App\Models\BundleStock;
use App\Models\BundleStockTransaction;
use App\Models\BundleStockTransactionGroup;
use App\Models\BundleTransferNote;
use App\Models\BundleTransferNoteDetail;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingTicket;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use PDF;

use Illuminate\Support\Facades\Redirect;

class BundleStocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bundle_stock_list = BundleStock::get();
        $data = [
            'bundle_stock_list' => $bundle_stock_list,
        ];
        return view('page.bundle-stock.index', $data);
    }


    public function dataBundleStock()
    {
        $query = DB::table('bundle_stocks')
            ->join('laying_plannings', 'laying_plannings.id', '=', 'bundle_stocks.laying_planning_id')
            ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->join('sizes', 'sizes.id', '=', 'bundle_stocks.size_id')
            ->groupBy('bundle_stocks.laying_planning_id')
            ->orderBy('gls.gl_number')
            ->select('laying_plannings.id as laying_planning_id','gls.id as gl_id','gls.gl_number', 'colors.color', DB::raw('SUM(bundle_stocks.current_qty) as total'))
            ->get();

            return Datatables::of($query)
                ->escapeColumns([])
                ->addColumn('action', function($data){
                    $action = '';
                    $action .= '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1" onclick="detail_stock('. $data->laying_planning_id .')" data-toggle="tooltip" data-placement="top" title="Detail" >Detail</a>';
                    if(auth()->user()->can('admin-only')){
                        $action .= ' <a href="'. route('bundle-stock.refresh-stock', $data->laying_planning_id) .'" class="btn btn-secondary btn-sm mb-1" data-toggle="tooltip" data-placement="top" title="Refresh Stock" >Refresh Stock</a>';
                    }
                    return $action;
                })
                ->addIndexColumn()
                ->make(true);
    }

    public function detail(Request $request)
    {
        try {
            $laying_planning_id = $request->laying_planning_id;
            $detail_stock = BundleStock::join('laying_plannings','laying_plannings.id','=', 'bundle_stocks.laying_planning_id')
                ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
                ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
                ->join('sizes', 'sizes.id', '=', 'bundle_stocks.size_id')
                ->where('laying_planning_id', $laying_planning_id)
                ->select('gls.gl_number','colors.color','sizes.size','bundle_stocks.current_qty')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully Get Data Stock',
                'data' => [
                    'detail_stock' => $detail_stock,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function stockIn(){
        $location = DB::table('bundle_locations')
                    ->where('location', ['Cutting'])
                    ->first();
        return view('page.bundle-stock.stock-in', compact('location'));
    }

    public function stockOut(){
        $location = DB::table('bundle_locations')
                    ->whereNotIn('location', ['Cutting'])
                    ->get();
        return view('page.bundle-stock.stock-out', compact('location'));
    }

    private function getSuccessMessage($transaction_type)
    {
        if($transaction_type == 'IN') {
            $message = "Berhasil memasukkan bundle ke dalam rack";
        } else if($transaction_type == 'OUT') {
            $message = "Berhasil mengeluarkan bundle dari rack";
        } else {
            $message = "No Message Provided";
        }
        return $message;
    }
    private function generateResponses($bundle_stock_transaction, $transaction_type, $cutting_ticket): array
    {
        /*
        * Alias:
        * a. $bundle_stock_transaction = BUNDLE
        * b. $transaction_type = TIPE PARAMS

        * The Rules :
        * 1. BUNDLE tidak ada di rack dan TIPE nya "IN" => ✔
        * 2. BUNDLE tidak ada di rack dan TIPE nya "OUT" => ✖
        * 3. BUNDLE ada di rack. Ambil data transaksi terakhir BUNDLE. jika BUNDLE->transaction_type nya berbeda dengan TIPE => ✔
        * 4. BUNDLE ada di rack. Ambil data transaksi terakhir BUNDLE. jika BUNDLE->transaction_type nya sama dengan TIPE => ✖
        */

        $success_condition = [
            'status' => "success",
            'message' => 'Go Ahead!'
        ];

        // ## Kondisi ketika ticket belum distock in tapi sudah stock out
        if (!$bundle_stock_transaction) {
            return ($transaction_type == 'OUT') ?
                [
                    'status'=> 'error',
                    'serial_number' => $cutting_ticket->serial_number,
                ] :
                $success_condition;
        }

        // ## Kondisi ketika ticket sudah stock in tidak bisa stock in lagi dan sebaliknya
        return ($bundle_stock_transaction->transaction_type == $transaction_type) ?
            [
                'status'=> 'error',
                'data' => 'exist',
                'serial_number' => $bundle_stock_transaction->cuttingTicket->serial_number,
            ] :
            $success_condition;
    }

    public function storeMultiple(Request $request)
    {
        $data_input = $request->all();
        if(!$data_input['serial_number']){
            return response()->json([
                'status'=> 'error',
                'message' => "Masukkan setidaknya satu Cutting Ticket"
            ]);
        };

        if(!$data_input['location']){
            return response()->json([
                'status'=> 'error',
                'message' => "Location Tidak Boleh Kosong"
            ]);
        }

        $ticket_list = CuttingTicket::whereIn('serial_number', $data_input['serial_number'])->get();
        // ## Check bundle is inside rack or not.
        $bundle_checking_responses = $this->checkMultipleBundleOnRack($ticket_list, $data_input['transaction_type']);
        $error_serial_numbers = [];
        $bundle_stock_serial_numbers = [];
        $bundle_stock_exist = null;
        $transaction_type_message = ($data_input['transaction_type'] == 'IN') ? ' Sudah ada di dalam rack' : ' Sudah keluar dari rack';
        foreach($bundle_checking_responses as $response){
            if(isset($response['data']) && $response['data'] === 'exist'){
                $bundle_stock_serial_numbers[] = $response['serial_number'];
                $bundle_stock_exist = $response['data'];
            }else if($response['status'] === 'error'){
                $error_serial_numbers[] = $response['serial_number'];
            }
        }

        if(!empty($error_serial_numbers || $bundle_stock_serial_numbers)){
            if($bundle_stock_exist){
                return response()->json([
                    'status'=> 'error',
                    'message' => 'Bundle dengan nomor ticket ' . implode(', ', $bundle_stock_serial_numbers) . $transaction_type_message,
                ]);
            }else{
                return response()->json([
                    'status'=> 'error',
                    'message' => 'Bundle dengan nomor ticket ' . implode(', ', $error_serial_numbers) . "  tidak ada di dalam rack",
                ]);
            }
        }

        try {
            $new_bundle_list = [];
            $bundle_stock_list = [];

            DB::beginTransaction();
            if($ticket_list){
                $bundle_transaction_detail = new BundleStockTransactionGroup();
                $bundle_transaction_detail->serial_number = $this->generate_bundle_transaction_detail_serial_number();
                $bundle_transaction_detail->transaction_type = $data_input['transaction_type'];
                $bundle_transaction_detail->location_id = $data_input['location'];;
                $bundle_transaction_detail->save();
            }

            foreach ($ticket_list as $key => $ticket) {
                // ## Create New Bundle Transaction Detail
                $bundle_stock_transaction = new BundleStockTransaction;
                $bundle_stock_transaction->transaction_group_id = $bundle_transaction_detail->id;
                $bundle_stock_transaction->ticket_id = $ticket->id;
                $bundle_stock_transaction->transaction_type = $data_input['transaction_type'];
                $bundle_stock_transaction->location_id = $data_input['location'];;
                $bundle_stock_transaction->save();

                $new_bundle_list[] = BundleStockTransaction::where('id',$bundle_stock_transaction->id)->with('cuttingTicket')->first();
                $bundle_stock_list[] = $this->updateBundleStock($bundle_stock_transaction);
            }

            if($data_input['transaction_type'] == "OUT"){
                $this->createTransferNote($new_bundle_list, $data_input['location']);
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
        }

        $message = $this->getSuccessMessage($data_input['transaction_type']);
        $data_return = [
            'status' => "success",
            'new_bundle' => $new_bundle_list,
            'bundle_stock' => $bundle_stock_list,
            'message' => $message,
        ];
        return response()->json($data_return);
    }

    private function checkMultipleBundleOnRack($cutting_tickets, $transaction_type): array
    {
        $ticket_ids = Arr::pluck($cutting_tickets, 'id');

        $bundle_stock_transactions = BundleStockTransaction::whereIn('ticket_id', $ticket_ids)
            ->orderBy('ticket_id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $response = [];

        foreach ($cutting_tickets as $cutting_ticket) {
            $ticket_id = $cutting_ticket->id;

            $bundle_stock_transaction = $bundle_stock_transactions->firstWhere('ticket_id', $ticket_id);

            $result = $this->generateResponses($bundle_stock_transaction, $transaction_type, $cutting_ticket);

            $response[$ticket_id] = $result;
        }
        return $response;
    }

    // private function checkTicketlist(Array $ticket_serial_number_list)
    // {
    //     foreach ($ticket_serial_number_list as $key => $serial_number) {
    //         $cutting_ticket = CuttingTicket::where('serial_number', $serial_number)->first();
    //         if(!$cutting_ticket) {
    //             $checking_result = [
    //                 'status' => 'error',
    //                 'message' => 'Bundle dengan nomor Tiket ' . $serial_number . ' tidak ditemukan'
    //             ];
    //             return $checking_result;
    //         }
    //     }
    //     $checking_result = [
    //         'status' => 'success',
    //         'message' => 'All Ticket are Valid'
    //     ];
    //     return $checking_result;
    // }



    private function createTransferNote($bundle_transaction_list, $location_id)
    {
        // ## Create New Bundle Transfer Note
        $transfer_note = new BundleTransferNote;
        $transfer_note->serial_number = $this->generate_transfer_note_serial_number();
        $transfer_note->location_from_id = 1;
        $transfer_note->location_to_id = $location_id;
        $transfer_note->save();

        // ## Create Bundle Transfer Note Details
        $this->createTransferNoteDetail($bundle_transaction_list, $transfer_note->id);
    }

    private function createTransferNoteDetail($bundle_transaction_list, $transfer_note_id)
    {
        foreach ($bundle_transaction_list as $key => $bundle_transaction) {
            $transfer_note_detail = new BundleTransferNoteDetail;
            $transfer_note_detail->bundle_transfer_note_id = $transfer_note_id;
            $transfer_note_detail->bundle_transaction_id = $bundle_transaction->id;
            $transfer_note_detail->save();
        }
    }

    private function generate_transfer_note_serial_number()
    {
        $this_year = date('Y');
        $this_month = date('m');
        $time_code = date('ym');

        $count_transfer_note_this_month = BundleTransferNote::whereYear('created_at',$this_year)->whereMonth('created_at',$this_month)->get()->count();

        if($count_transfer_note_this_month) {
            $next_number = $count_transfer_note_this_month + 1;
        } else {
            $next_number = 1;
        }
        $transfer_note_number = Str::padLeft($next_number, 4, '0');
        $serial_number = "CPTN-{$time_code}-{$transfer_note_number}";
        return $serial_number;
    }

    public function searchTicket(Request $request)
    {
        try {
            $data_input = $request->all();
            $cutting_ticket = CuttingTicket::with([
                'cuttingOrderRecord.layingPlanningDetail.layingPlanning.color',
                'cuttingOrderRecord.layingPlanningDetail.layingPlanning.buyer',
                'size'
            ])
            ->select('id', 'serial_number', 'cutting_order_record_id', 'size_id', 'layer', 'ticket_number')
            ->where('serial_number', $data_input['serial_number'])
            ->first();

            if(!$cutting_ticket) {
                    $checking_result = [
                        'status' => 'error',
                        'message' => 'Bundle dengan nomor Tiket ' .  $data_input['serial_number'] . ' tidak ditemukan'
                    ];
                    return $checking_result;
            }

            $checking_result = [
                'status' => 'success',
                'message' => "Berhasil menambah cutting ticket pada tabel",
                'data' => $cutting_ticket,
            ];
            return $checking_result;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    private function updateBundleStock($bundle_stock_transaction) : array
    {
        // ## Update Bundle Stock in Rack
        $laying_planning_id = $bundle_stock_transaction->cuttingTicket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->id;
        $size_id = $bundle_stock_transaction->cuttingTicket->size_id;
        $cut_piece_qty = $bundle_stock_transaction->cuttingTicket->layer;

        $bundle_stock = BundleStock::where('laying_planning_id', $laying_planning_id)
            ->where('size_id', $size_id)
            ->first();

        if (!$bundle_stock){
            $bundle_stock = new BundleStock;
            $bundle_stock->laying_planning_id = $laying_planning_id;
            $bundle_stock->size_id = $size_id;
            $bundle_stock->current_qty = $cut_piece_qty;
        } else {
            if($bundle_stock_transaction->transaction_type == 'IN') {
                $bundle_stock->current_qty = $bundle_stock->current_qty + $cut_piece_qty;
            } else if ($bundle_stock_transaction->transaction_type == 'OUT'){
                $bundle_stock->current_qty = $bundle_stock->current_qty - $cut_piece_qty;
            }
        }
        $bundle_stock->save();

        $bundle_stock_information = [
            'gl_number' => $bundle_stock->layingPlanning->gl->gl_number,
            'color' => $bundle_stock->layingPlanning->color->color,
            'size' => $bundle_stock->size->size,
            'current_qty' => $bundle_stock->current_qty,
        ];

        return $bundle_stock_information;
    }

    public function filter()
    {
        $gls = Gl::select('id', 'gl_number')->get();
        return view('page.bundle-stock.filter', compact('gls'));
    }

    public function print(Request $request)
    {
        $gl_id = $request->gl_id;
        $gl = Gl::find($gl_id);
        $filename = 'Cut Piece Stock #GL'.$gl->gl_number.'.pdf';
        $gl_number = 'GL-' . $gl->gl_number;

        $stock_item_list = $this->getStocItemkList($gl_id); // ## this is laying plannings

        $laying_planning_id_list = array_column($stock_item_list, 'laying_planning_id');
        $size_list = $this->getSizeList($laying_planning_id_list);
        $total_size = count($size_list);

        // ## get qty per stock item
        foreach ($stock_item_list as $key => $item) {
            $qty_per_size = $this->getQtyPerSize($item['laying_planning_id'], $size_list);
            $stock_item_list[$key]['qty_per_size'] = $qty_per_size;

            $total_qty_all_size = array_reduce($qty_per_size, function ($total_qty, $size) {
                return $total_qty + $size['qty'];
            }, 0);
            $stock_item_list[$key]['total_qty_all_size'] = $total_qty_all_size;
        }

        $data = [
            'gl_number' => $gl_number,
            'filename' => $filename,
            'stock_item_list' => $stock_item_list,
            'size_list' => $size_list,
            'total_size' => $total_size,
        ];

        // return view('page.bundle-stock.report', $data);

        $pdf = PDF::loadView('page.bundle-stock.report', $data);
        return $pdf->stream($filename);
    }

    private function getStocItemkList($gl_id = null) : array
    {
        if(!$gl_id) { return []; }
        return LayingPlanning::where('gl_id', $gl_id)
            ->join('colors','colors.id','=','laying_plannings.color_id')
            ->join('gls','gls.id','=','laying_plannings.gl_id')
            ->select('laying_plannings.id as laying_planning_id','laying_plannings.serial_number as laying_planning_number','gls.gl_number','colors.color')
            ->get()->toArray();
    }

    private function getSizeList(Array $laying_planning_id_list) : array
    {
        return LayingPlanning::whereIn('laying_plannings.id',$laying_planning_id_list)
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')
            ->join('laying_planning_detail_sizes','laying_planning_detail_sizes.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('sizes','sizes.id','=','laying_planning_detail_sizes.size_id')
            ->groupBy('laying_planning_detail_sizes.size_id')
            ->select('sizes.id','sizes.size')
            ->get()->toArray();
    }

    private function getQtyPerSize($laying_planning_id, $size_list)
    {
        $getBundleStock = LayingPlanning::where('laying_plannings.id', $laying_planning_id)
            ->join('bundle_stocks','bundle_stocks.laying_planning_id','=','laying_plannings.id')
            ->select('bundle_stocks.*')
            ->get()->toArray();


        if(!$getBundleStock){
            // ## Jika tidak ada ticket dari laying planning ini yang tersimpan di rack, berarti semua size stocknya 0
            foreach ($size_list as $key => $size) {
                $size_list[$key]['qty'] = 0;
            }
        } else {
            /*
            * Jika ada ticket dari laying planning ini yang tersimpan di rack.
            * Melakukan pengecekkan untuk semua size list. lalu ambil current qty dari size yang berkaitan. => using array_filter
            * jika ada size tidak di temukan di rack. Maka qty = 0
             */
            foreach ($size_list as $key => $size) {
                $size_id = $size['id'];
                $filter_result = array_filter($getBundleStock, function ($bundle) use ($size_id) {
                    return $bundle['size_id'] === $size_id;
                });

                if($filter_result){
                    $size_list[$key]['qty'] = reset($filter_result)['current_qty'];
                } else {
                    $size_list[$key]['qty'] = 0;
                }
            }
        }
        return $size_list;
    }

    public function refreshStock(Request $request, $laying_planning_id)
    {
        // ## Get all tickets that have stocked in
        $stocked_in_tickets = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->where('laying_planning_details.laying_planning_id', $laying_planning_id)
            ->where('bundle_stock_transactions.transaction_type','IN')
            ->select('cutting_tickets.*')
            ->get();
        
        $stocked_in_tickets_grouped = $stocked_in_tickets->groupBy('size_id')->map(function ($item) {
            return $item->sum('layer');
        });


        // ## Get all tickets that have stocked out
        $stocked_out_tickets = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->where('laying_planning_details.laying_planning_id', $laying_planning_id)
            ->where('bundle_stock_transactions.transaction_type','OUT')
            ->select('cutting_tickets.*')
            ->get();
        
        $stocked_out_tickets_grouped = $stocked_out_tickets->groupBy('size_id')->map(function ($item) {
            return $item->sum('layer');
        });

        $current_qty_each_size = [];
        foreach ($stocked_in_tickets_grouped as $size => $accumulate_stock_in) {
            $current_qty_each_size[$size] = $accumulate_stock_in - ($stocked_out_tickets_grouped[$size] ?? 0);
        }

        
        // ## Get Bundle Stock base on Laying Planning. this will result stock per size of planning
        $bundle_stock_each_size = BundleStock::where('laying_planning_id', $laying_planning_id)->get();
        foreach ($bundle_stock_each_size as $size_stock) {
            $size_stock->current_qty = $current_qty_each_size[$size_stock->size_id] ?? 0;
            $size_stock->save();
        }
        
        $bundle_stock_list = BundleStock::get();
        $data = [
            'bundle_stock_list' => $bundle_stock_list,
        ];
        return redirect()->route('bundle-stock.index')->with('success','Cut Piece Stock Refreshed Succesfully');
    }
    
    private function createBundleTransactionDetail($new_bundle_list, $bundle_location, $bundle_transaction_type)
    {
        foreach( $new_bundle_list as $key => $bundle) {
            $bundle_transaction_detail = new BundleStockTransactionGroup();
            $bundle_transaction_detail->bundle_transaction_id = $bundle->id;
            $bundle_transaction_detail->serial_number = $this->generate_bundle_transaction_detail_serial_number($bundle->id);
            $bundle_transaction_detail->transaction_type = $bundle_transaction_type;
            $bundle_transaction_detail->location_id = $bundle_location;
            $bundle_transaction_detail->save();
        };
    }

    private function generate_bundle_transaction_detail_serial_number()
    {
        $this_year = date('Y');
        $this_month = date('m');
        $time_code = date('ym');

        $count_bundle_stock_this_month = BundleStockTransactionGroup::whereYear('created_at',$this_year)->whereMonth('created_at',$this_month)->withTrashed()->get()->count();

        if($count_bundle_stock_this_month) {
            $next_number = $count_bundle_stock_this_month + 1;
        } else {
            $next_number = 1;
        }
        $bundle_stock_number = Str::padLeft($next_number, 4, '0');
        $serial_number = "BST-{$time_code}-{$bundle_stock_number}";
        return $serial_number;
    }

    public function transactionHistory()
    {
        return view('page.bundle-stock.transaction-history');
    }

    public function dtableTransaction()
    {
        $datas = BundleStockTransaction::join('bundle_stock_transaction_groups', 'bundle_stock_transactions.transaction_group_id', '=', 'bundle_stock_transaction_groups.id')
                    ->join('bundle_locations', 'bundle_locations.id', '=', 'bundle_stock_transaction_groups.location_id')
                    ->join('cutting_tickets', 'cutting_tickets.id', '=', 'bundle_stock_transactions.ticket_id')
                    ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
                    ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
                    ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
                    ->join('colors','colors.id','=','laying_plannings.color_id')
                    ->join('gls','gls.id','=','laying_plannings.gl_id')
                    ->whereNotNull('transaction_group_id')
                    ->groupBy('bundle_stock_transaction_groups.id')
                    ->select('bundle_locations.location as location','bundle_stock_transaction_groups.*')
                    ->withTrashed();

        if(request()->has('filter_type') && request()->input('filter_type') !== 0){
            $filterType = request()->input('filter_type');
            if(!Auth::user()->hasRole('super_admin') || $filterType === 'non_deleted'){
                $datas->where('bundle_stock_transaction_groups.deleted_at', null);
            }
            if($filterType === 'soft_deleted'){
                $datas->whereNotNull('bundle_stock_transaction_groups.deleted_at');
            }
        }

        $query = $datas->get();

        return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('gl_number', function($data){
                return $this->getGlFromBundleTransaction($data->id);
            })->addColumn('color', function($data){
                return $this->getColorFromBundleTransaction($data->id);
            })->addColumn('date', function($data){
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i');
            })->addColumn('total_pcs', function($data){
                return $this->getTotalPcsFromBundleTransaction($data->id);
            })->addColumn('action', function($data){
                $action = "";
                $filterType = request()->input('filter_type');
                $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at);

                $differenceInMinutes = Carbon::now()->diffInMinutes($createdAt);

                $action .= '<a href="'.route('bundle-stock.detail-transaction-history', $data->id).'" class="btn btn-info btn-sm mb-1 mr-1" data-toggle="tooltip" data-placement="top" title="Detail" target="_blank">Detail</a>';
                if (Auth::user()->hasRole('super_admin')) {
                    if($filterType === 'soft_deleted' || $filterType === 'non_deleted'){
                        if ($differenceInMinutes <= 30) {
                            $action .= "<button onclick='delete_bundle_stock_transaction($data->id, `$filterType`)' class='btn btn-danger btn-sm mb-1 mr-1' data-toggle='tooltip' data-placement='top' title='Delete'>Delete</button>";
                        } else {
                            $action .= "<button class='btn btn-danger btn-sm mb-1 mr-1' data-toggle='tooltip' data-placement='top' title='Data tidak dapat di delete karena sudah disimpan selama 30 menit' disabled>Delete</button>";
                        }
                    }
                }
                return $action;
            })->addIndexColumn()
            ->make(true);
   }

   public function deleteBundleTransaction($id)
   {
        $bundle_stock_transaction_detail = BundleStockTransactionGroup::onlyTrashed()->find($id);

        if(!$bundle_stock_transaction_detail){
            return response()->json([
                'status'=> 'error',
                'message' => "Bundle Stock Transaction History Tidak Ditemukan"
            ]);
        }
        $bundle_stock_transaction_detail_serial_number = $bundle_stock_transaction_detail->serial_number;
        $bundle_stock_transaction = BundleStockTransaction::where('transaction_group_id', $bundle_stock_transaction_detail->id)
            ->onlyTrashed()
            ->get();
        if($bundle_stock_transaction->isEmpty()){
            return response()->json([
                'status'=> 'error',
                'message' => "Bundle Stock Tidak Ditemukan"
            ]);
        }

        try{
            DB::beginTransaction();
            foreach ($bundle_stock_transaction as $transaction) {
                $bundle_transfer_note_details = BundleTransferNoteDetail::where('bundle_transaction_id', $transaction->id)->first();
                if ($bundle_transfer_note_details) {
                    BundleTransferNoteDetail::where('bundle_transfer_note_id', $bundle_transfer_note_details->bundle_transfer_note_id)->delete();
                    BundleTransferNote::where('id', $bundle_transfer_note_details->bundle_transfer_note_id)->onlyTrashed()->forceDelete();
                    $bundle_transfer_note_details->forceDelete();
                }
                $transaction->forceDelete();
            }
            $bundle_stock_transaction_detail->forceDelete();
            DB::commit();
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
        }
        return response()->json([
            'status'=> 'success',
            'message'=> 'Bundle Stock Transaction Dengan Serial Number '. $bundle_stock_transaction_detail_serial_number .' Sudah Berhasil Dihapus'
        ]);
   }

   public function softDeleteBundleTransaction($id)
   {
        $bundle_stock_transaction_detail = BundleStockTransactionGroup::find($id);
        if(!$bundle_stock_transaction_detail){
            return response()->json([
                'status'=> 'error',
                'message' => "Bundle Stock Transaction History Tidak Ditemukan"
            ]);
        }
        $bundle_stock_transaction_detail_serial_number = $bundle_stock_transaction_detail->serial_number;
        $bundle_stock_transaction = BundleStockTransaction::where('transaction_group_id', $bundle_stock_transaction_detail->id)
            ->get();

        if($bundle_stock_transaction->isEmpty()){
            return response()->json([
                'status'=> 'error',
                'message' => "Bundle Stock Tidak Ditemukan"
            ]);
        }

        try{
            DB::beginTransaction();
            foreach ($bundle_stock_transaction as $transaction) {
                $this->updateStockFromTransactionHistory($transaction, $bundle_stock_transaction_detail->transaction_type);

                $bundle_transfer_note_details = BundleTransferNoteDetail::where('bundle_transaction_id', $transaction->id)->first();
                if ($bundle_transfer_note_details) {
                    BundleTransferNoteDetail::where('bundle_transfer_note_id', $bundle_transfer_note_details->bundle_transfer_note_id);
                    BundleTransferNote::where('id', $bundle_transfer_note_details->bundle_transfer_note_id)->delete();
                }
                $transaction->delete();
            }
            $bundle_stock_transaction_detail->delete();
            DB::commit();
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
        }
        return response()->json([
            'status'=> 'success',
            'message'=> 'Bundle Stock Transaction Dengan Serial Number '. $bundle_stock_transaction_detail_serial_number .' Sudah Berhasil Dihapus'
        ]);
   }

   private function updateStockFromTransactionHistory($bundle_stock_transaction, $transaction_type)
    {
        // ## Update Bundle Stock in Rack
        $laying_planning_id = $bundle_stock_transaction->cuttingTicket->cuttingOrderRecord->layingPlanningDetail->layingPlanning->id;
        $size_id = $bundle_stock_transaction->cuttingTicket->size_id;
        $cut_piece_qty = $bundle_stock_transaction->cuttingTicket->layer;

        $bundle_stock = BundleStock::where('laying_planning_id', $laying_planning_id)
            ->where('size_id', $size_id)
            ->first();

        if($bundle_stock_transaction->transaction_type == 'OUT') {
            $bundle_stock->current_qty = $bundle_stock->current_qty + $cut_piece_qty;
        } else if ($bundle_stock_transaction->transaction_type == 'IN'){
            $bundle_stock->current_qty = $bundle_stock->current_qty - $cut_piece_qty;
        }

        $bundle_stock->save();

        $bundle_stock_information = [
            'gl_number' => $bundle_stock->layingPlanning->gl->gl_number,
            'color' => $bundle_stock->layingPlanning->color->color,
            'size' => $bundle_stock->size->size,
            'current_qty' => $bundle_stock->current_qty,
        ];

        return $bundle_stock_information;
    }

   private function getColorFromBundleTransaction($transaction_group_id)
   {
        $bundle_stock_transaction = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('colors','colors.id','=','laying_plannings.color_id')
            ->where('transaction_group_id',$transaction_group_id)
            ->groupBy('colors.id')
            ->withTrashed()
            ->select('colors.color')->get()->toArray();

        $color_list = array_column($bundle_stock_transaction,'color');
        $color_list = implode(', ',$color_list);
        return $color_list;
   }

   private function getGlFromBundleTransaction($transaction_group_id)
   {
        $bundle_stock_transaction = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('gls','gls.id','=','laying_plannings.gl_id')
            ->where('transaction_group_id',$transaction_group_id)
            ->groupBy('gls.id')
            ->withTrashed()
            ->select('gls.gl_number')->get()->toArray();

        $gl_number_list = array_column($bundle_stock_transaction,'gl_number');
        $gl_number_list = implode(', ',$gl_number_list);
        return $gl_number_list;
   }

   private function getStyleFromBundleTransaction($transaction_group_id)
   {
        $bundle_stock_transaction = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('cutting_order_records','cutting_order_records.id','=','cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
            ->join('styles','styles.id','=','laying_plannings.style_id')
            ->where('transaction_group_id',$transaction_group_id)
            ->groupBy('styles.id')
            ->withTrashed()
            ->select('styles.style as style')->get()->toArray();

        $style_list = array_column($bundle_stock_transaction,'style');
        $style = implode(', ',$style_list);
        return $style;
   }

   private function getTotalPcsFromBundleTransaction($transaction_group_id)
   {
       $bundle_stock_transaction = BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
           ->where('transaction_group_id',$transaction_group_id)
           ->withTrashed()
           ->select('cutting_tickets.layer')->get()->toArray();

       $layer_list = array_column($bundle_stock_transaction,'layer');
       $total_pcs = array_sum($layer_list);
       return $total_pcs;
   }


    public function syncBundleTransaction()
    {
        $transfer_note_details = BundleTransferNoteDetail::join('bundle_stock_transactions', 'bundle_stock_transactions.id', '=', 'bundle_transfer_note_details.bundle_transaction_id')
                    ->select([
                        'bundle_transfer_note_details.*',
                        'bundle_stock_transactions.transaction_type as transaction_type'
                    ])
                    ->get();

        foreach($transfer_note_details as $transfer_note_detail){
            BundleTransferNote::where('id',$transfer_note_detail->bundle_transfer_note_id)
                             ->update(['transaction_type' => $transfer_note_detail->transaction_type]);
        }

        return redirect()->back()->with('success','Sync Succesfully');
    }

    public function detailTransactionHistory($id)
    {
        $bundle_stock_detail = BundleStockTransactionGroup::with('bundleLocation', 'bundleStockTransaction')
        ->withTrashed()
        ->find($id);

        $bundle_stock_header =[
            'bundle_stock_transaction_id' => $bundle_stock_detail->id,
            'serial_number' => $bundle_stock_detail->serial_number,
            'transaction_type' => $bundle_stock_detail->transaction_type,
            'location' => $bundle_stock_detail->bundleLocation->location,
            'total_stock' => $bundle_stock_detail->bundleStockTransaction()->withTrashed()->count(),
            'style_no' => $this->getStyleFromBundleTransaction($bundle_stock_detail->id),
            'gl_number' =>$this->getGlFromBundleTransaction($bundle_stock_detail->id),
            'date' => $bundle_stock_detail->created_at->format('d-m-Y'),
        ];
        return view('page.bundle-stock.detail')->with('bundle_stock_header', $bundle_stock_header);;
    }

    private function getBundleStockSize($transaction_group_id)
    {
        return BundleStockTransaction::join('cutting_tickets','cutting_tickets.id','=','bundle_stock_transactions.ticket_id')
            ->join('sizes', 'sizes.id', '=', 'cutting_tickets.size_id')
            ->groupBy('sizes.id')
            ->select('sizes.id','sizes.size')
            ->where('transaction_group_id', $transaction_group_id)
            ->orderBy('sizes.id', 'ASC')
            ->withTrashed()
            ->get();
    }

    public function dtableTicketList($transaction_group_id)
    {
        $bundle_stock_detail = BundleStockTransactionGroup::with('bundleLocation', 'bundleStockTransaction')
            ->withTrashed()
            ->find($transaction_group_id);

        $bundle_stock_transaction = BundleStockTransaction::join('cutting_tickets', 'cutting_tickets.id', '=', 'bundle_stock_transactions.ticket_id')
            ->join('sizes','sizes.id','=', 'cutting_tickets.size_id')
            ->join('cutting_order_records','cutting_order_records.id','=', 'cutting_tickets.cutting_order_record_id')
            ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
            ->join('laying_plannings', 'laying_plannings.id', '=', 'laying_planning_details.laying_planning_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->join('gls', 'gls.id', "=", 'laying_plannings.gl_id')
            ->join('buyers', 'buyers.id', '=', 'gls.buyer_id')
            ->where('transaction_group_id', $bundle_stock_detail->id)
            ->select('gls.gl_number as gl_number','buyers.name as buyer_name','cutting_tickets.ticket_number','cutting_tickets.serial_number','cutting_tickets.layer','sizes.id as size_id', 'sizes.size as size', 'colors.color as color', 'cutting_tickets.table_number as table_number', 'cutting_tickets.layer as layer')
            ->orderBy('sizes.id', 'ASC')
            ->withTrashed()
            ->get();


        return Datatables::of($bundle_stock_transaction)
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
}
