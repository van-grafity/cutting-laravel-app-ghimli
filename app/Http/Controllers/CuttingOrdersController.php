<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use PDF;

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

    public function dataCuttingOrder(){
        $query = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])
            ->select('cutting_order_records.id','laying_planning_detail_id','serial_number')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('no_laying_sheet', function ($data){
                return $data->layingPlanningDetail->no_laying_sheet;
            })
            ->addColumn('gl_number', function ($data){
                return $data->layingPlanningDetail->layingPlanning->gl->gl_number;
            })
            ->addColumn('color', function ($data){
                return $data->layingPlanningDetail->layingPlanning->color->color;
            })
            ->addColumn('table_number', function ($data){
                return $data->layingPlanningDetail->table_number;
            })
            ->addColumn('status', function($data){
                $sum_layer = 0;
                $status = '';
                foreach ($data->cuttingOrderRecordDetail as $detail) {
                    $sum_layer += $detail->layer;
                }
                if ($sum_layer == $data->layingPlanningDetail->layer_qty) {
                    $status = '<span class="badge badge-success">Complete</span>';
                } else if ($sum_layer > $data->layingPlanningDetail->layer_qty) {
                    $status = '<span class="badge badge-danger">Over Cut</span>';
                } else {
                    $status = '<span class="badge badge-warning">Not Complete</span>';
                }
                return $status;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('cutting-order.print', $data->id).'" class="btn btn-primary btn-sm mb-1" target="_blank">Print Nota</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm mb-1" onclick="delete_cuttingOrder('.$data->id.')" data-id="'.$data->id.'">Delete</a>
                <a href="'.route('cutting-order.show', $data->id).'" class="btn btn-info btn-sm mb-1">Detail</a>
                <a href="'.route('cutting-order.report', $data->id).'" class="btn btn-primary btn-sm mb-1" target="_blank">Print Report</a>
                ';
            })
            ->make(true);
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
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'fabric_consumption' => $layingPlanningDetail->layingPlanning->fabricCons->name,
            'marker_length' => $layingPlanningDetail->marker_yard ." yd ". $layingPlanningDetail->marker_inch ." inch",
        ];

        $size_ratio = $this->print_size_ratio($layingPlanningDetail);
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
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'fabric_cons' => $layingPlanningDetail->layingPlanning->fabricCons->name,
            'marker_length' => $layingPlanningDetail->marker_yard ." yd ". $layingPlanningDetail->marker_inch. " inch",
            'layer' => $layingPlanningDetail->layer_qty,
        ];

        $size_ratio = $this->print_size_ratio($layingPlanningDetail);
        $cutting_order = Arr::add($cutting_order, 'size_ratio', $size_ratio);

        $cutting_order_detail = $getCuttingOrder->cuttingOrderRecordDetail;

        $total_width = 0;
        $total_weight = 0;
        $total_layer = 0;
        foreach( $cutting_order_detail as $key => $detail ){
            $total_width += $detail->yardage;
            $total_weight += $detail->weight;
            $total_layer += $detail->layer;
            $detail->cutting_date = Carbon::createFromFormat('Y-m-d H:i:s', $detail->created_at)->format('d-m-Y');
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
            $data_return = [
                'status' => 'success',
                'data'=> $cuttingOrder,
                'message'=> 'Data Cutting Order berhasil di hapus',
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function print_pdf($cutting_order_id){

        $cutting_order = CuttingOrderRecord::find($cutting_order_id);
        $filename = $cutting_order->serial_number . '.pdf';

        $data = (object)[
            'serial_number' => $cutting_order->serial_number,
            'style' => $cutting_order->layingPlanningDetail->layingPlanning->style->style,
            'gl_number' => $cutting_order->layingPlanningDetail->layingPlanning->gl->gl_number,
            'no_laying_sheet' => $cutting_order->layingPlanningDetail->no_laying_sheet,
            'fabric_po' => $cutting_order->layingPlanningDetail->layingPlanning->fabric_po,
            'marker_code' => $cutting_order->layingPlanningDetail->marker_code,
            'marker_length' => $cutting_order->layingPlanningDetail->marker_yard.' yd '.  $cutting_order->layingPlanningDetail->marker_inch.' inch',
            'fabric_type' => $cutting_order->layingPlanningDetail->layingPlanning->fabricType->name,   
            'fabric_cons' => $cutting_order->layingPlanningDetail->layingPlanning->fabricCons->name,   
            'table_number' => $cutting_order->layingPlanningDetail->table_number,   
            'buyer' => $cutting_order->layingPlanningDetail->layingPlanning->buyer->name,   
            'size_ratio' => $this->print_size_ratio($cutting_order->layingPlanningDetail),   
            'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
            'layer' => $cutting_order->layingPlanningDetail->layer_qty,
            'date' => Carbon::now()->format('d-m-Y'),
        ];

        // dd($data);
        // return view('page.cutting-order.print', compact('data'));
        $pdf = PDF::loadview('page.cutting-order.print', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    public function cutting_order_detail(Request $request, $id) {

        $cutting_order_record_detail = CuttingOrderRecordDetail::find($id);
        $cutting_order_record_detail->color = $cutting_order_record_detail->color->color;

        $cutting_order_record_detail->cutting_date = Carbon::createFromFormat('Y-m-d H:i:s', $cutting_order_record_detail->created_at)->format('d-m-Y');
        return response()->json([
            'status' => 'success',
            'data' => $cutting_order_record_detail
        ], 200);
    }

    public function print_report_pdf($cutting_order_id) {

        $cutting_order = CuttingOrderRecord::find($cutting_order_id);
        $filename = $cutting_order->serial_number . '.pdf';

        $cor_details = [];
        $temp_cor_details = [];
        $cutting_order_detail = $cutting_order->CuttingOrderRecordDetail;
        
        foreach ($cutting_order_detail as $key  => $detail) {
            $data_detail = (object)[
                'place_no' => $detail->fabric_roll,
                'color' => $detail->color->color,
                'yardage' => $detail->yardage,
                'weight' => $detail->weight,
                'layer' => $detail->layer,
                'joint' => $detail->joint,
                'balance_end' => $detail->balance_end,
                'remarks' => $detail->remarks,
            ];
            $temp_cor_details[] = $data_detail;
        }

        // dd($temp_cor_details);


        for ($i=0; $i < 10; $i++) {
            if(array_key_exists($i, $temp_cor_details)) {
                $data_detail = (object)[
                    'place_no' => $temp_cor_details[$i]->place_no,
                    'color' => $temp_cor_details[$i]->color,
                    'yardage' => $temp_cor_details[$i]->yardage,
                    'weight' => $temp_cor_details[$i]->weight,
                    'layer' => $temp_cor_details[$i]->layer,
                    'joint' => $temp_cor_details[$i]->joint,
                    'balance_end' => $temp_cor_details[$i]->balance_end,
                    'remarks' => $temp_cor_details[$i]->remarks,
                ];
                
            } else {
                $data_detail = (object)[
                    'place_no' => '',
                    'color' => '',
                    'yardage' => '',
                    'weight' => '',
                    'layer' => '',
                    'joint' => '',
                    'balance_end' => '',
                    'remarks' => '',
                ];
            }
            $cor_details[] = $data_detail;
        }

        $data = (object)[
            'serial_number' => $cutting_order->serial_number,
            'style' => $cutting_order->layingPlanningDetail->layingPlanning->style->style,
            'gl_number' => $cutting_order->layingPlanningDetail->layingPlanning->gl->gl_number,
            'no_laying_sheet' => $cutting_order->layingPlanningDetail->no_laying_sheet,
            'fabric_po' => $cutting_order->layingPlanningDetail->layingPlanning->fabric_po,
            'marker_code' => $cutting_order->layingPlanningDetail->marker_code,
            'marker_length' => $cutting_order->layingPlanningDetail->marker_yard.' yd '.  $cutting_order->layingPlanningDetail->marker_inch.' inch',
            'fabric_type' => $cutting_order->layingPlanningDetail->layingPlanning->fabricType->name,   
            'fabric_cons' => $cutting_order->layingPlanningDetail->layingPlanning->fabricCons->name,   
            'table_number' => $cutting_order->layingPlanningDetail->table_number,   
            'buyer' => $cutting_order->layingPlanningDetail->layingPlanning->buyer->name,   
            'size_ratio' => $this->print_size_ratio($cutting_order->layingPlanningDetail),   
            'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
            'layer' => $cutting_order->layingPlanningDetail->layer_qty,
            'date' => Carbon::now()->format('d-m-Y'),
        ];

        // return view('page.cutting-order.report', compact('data','cor_details'));
        $pdf = PDF::loadview('page.cutting-order.report', compact('data','cor_details'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    function generate_serial_number($layingPlanningDetail){
        $gl_number = explode('-', $layingPlanningDetail->layingPlanning->gl->gl_number)[0];
        $color_code = $layingPlanningDetail->layingPlanning->color->color_code;
        $table_number = Str::padLeft($layingPlanningDetail->table_number, 3, '0');
        
        $serial_number = "COR-{$gl_number}-{$color_code}-{$table_number}";
        return $serial_number;
    }

    function print_size_ratio($layingPlanningDetail){
        
        // $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        // $size_ratio = [];
        // foreach( $get_size_ratio as $key => $size ) {
        //     $collect_size[] = $size->size->size;
        //     $collect_ratio[] = $size->ratio_per_size;
        // }
        // $collect_size = Arr::join($collect_size, ' | ');
        // $collect_ratio = Arr::join($collect_ratio, ' | ');
        // $size_ratio = [
        //     'size'=> $collect_size,
        //     'ratio'=>  $collect_ratio
        // ];


        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $size_ratio = [];

        foreach( $get_size_ratio as $key => $size ) {
            if($size->ratio_per_size > 0){
                $size_ratio[] = $size->size->size . " = " . $size->ratio_per_size;
            }
        }
        $size_ratio = Arr::join($size_ratio, ' | ');
        return $size_ratio;
    }

}
