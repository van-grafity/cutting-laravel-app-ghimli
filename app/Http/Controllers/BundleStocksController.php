<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gl;
use App\Models\LayingPlanning;
use App\Models\BundleStock;
use App\Models\BundleStockTransaction;
use App\Models\BundleTransferNote;
use App\Models\BundleTransferNoteDetail;
use App\Models\CuttingTicket;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

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
                $action = '<a href="javascript:void(0)" class="btn btn-info btn-sm mb-1" onclick="detail_stock('. $data->laying_planning_id .')" data-toggle="tooltip" data-placement="top" title="Detail" >Detail</a>';
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
        $location = DB::table('bundle_locations')->get();
        return view('page.bundle-stock.stock-in', compact('location'));
    }

    public function stockOut(){
        $location = DB::table('bundle_locations')->get();
        return view('page.bundle-stock.stock-out', compact('location'));
    }


    private function checkSingleBundleOnRack($transaction_type, $cutting_ticket)
    {
        $bundle_stock_transaction = BundleStockTransaction::where('ticket_id', $cutting_ticket->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $result = $this->generateResponses($bundle_stock_transaction, $transaction_type, $cutting_ticket);

        return $result;
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

        if (!$bundle_stock_transaction) {
            return ($transaction_type == 'OUT') ?
                ['status' => "error", 'message' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' tidak ada di dalam rack'] :
                $success_condition;
        }

        $message = ($transaction_type == 'IN') ? 'Sudah ada di dalam rack' : 'Sudah keluar dari rack';

        return ($bundle_stock_transaction->transaction_type == $transaction_type) ?
            ['status' => 'error' , 'message' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' ' . $message] :
            $success_condition;
    }

    public function storeMultiple(Request $request)
    {
        $data_input = $request->all();
        $checked_tickets = $this->checkTicketlist($data_input['serial_number']);

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

        if($checked_tickets['status'] == 'error'){
            $data_return = [
                'status' => 'error',
                'message' => $checked_tickets['message'],
            ];
            return response()->json($data_return);
        }

        $ticket_list = CuttingTicket::whereIn('serial_number', $data_input['serial_number'])->get();
        // ## Check bundle is inside rack or not.
        $bundle_checking_responses = $this->checkMultipleBundleOnRack($ticket_list, $data_input['transaction_type']);

        // ## Get Only Error Bundle
        $error_bundles = array_filter($bundle_checking_responses, function ($response) {
            return isset($response['status']) && $response['status'] === 'error';
        });

        if($error_bundles){
            // ## Get First Error
            $first_error_bundle = reset($error_bundles);
            return response()->json($first_error_bundle);

        }

        try {
            $new_bundle_list = [];
            $bundle_stock_list = [];

            DB::beginTransaction();
            foreach ($ticket_list as $key => $ticket) {
                // ## Create New Bundle Transaction
                $bundle_stock_transaction = new BundleStockTransaction;
                $bundle_stock_transaction->ticket_id = $ticket->id;
                $bundle_stock_transaction->transaction_type = $data_input['transaction_type'];
                $bundle_stock_transaction->location_id = $data_input['location'];
                $bundle_stock_transaction->save();

                $new_bundle_list[] = BundleStockTransaction::where('id',$bundle_stock_transaction->id)->with('cuttingTicket')->first();
                $bundle_stock_list[] = $this->updateBundleStock($bundle_stock_transaction);
            }
            if($data_input['transaction_type'] == 'OUT'){
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

    private function checkTicketlist(Array $ticket_serial_number_list)
    {
        foreach ($ticket_serial_number_list as $key => $serial_number) {
            $cutting_ticket = CuttingTicket::where('serial_number', $serial_number)->first();
            if(!$cutting_ticket) {
                $checking_result = [
                    'status' => 'error',
                    'message' => 'Bundle dengan nomor Tiket ' . $serial_number . ' tidak ditemukan'
                ];
                return $checking_result;
            }
        }
        $checking_result = [
            'status' => 'success',
            'message' => 'All Ticket are Valid'
        ];
        return $checking_result;
    }

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



    // public function search_serial_number(String $id){
    //     try{
    //         $ticket = CuttingTicket::where('serial_number', $id)->first();
    //         if($ticket == null){
    //             return response()->json([
    //                 'status'=> 'error',
    //                 'message'=> 'Cutting Ticket Dengan Serial Number'. $id . 'Tidak Ditemukan'
    //             ]);
    //         };

    //         $checkBundle = $this->checkSingleBundleOnRack($data_input["transaction_type"], $cutting_ticket);
    //         if($checkBundle['status'] == "error"){
    //             return  response()->json($checkBundle);
    //         };

    //         return response()->json($ticket);
    //     }catch(\Throwable $th){
    //         return response()->json([
    //             'status'=> 'error',
    //             'message'=> $th->getMessage()
    //         ]);
    //     }
    // }

    public function store(Request $request)
    {
        try {

            $data_input = $request->all();

            $cutting_ticket = CuttingTicket::with(['cuttingOrderRecord.layingPlanningDetail.layingPlanning.color','cuttingOrderRecord.layingPlanningDetail.layingPlanning.buyer' ,'size'])
                            ->select('cutting_tickets.id','cutting_tickets.serial_number','cutting_order_record_id','size_id','layer','ticket_number')
                            ->where('serial_number', $data_input['serial_number'])
                            ->first();

            if ($cutting_ticket == null) return response()->json(["message" => "Cutting Ticket Tidak Ditemukan"]);

            $data_return = [
                'status' => "success",
                'message' => "Berhasil menambah cutting ticket pada tabel",
                'data' => $cutting_ticket
            ];

            return response()->json($data_return);
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
}
