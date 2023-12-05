<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Traits\ApiHelpers;
use App\Models\BundleStock;
use App\Models\BundleLocation;
use App\Models\BundleStockTransaction;
use App\Models\CuttingTicket;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class BundleStocksController extends Controller
{

    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'status' => 'success'
        ];
        return $this->onSuccess($data, 'Bundle Stock API.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_input = $request->all();

        $cutting_ticket = CuttingTicket::where('serial_number', $data_input['serial_number'])->first();
        if ($cutting_ticket == null) return $this->onError(404, 'Cutting Ticket not found.');
        
        try {

            // ## Check bundle is inside rack or not base on ticket number.
            $checkBundle = $this->checkSingleBundleOnRack($data_input['transaction_type'], $cutting_ticket);
            
            if($checkBundle['status'] == 'error'){
                return $this->onSuccess($checkBundle, 'Action failed');
            }
            
            DB::beginTransaction();
            
            // ## Create New Bundle Transaction 
            $bundle_stock_transaction = new BundleStockTransaction;
            $bundle_stock_transaction->ticket_id = $cutting_ticket->id;
            $bundle_stock_transaction->transaction_type = $data_input['transaction_type'];
            $bundle_stock_transaction->location_id = $data_input['location'];
            $bundle_stock_transaction->save();

            $new_bundle = BundleStockTransaction::where('id',$bundle_stock_transaction->id)->with('cuttingTicket')->first();
            $bundle_stock = $this->updateBundleStock($bundle_stock_transaction);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(500, $th->getMessage());
        }

        $message = 'Okeee';
        if($data_input['transaction_type'] == 'IN') {
            $message = "Berhasil memasukkan bundle ke dalam rack";
        } else if($data_input['transaction_type'] == 'OUT') {
            $message = "Berhasil mengeluarkan bundle dari rack";
        }

        $data_return = [
            'status' => "success",
            'new_bundle' => $new_bundle,
            'bundle_stock' => $bundle_stock,
            'message_data' => $message,
        ];
        
        return $this->onSuccess($data_return, "Bundle Transaction Data Inserted Successfully");
    }


    private function updateBundleStock($bundle_stock_transaction) : array
    {
        // ## Update Bundle Stock in Rack
        // todo : buat versi syncronize stock juga nanti

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

    /**
     * Store Bundle Stock Out to storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_multiple(Request $request)
    {
        $data_input = $request->all();

        $checked_tickets = $this->checkTicketlist($data_input['serial_number']);
        if($checked_tickets['status'] == 'error'){
            $data_return = [
                'status' => 'error',
                'message_data' => $checked_tickets['message_data'],
            ];
            return $this->onSuccess($data_return, "Action failed");
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
            return $this->onSuccess($first_error_bundle, 'Action failed');
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

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(500, $th->getMessage());
        }

        $message = 'Okeee';
        if($data_input['transaction_type'] == 'IN') {
            $message = "Berhasil memasukkan bundle ke dalam rack";
        } else if($data_input['transaction_type'] == 'OUT') {
            $message = "Berhasil mengeluarkan bundle dari rack";
        }

        $data_return = [
            'status' => "success",
            'new_bundle' => $new_bundle_list,
            'bundle_stock' => $bundle_stock_list,
            'message_data' => $message,
        ];
        
        return $this->onSuccess($data_return, "Bundle Transaction Data Inserted Successfully");
    }

    /**
     * Check if list of ticket serial number are valid or not.
     *
     * @param  Array ticket serial number list
     * @return Array with status success if all ticket serial number are found in database. status false otherwise 
     */
    private function checkTicketlist(Array $ticket_serial_number_list)
    {
        foreach ($ticket_serial_number_list as $key => $serial_number) {
            $cutting_ticket = CuttingTicket::where('serial_number', $serial_number)->first();
            if(!$cutting_ticket) { 
                $checking_result = [
                    'status' => 'error',
                    'message_data' => 'Bundle dengan nomor Tiket ' . $serial_number . ' tidak ditemukan'
                ];
                return $checking_result;
            }
        }
        $checking_result = [
            'status' => 'success',
            'message_data' => 'All Ticket are Valid'
        ];
        return $checking_result;
    }

    private function checkSingleBundleOnRack($transaction_type, $cutting_ticket)
    {
        $bundle_stock_transaction = BundleStockTransaction::where('ticket_id', $cutting_ticket->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $result = $this->generateResponses($bundle_stock_transaction, $transaction_type, $cutting_ticket);
        return $result;
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
            'status' => 'success',
            'message' => 'Go Ahead!'
        ];

        if (!$bundle_stock_transaction) {
            return ($transaction_type == 'OUT') ?
                ['status' => 'error', 'message_data' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' tidak ada di dalam rack'] :
                $success_condition;
        }

        $message = ($transaction_type == 'IN') ? 'Sudah ada di dalam rack' : 'Sudah keluar dari rack';

        return ($bundle_stock_transaction->transaction_type == $transaction_type) ?
            ['status' => 'error', 'message_data' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' ' . $message] :
            $success_condition;
    }

    
}
