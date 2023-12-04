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
            $checkBundle = $this->checkBundleOnRack($cutting_ticket, $data_input['transaction_type']);
            if($checkBundle['status'] == 'error'){
                return $this->onSuccess($checkBundle, 'Action denied');
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

    private function checkBundleOnRack($cutting_ticket, $transaction_type) : array
    {
        $data_return = [
            'status' => 'success',
            'message' => 'Go Ahead!'
        ];
        $bundle_stock_transaction = BundleStockTransaction::where('ticket_id', $cutting_ticket->id)->orderBy('created_at', 'DESC')->first();
        
        //## If no bundle with same gl, color, size on the rack. Then go ahead ✔
        if(!$bundle_stock_transaction) { return $data_return; }
        
        if($bundle_stock_transaction->transaction_type == $transaction_type ){
            if($transaction_type == 'IN') {
                $data_return = [
                    'status' => 'error',
                    'message_data' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' Sudah ada di dalam rack',
                ];
            } else if($transaction_type == 'OUT') {
                $data_return = [
                    'status' => 'error',
                    'message_data' => 'Bundle dengan nomor ticket ' . $cutting_ticket->serial_number . ' tidak ada di dalam rack',
                ];
            }
        }
        return $data_return;
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
}
