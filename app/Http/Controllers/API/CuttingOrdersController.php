<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\Color;
use App\Http\Traits\ApiHelpers;
use App\Http\Controllers\API\BaseController as BaseController;

class CuttingOrdersController extends BaseController
{
    use ApiHelpers;

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        $data = CuttingOrderRecord::with(['layingPlanningDetail'])->get();
        $data = collect(
            [
                'cuttingOrderRecord' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function show($serial_number)
    {
        $getCuttingOrder = CuttingOrderRecord::with(['CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color'])->where('serial_number', $serial_number)->latest()->first();
        $layingPlanningDetail = LayingPlanningDetail::with(['layingPlanning', 'layingPlanning.color'])->find($getCuttingOrder->layingPlanningDetail->id);
        $data = collect(
            [
                'laying_planning_detail' => $layingPlanningDetail
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::where('serial_number', $input['serial_number'])->first();
        $cuttingOrderRecordDetail = new CuttingOrderRecordDetail;
        $cuttingOrderRecordDetail->cutting_order_record_id = $cuttingOrderRecord->id;
        $cuttingOrderRecordDetail->fabric_roll = $input['fabric_roll'];
        $cuttingOrderRecordDetail->fabric_batch = $input['fabric_batch'];

        $color = Color::where('color', $input['color'])->first();
        $cuttingOrderRecordDetail->color_id = $color->id;

        $cuttingOrderRecordDetail->yardage = $input['yardage'];
        $cuttingOrderRecordDetail->weight = $input['weight'];
        $cuttingOrderRecordDetail->layer = $input['layer'];
        $cuttingOrderRecordDetail->joint = $input['joint'];
        $cuttingOrderRecordDetail->balance_end = $input['balance_end'];
        $cuttingOrderRecordDetail->remarks = $input['remarks'];
        $cuttingOrderRecordDetail->operator = $input['operator'];
        $cuttingOrderRecordDetail->save();
        return $this->onSuccess($cuttingOrderRecordDetail, 'Cutting Order Record Detail created successfully.');
    }

    public function color()
    {
        $data = Color::all();
        $data = collect(
            [
                'color' => $data
            ]
        );
        return $this->onSuccess($data, 'Color retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::find($id);
        $cuttingOrderRecordDetail->fabric_roll = $input['fabric_roll'];
        $cuttingOrderRecordDetail->fabric_batch = $input['fabric_batch'];
        $cuttingOrderRecordDetail->color_id = $input['color_id'];
        $cuttingOrderRecordDetail->yardage = $input['yardage'];
        $cuttingOrderRecordDetail->weight = $input['weight'];
        $cuttingOrderRecordDetail->layer = $input['layer'];
        $cuttingOrderRecordDetail->joint = $input['joint'];
        $cuttingOrderRecordDetail->balance_end = $input['balance_end'];
        $cuttingOrderRecordDetail->remarks = $input['remarks'];
        $cuttingOrderRecordDetail->operator = $input['operator'];
        $cuttingOrderRecordDetail->save();
        return $this->onSuccess($cuttingOrderRecordDetail, 'Cutting Order Record Detail updated successfully.');
    }

    public function destroy($id)
    {
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::find($id);
        $cuttingOrderRecordDetail->delete();
        return $this->onSuccess($cuttingOrderRecordDetail, 'Cutting Order Record Detail deleted successfully.');
    }
}