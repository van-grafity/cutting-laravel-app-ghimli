<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\CuttingOrderRecord;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;

class CuttingOrdersController extends Controller
{
    public function index()
    {
        $get_data_cutting_order = CuttingOrderRecord::with(['layingPlanningDetail'])->get();
        $data = [];
        foreach ($get_data_cutting_order as $index => $cutting_order) {
            $temp = (object)[
                'no' => $index + 1,
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
        $laying_planning_detail = LayingPlanningDetail::find($laying_planning_detail_id);
        $data = [
            'id' => $laying_planning_detail->id,
            'no_laying_sheet' => $laying_planning_detail->no_laying_sheet,
            'table_number' => $laying_planning_detail->table_number,
            'gl_number' => $laying_planning_detail->layingPlanning->gl->gl_number,
            'style' => $laying_planning_detail->layingPlanning->style->style,
            'style_desc' => $laying_planning_detail->layingPlanning->style->description,
            'buyer' => $laying_planning_detail->layingPlanning->buyer->name,
            'color' => $laying_planning_detail->layingPlanning->color->color,
            'layer' => $laying_planning_detail->layer_qty,
            'fabric_po' => $laying_planning_detail->layingPlanning->fabric_po,
            'fabric_type' => $laying_planning_detail->layingPlanning->fabricType->description,
            'fabric_consumption' => $laying_planning_detail->layingPlanning->fabricType->description,
            'marker_length' => $laying_planning_detail->marker_yard ."yd ". $laying_planning_detail->marker_inch,
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

    public function show($id) {
        $get_cutting_order = CuttingOrderRecord::with(['layingPlanningDetail'])->find($id);
        $laying_planning_detail = LayingPlanningDetail::find($get_cutting_order->layingPlanningDetail->id);
        
        $cutting_order = [
            'no_laying_sheet'=> $laying_planning_detail->no_laying_sheet,
            'table_number' => $laying_planning_detail->table_number,
            'gl_number' => $laying_planning_detail->layingPlanning->gl->gl_number,
            'buyer' => $laying_planning_detail->layingPlanning->buyer->name,
            'style' => $laying_planning_detail->layingPlanning->style->style,
            'color' => $laying_planning_detail->layingPlanning->color->color,
            'fabric_po' => $laying_planning_detail->layingPlanning->fabric_po,
            'fabric_type' => $laying_planning_detail->layingPlanning->fabricType->description,
            'fabric_cons' => $laying_planning_detail->layingPlanning->fabricCons->description,
            'marker_length' => $laying_planning_detail->marker_yard ."yd ". $laying_planning_detail->marker_inch,
            'layer' => $laying_planning_detail->layer_qty,
        ];

        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $laying_planning_detail->id)->get();
        $size_ratio = [];
        foreach( $get_size_ratio as $key => $size ) {
            $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        $cutting_order = Arr::add($cutting_order, 'size_ratio', $size_ratio);

        $cutting_order_detail = $get_cutting_order->cuttingOrderRecordDetail;

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

}
