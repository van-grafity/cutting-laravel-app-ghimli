<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LayingPlanning;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;

use Carbon\Carbon;


class UpdateDatabasesController extends Controller
{
    public function index()
    {
        $data_return = [
            [
                'title' => 'Update Cut Datetime on cutting_order_records table',
                'description' => 'Ambil data dari cutting_order_record_details. lastest created_at nya jadiin cutting_order_records.cut',
                'url' => url('update-database/cor-cut-datetime')
            ]
        ];
        return $data_return;
    }

    public function update_cor_cut_datetime(Request $request)
    {
        $year = $request->year ? $request->year : '2023';
        $month = $request->month ? $request->month : '12';
        $counter_updated = 0;
        $updated_cor = [];

        try {
            $cor_list = CuttingOrderRecord::select('cutting_order_records.*')
                ->where('cut',null)
                ->whereMonth('created_at',$month)
                ->whereYear('created_at',$year)
                ->get();
            
            foreach ($cor_list as $key => $cor) {
                $cor_detail = CuttingOrderRecordDetail::select('*')
                    ->where('cutting_order_record_details.cutting_order_record_id',$cor->id)
                    ->orderBy('created_at','DESC')
                    ->first();

                if(!$cor_detail) { continue; }
                $cor->cut = $cor_detail->created_at;
                $cor->save();
                
                $updated_cor[] = $cor; 
                $counter_updated++;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil memperbaharui '. $counter_updated .' data Cutting Order Record',
                'data' => [
                    'updated_cor' => $updated_cor,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
        
    }

}