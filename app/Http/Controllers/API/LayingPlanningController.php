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

    public function show($serial_number)
    {
        $getCuttingOrder = CuttingOrderRecord::with(['CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color'])->where('serial_number', $serial_number)->latest()->first();
        if ($getCuttingOrder == null) return $this->onError(404, 'Cutting Order Record not found.');
        $layingPlanningDetail = LayingPlanningDetail::with(['layingPlanning', 'layingPlanning.color'])->find($getCuttingOrder->layingPlanningDetail->id);
        if ($layingPlanningDetail->layingPlanning->color == null || $layingPlanningDetail->layingPlanning->color->id == null) return $this->onError(404, 'Color not found.');
        // if ($layingPlanningDetail->marker_yard == null) return $layingPlanningDetail->marker_yard = 0;
        $data = collect(
            [
                // 'cutting_order_record' => $getCuttingOrder,
                'laying_planning_detail' => $layingPlanningDetail,
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }
}