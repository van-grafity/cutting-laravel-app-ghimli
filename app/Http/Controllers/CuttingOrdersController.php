<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingOrderRecord;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CuttingOrdersController extends Controller
{
    public function index()
    {
        $get_data_cutting_order = CuttingOrderRecord::with('layingPlanningDetail')
            ->join('laying_planning_details', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->orderBy('laying_planning_details.id')
            ->orderBy('laying_planning_details.table_number')
            ->select('cutting_order_records.id','laying_planning_detail_id','serial_number')
            ->get();

        $data = [];
        foreach ($get_data_cutting_order as $index => $cutting_order) {
            $temp = (object)[
                'id' => $cutting_order->id,
                'no' => $index + 1,
                'serial_number' => $cutting_order->serial_number,
                'no_laying_sheet' => $cutting_order->layingPlanningDetail->no_laying_sheet,
                'gl_number' => $cutting_order->layingPlanningDetail->layingPlanning->gl->gl_number,
                'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
                'table_number' => $cutting_order->layingPlanningDetail->table_number,
            ];
            $data[] = $temp;
        }
        return view('page.cutting-order.index',compact('data'));
    }

    public function createNota($laying_planning_detail_id) {
        $layingPlanningDetail = LayingPlanningDetail::find($laying_planning_detail_id);

        $data = [
            'serial_number' => $this->generate_serial_number($layingPlanningDetail),
            'laying_planning_id' => $layingPlanningDetail->layingPlanning->id,
            'laying_planning_detail_id' => $layingPlanningDetail->id,
            'no_laying_sheet' => $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'style_desc' => $layingPlanningDetail->layingPlanning->style->description,
            'buyer' => $layingPlanningDetail->layingPlanning->buyer->name,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'layer' => $layingPlanningDetail->layer_qty,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->description,
            'fabric_consumption' => $layingPlanningDetail->layingPlanning->fabricCons->description,
            'marker_length' => $layingPlanningDetail->marker_yard ."yd ". $layingPlanningDetail->marker_inch,
        ];

        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail_id)->get();
        $size_ratio = [];

        foreach( $get_size_ratio as $key => $size ) {
            $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        $data = Arr::add($data, 'size_ratio', $size_ratio);
        $data = (object)$data;

        return view('page.cutting-order.createNota',compact('data'));
    }

    public function store(Request $request)
    {
        $layingPlanningDetail = LayingPlanningDetail::find($request->laying_planning_detail_id);
        $dataCuttingOrder = [
            'serial_number' => $this->generate_serial_number($layingPlanningDetail),
            'laying_planning_detail_id' => $request->laying_planning_detail_id
        ];
        $insertCuttingOrder = CuttingOrderRecord::create($dataCuttingOrder);
        return redirect()->route('cutting-order.index')->with('success', 'Cutting Order created successfully.');
    }

    public function show($id) {
        $getCuttingOrder = CuttingOrderRecord::with(['layingPlanningDetail'])->find($id);
        $layingPlanningDetail = LayingPlanningDetail::find($getCuttingOrder->layingPlanningDetail->id);
        
        $cutting_order = [
            'serial_number'=> $layingPlanningDetail->cuttingOrderRecord->serial_number,
            'no_laying_sheet'=> $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'buyer' => $layingPlanningDetail->layingPlanning->buyer->name,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->description,
            'fabric_cons' => $layingPlanningDetail->layingPlanning->fabricCons->description,
            'marker_length' => $layingPlanningDetail->marker_yard ."yd ". $layingPlanningDetail->marker_inch,
            'layer' => $layingPlanningDetail->layer_qty,
        ];

        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $size_ratio = [];
        foreach( $get_size_ratio as $key => $size ) {
            $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        $cutting_order = Arr::add($cutting_order, 'size_ratio', $size_ratio);

        $cutting_order_detail = $getCuttingOrder->cuttingOrderRecordDetail;

        $total_width = 0;
        $total_weight = 0;
        $total_layer = 0;
        foreach( $cutting_order_detail as $key => $detail ){
            $total_width += $detail->yardage;
            $total_weight += $detail->weight;
            $total_layer += $detail->layer;
        }
        $cutting_order = Arr::add($cutting_order, 'total_width', $total_width);
        $cutting_order = Arr::add($cutting_order, 'total_weight', $total_weight);
        $cutting_order = Arr::add($cutting_order, 'total_layer', $total_layer);

        $cutting_order = (object)$cutting_order;

        return view('page.cutting-order.detail', compact('cutting_order','cutting_order_detail'));
    }

    public function destroy($id)
    {
        try {
            $cuttingOrder = CuttingOrderRecord::find($id);
            $cuttingOrder->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $cuttingOrder,
                'message'=> 'Data Cutting Order berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    function generate_serial_number($layingPlanningDetail){
        $gl_number = explode('-', $layingPlanningDetail->layingPlanning->gl->gl_number)[0];
        $color_code = $layingPlanningDetail->layingPlanning->color->color_code;
        $table_number = Str::padLeft($layingPlanningDetail->table_number, 3, '0');
        
        $serial_number = "COR-{$gl_number}-{$color_code}-{$table_number}";
        return $serial_number;
    }

}
