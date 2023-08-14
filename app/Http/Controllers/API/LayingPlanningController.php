<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanning;
use App\Models\Color;
use App\Models\Remark;
use App\Http\Traits\ApiHelpers;
// DB
use Illuminate\Support\Facades\DB;

class LayingPlanningController extends BaseController
{

    use ApiHelpers;

    public function index()
    {
        $data = LayingPlanningDetail::with('layingPlanning','cuttingOrderRecord','cuttingOrderRecord.cuttingOrderRecordDetail','cuttingOrderRecord.cuttingOrderRecordDetail.color', 'layingPlanning.color')->get();
        $data = collect(
            [
                'laying_planning_detail' => $data
            ]
        );
        return $this->onSuccess($data, 'Laying Planning Detail retrieved successfully.');
    }

    public function show(Request $request)
    {
        $input = $request->all();
        $getCuttingOrder = CuttingOrderRecord::with([ 'statusLayer',  'statusCut', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.color', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color'])->where('serial_number', $input['serial_number'])->latest()->first();
        if ($getCuttingOrder == null) return $this->onSuccess(null, 'Cutting Order not found.');
        $layingPlanningDetail = LayingPlanningDetail::with(['layingPlanning', 'layingPlanning.color'])->find($getCuttingOrder->layingPlanningDetail->id);
        $layingPlanning = LayingPlanning::with('layingPlanningDetail.cuttingOrderRecord')->where('id', $layingPlanningDetail->laying_planning_id)->first();

        // foreach ($layingPlanning->layingPlanningDetail as $detail) {
        //     if ($detail->marker_code == 'PILOT RUN' && $detail->cuttingOrderRecord->is_pilot_run == 0 && $detail->cuttingOrderRecord->serial_number != $getCuttingOrder->serial_number) {
        //         return $this->onSuccess(null, 'Pilot Run must be approved first.');
        //     }
        // }
        
        if ($layingPlanningDetail->layingPlanning->color == null || $layingPlanningDetail->layingPlanning->color->id == null) return $this->onError(404, 'Color not found.');
        $status = $getCuttingOrder->statusLayer->name ?? 'not completed';
        $data = collect(
            [
                'cutting_order_record' => $getCuttingOrder->load('cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color'),
                // 'laying_planning_detail' => $getCuttingOrder->load('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.color', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color'),
                // 'laying_planning' => $layingPlanning,
                'status' => $status
            ]
        );

        foreach ($layingPlanning->layingPlanningDetail as $detail) {
            if ($detail->marker_code == 'PILOT RUN' && $detail->cuttingOrderRecord->is_pilot_run == 0) {
                if ($detail->cuttingOrderRecord->serial_number != $getCuttingOrder->serial_number) {
                    return $this->onSuccess(null, 'Pilot Run must be approved first.');
                }
                return $this->onSuccess($data, 'Laying Planning Detail retrieved successfully.');
            }
        }

        if ($status == 'completed') {
            return $this->onSuccess($data, 'completed');
        } else if ($status == 'over layer') {
            return $this->onSuccess($data, 'over layer');
        } else {
            return $this->onSuccess($data, 'not completed');
        }
    }
}