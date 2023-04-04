<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\Color;
use App\Models\Remark;
use App\Http\Traits\ApiHelpers;

class CuttingOrdersController extends BaseController
{
    use ApiHelpers;

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }
    public function index()
    {
        $data = CuttingOrderRecord::with('cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')->latest()->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function show($serial_number)
    {
        $getCuttingOrder = CuttingOrderRecord::where('serial_number', $serial_number)->latest()->first();
        if ($getCuttingOrder == null) return $this->onError(404, 'Cutting Order Record not found.');
        $cuttingRecordDetail = CuttingOrderRecordDetail::with('CuttingOrderRecord')->whereHas('CuttingOrderRecord', function ($query) use ($serial_number) {
            $query->where('serial_number', $serial_number);
        })->get();
        
        $data = collect(
            [
                'cutting_order_record_detail' => $cuttingRecordDetail,
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
        if ($color == null) return $this->onError(404, 'Color not found.'); // ?
        $cuttingOrderRecordDetail->color_id = $color->id;

        $cuttingOrderRecordDetail->yardage = $input['yardage'];
        $cuttingOrderRecordDetail->weight = $input['weight'];
        $cuttingOrderRecordDetail->layer = $input['layer'];
        $cuttingOrderRecordDetail->joint = $input['joint'];
        $cuttingOrderRecordDetail->balance_end = $input['balance_end'];

        // $remark = Remark::where('name', $input['remarks'])->first();
        // if ($remark == null) return $this->onError(404, 'Remark not found.'); // not relation
        // $cuttingOrderRecordDetail->remark_id = $remark->id;

        $cuttingOrderRecordDetail->remarks = $input['remarks'];
        $cuttingOrderRecordDetail->operator = $input['operator'];
        $cuttingOrderRecordDetail->save();

        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('cuttingOrderRecordDetail')
            ->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record Detail created successfully.');
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