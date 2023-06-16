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
        $getCuttingOrder = CuttingOrderRecord::with(['layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.color', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color'])->where('serial_number', $input['serial_number'])->latest()->first();
        if ($getCuttingOrder == null) return $this->onSuccess(null, 'Cutting Order not found.');
        $layingPlanningDetail = LayingPlanningDetail::with(['layingPlanning', 'layingPlanning.color'])->find($getCuttingOrder->layingPlanningDetail->id);
        if ($layingPlanningDetail->layingPlanning->color == null || $layingPlanningDetail->layingPlanning->color->id == null) return $this->onError(404, 'Color not found.');
        // if ($layingPlanningDetail->marker_yard == null) return $layingPlanningDetail->marker_yard = 0;
        // foreach ($data->cuttingOrderRecordDetail as $detail) {
        //     $sum_layer += $detail->layer;
        // }
        // if ($sum_layer == $data->layingPlanningDetail->layer_qty) {
        //     $status = '<span class="badge rounded-pill badge-success" style="padding: 1em">Selesai Layer</span>';
        // } else if ($sum_layer > $data->layingPlanningDetail->layer_qty) {
        //     $status = '<span class="badge rounded-pill badge-danger" style="padding: 1em">Over layer</span>';
        // } else {
        //     $status = '<span class="badge rounded-pill badge-warning" style="padding: 1em">Belum Selesai</span>';
        // }
        $status = $getCuttingOrder->statusLayer->name ?? 'not completed';
        $data = collect(
            [
                'laying_planning_detail' => $getCuttingOrder->layingPlanningDetail->load('layingPlanning', 'layingPlanning.color', 'cuttingOrderRecord', 'cuttingOrderRecord.statusLayer', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color'),
                // 'laying_planning_detail' => $getCuttingOrder->load('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.color', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color'),
                'status' => $status
            ]
        );

        if ($status == 'completed') {
            return $this->onSuccess($data, 'completed');
        } else if ($status == 'over layer') {
            return $this->onSuccess($data, 'over layer');
        } else {
            return $this->onSuccess($data, 'not completed');
        }
    }
}