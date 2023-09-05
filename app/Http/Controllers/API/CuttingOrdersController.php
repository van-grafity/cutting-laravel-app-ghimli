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
use App\Models\StatusLayer;
use App\Models\StatusCut;
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
        $data = CuttingOrderRecord::with('statusLayer', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')->latest()->paginate(50);
        // get latest base on data cuttingOrderRecordDetail
        // $data = CuttingOrderRecord::with('statusLayer', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')
        //     ->whereHas('cuttingOrderRecordDetail', function ($query) {
        //         $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 day')));
        //     })
        //     ->latest()
        //     ->paginate(50);
        $pagination = [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'next_page_url' => $data->nextPageUrl(),
            'total' => $data->total(),
        ];
        
        $cuttingOrderRecords = [
            'cutting_order_records' => $data->items()
        ];

        $result = array_merge($cuttingOrderRecords, $pagination);

        return $this->onSuccess($result, 'Cutting Order Record retrieved successfully.');
    }

    public function show($serial_number)
    {
        $getCuttingOrder = CuttingOrderRecord::where('serial_number', $serial_number)->latest()->first();
        if ($getCuttingOrder == null) return $this->onError(404, 'Cutting Order Record not found.');
        $layingPlanningDetail = LayingPlanningDetail::where('id', $getCuttingOrder->laying_planning_detail_id)->first();
        // $layingPlanning = LayingPlanning::with('layingPlanningDetail.cuttingOrderRecord')->where('id', $layingPlanningDetail->laying_planning_id)->first();

        // foreach ($layingPlanning->layingPlanningDetail as $detail) {
        //     if ($detail->marker_code == 'PILOT RUN' && $detail->cuttingOrderRecord->is_pilot_run == 0) {
        //         return $this->onError(404, 'Pilot Run must be approved first.');
        //     }
        // }
        
        $cuttingRecordDetail = CuttingOrderRecordDetail::with('CuttingOrderRecord')->whereHas('CuttingOrderRecord', function ($query) use ($serial_number) {
            $query->where('serial_number', $serial_number);
        })->get();
        
        $data = collect(
            [
                'cutting_order_record_details' => $cuttingRecordDetail,
                // 'laying_planning' => $layingPlanning
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::with('CuttingOrderRecordDetail')->where('serial_number', $input['serial_number'])->first();
        $sum_layer = 0;
        foreach ($cuttingOrderRecord->cuttingOrderRecordDetail as $detail) {
            $sum_layer += $detail->layer;
        }
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

        $cuttingOrderRecordDetail->remarks = $input['remarks'];
        $cuttingOrderRecordDetail->operator = $input['operator'];

        $sum_layer += $input['layer'];
        if ($sum_layer == $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
            $status = StatusLayer::where('name', 'completed')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
        } else if ($sum_layer > $cuttingOrderRecord->layingPlanningDetail->layer_qty) {
            return $this->onSuccess(null, 'Layer Cut tidak boleh lebih dari Layer Qty.');
            $status = StatusLayer::where('name', 'over layer')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
        } else {
            $status = StatusLayer::where('name', 'not completed')->first();
            if ($status == null) return $this->onError(404, 'Status Layer Cut not found.');
            $cuttingOrderRecord->id_status_layer = $status->id;
        }
        
        $cuttingOrderRecordDetail->save();
        $cuttingOrderRecord->save();
        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('statusLayer', 'cuttingOrderRecordDetail')
            ->first();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record Detail created successfully.');
    }

    public function search(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::with('statusLayer', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')
        ->where('serial_number', 'like', '%' . $input['serial_number'] . '%')
        ->get();
        
        $data = collect(
            [
                'cutting_order_records' => $cuttingOrderRecord
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
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

    public function getCuttingOrderRecordByGlId($id)
    {
        $data = CuttingOrderRecord::whereHas('layingPlanningDetail', function ($query) use ($id) {
            $query->whereHas('layingPlanning', function ($query) use ($id) {
                $query->whereHas('gl', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            });
        })->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function getLayingPlanningDetailByCuttingOrderRecordId($id)
    {
        $data = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.gl')->where('id', $id)->get();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function postStatusCut(Request $request)
    {
        $input = $request->all();
        $cuttingOrderRecord = CuttingOrderRecord::where('serial_number', $input['serial_number'])->first();
        $statusCut = StatusCut::where('name', $input['name'])->first();
        if ($statusCut == null) return $this->onError(404, 'Status Cut not found.'); // not relation
        $cuttingOrderRecord->id_status_cut = $statusCut->id;
        $cuttingOrderRecord->save();
        $data = CuttingOrderRecord::where('cutting_order_records.id', $cuttingOrderRecord->id)->with('statusCut')
            ->first();
        $data = collect(
            [
                'cutting_order_record' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record updated successfully.');
    }
    
    public function destroy($id)
    {
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::find($id);
        $cuttingOrderRecordDetail->delete();
        return $this->onSuccess($cuttingOrderRecordDetail, 'Cutting Order Record Detail deleted successfully.');
    }
}