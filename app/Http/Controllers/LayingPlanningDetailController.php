<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CuttingOrderRecord;
use App\Models\FabricRequisition;


class LayingPlanningDetailController extends Controller
{

    public function unprint_cor(Request $request)
    {
        try {
            $lp_detail_ids = explode(',',$request->selected_item);
            $updated_cor = [];

            DB::transaction(function () use ($lp_detail_ids, &$updated_cor) {
                foreach ($lp_detail_ids as $key => $lp_detail_id) {
                    $cor = CuttingOrderRecord::where('laying_planning_detail_id',$lp_detail_id)->first();
                    $cor->status_print = 0;
                    $cor->save();
                    $updated_cor[] = $cor;
                }
            });

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully unprint '. count($updated_cor) .' Cutting Order Record',
                'data' => [
                    'updated_cor' => $updated_cor,
                ]
            ];
            return response()->json($data_return, 200);

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }
    public function unprint_fbr(Request $request)
    {
        try {
            $lp_detail_ids = explode(',',$request->selected_item);
            $updated_fbr = [];

            DB::transaction(function () use ($lp_detail_ids, &$updated_fbr) {
                foreach ($lp_detail_ids as $key => $lp_detail_id) {
                    $fbr = FabricRequisition::where('laying_planning_detail_id',$lp_detail_id)->first();
                    $fbr->status_print = 0;
                    $fbr->save();
                    $updated_fbr[] = $fbr;
                }
            });

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully unprint '. count($updated_fbr) .' Fabric Requisition',
                'data' => [
                    'updated_fbr' => $updated_fbr,
                ]
            ];
            return response()->json($data_return, 200);

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }
}
