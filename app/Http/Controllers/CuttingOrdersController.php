<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;

class CuttingOrdersController extends Controller
{
    public function index()
    {
        // $colors = Color::all();
        return view('page.cutting-order.index');
    }

    // public function create()
    // {
    //     return view('page.cutting-order.index');
    // }

    public function createNota($id) {
        $laying_planning_detail = LayingPlanningDetail::find($id);
        $data = [
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

        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $id)->get();
        $size_ratio = [];

        foreach( $get_size_ratio as $key => $size ) {
            // $data_size = [
            //     'size' => $size->size->size,
            //     'ratio' => $size->ratio_per_size
            // ];
            // $size_ratio[] = $data_size;
            $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        $data = Arr::add($data, 'size_ratio', $size_ratio);
        $data = (object)$data;

        return view('page.cutting-order.createNota',compact('data'));
    }

    public function show($id) {
        return view('page.cutting-order.detail');
    }

}
