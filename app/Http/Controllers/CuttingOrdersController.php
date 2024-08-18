<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use App\Models\User;
use App\Models\UserGroups;
use App\Models\Groups;
use App\Models\StatusLayer;
use App\Models\StatusCut;
use App\Models\LayingPlanning;
use App\Models\Gl;
use App\Models\FabricRequisition;
use App\Models\FabricIssue;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use PDF;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class CuttingOrdersController extends Controller
{
    public function index()
    {
        // $cuttingOrder = CuttingOrderRecord::with(['cuttingOrderRecordDetail'])->get();
        // UPDATE `cutting_order_records` JOIN `cutting_order_record_details` ON `cutting_order_records`.`id` = `cutting_order_record_details`.`cutting_order_record_id` SET `cutting_order_records`.`id_status_layer` = 4 WHERE `cutting_order_records`.`id_status_layer` = 1 AND `cutting_order_records`.`id_status_cut` = 1;
        // foreach ($cuttingOrder as $key => $value) {
        //     if ($value->id_status_layer == 1 && $value->id_status_cut == 1) {
        //         if ($value->cuttingOrderRecordDetail != null) {
        //             $value->id_status_layer = 4;
        //         }
        //     } else {
        //         $value->id_status_layer = $value->id_status_layer;
        //     }
        //     $value->save();
        // }

        // foreach ($cuttingOrder as $key => $value) {
        //     if ($value->id_status_layer == 4) {
        //         if ($value->cuttingOrderRecordDetail->isEmpty()) {
        //             $value->id_status_layer = 1;
        //             $value->save();
        //         }
        //     }
        // }
        
        return view('page.cutting-order.index');
    }

    public function dataCuttingOrder(){
        $query = DB::table('cutting_order_records')
            ->join('laying_planning_details', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('laying_plannings', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('gls', 'laying_plannings.gl_id', '=', 'gls.id')
            ->join('styles', 'laying_plannings.style_id', '=', 'styles.id')
            ->join('colors', 'laying_plannings.color_id', '=', 'colors.id')
            ->join('fabric_types', 'laying_plannings.fabric_type_id', '=', 'fabric_types.id')
            ->join('fabric_cons', 'laying_plannings.fabric_cons_id', '=', 'fabric_cons.id')
            ->where('cutting_order_records.deleted_at', '=', null)
            ->select('cutting_order_records.id', 'cutting_order_records.serial_number', 'cutting_order_records.is_pilot_run', 'cutting_order_records.id_status_layer', 'cutting_order_records.id_status_cut', 'styles.style', 'colors.color', 'fabric_types.name as fabric_type', 'fabric_cons.name as fabric_cons', 'cutting_order_records.created_at', 'cutting_order_records.status_print')
            ->orderBy('cutting_order_records.updated_at', 'desc')->get();
            
            return Datatables::of($query)
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('color', function ($data){
                return $data->color;
            })
            ->addColumn('style', function ($data){
                return $data->style;
            })
            ->addColumn('fabric_type', function ($data){
                return $data->fabric_type;
            })
            ->addColumn('fabric_cons', function ($data){
                return $data->fabric_cons;
            })
            ->addColumn('status_layer', function($data){
                $status = '';
                if ($data->id_status_layer == 2) {
                    $status = '<span class="badge rounded-pill badge-success" style="padding: 1em">Selesai Layer</span>';
                } else if ($data->id_status_layer == 3) {
                    $status = '<span class="badge rounded-pill badge-danger" style="padding: 1em">Over layer</span>';
                } else if ($data->id_status_layer == 4) {
                    $status = '<span class="badge rounded-pill badge-info" style="padding: 1em">Sedang di Layer</span>';
                } else {
                    $status = '<span class="badge rounded-pill badge-warning" style="padding: 1em">Belum Layer</span>';
                }
                return $status;
            })
            ->addColumn('status_cut', function($data){
                $status = '';
                if ($data->id_status_cut == 2) {
                    $status = '<span class="badge rounded-pill badge-success" style="padding: 1em">Sudah Potong</span>';
                } else {
                    $status = '<span class="badge rounded-pill badge-warning" style="padding: 1em">Belum Potong</span>';
                }
                return $status;
            })
            // ->addColumn('status', function($data){
            //     $html = '<div class="d-flex flex-row">';
            //     if ($data->id_status_layer == 2) {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-success" style="padding: 8px; margin: -2px;">Selesai Layer</span></div>';
            //     } else if ($data->id_status_layer == 3) {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-danger" style="padding: 8px; margin: -2px;">Over layer</span></div>';
            //     } else if ($data->id_status_layer == 4) {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-warning" style="padding: 8px; margin: -2px;">On Progress</span></div>';
            //     } else {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-info" style="padding: 8px; margin: -2px;">Belum Layer</span></div>';
            //     }
            //     if ($data->id_status_cut == 2) {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-success" style="padding: 8px; margin: -2px;">Sudah Potong</span></div>';
            //     } else {
            //         $html .= '<div class="p-2"><span class="badge rounded-pill badge-info" style="padding: 8px; margin: -2px;">Belum Potong</span></div>';
            //     }
            //     $html .= '</div>';
            //     return $html;
            // })
            ->addColumn('created_at', function($data){
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
            })
            ->addColumn('action', function($data){
                $action_delete = '';
                if(Auth::user()->hasRole('super_admin')){
                    $action_delete = '<a href="javascript:void(0);" class="btn btn-danger btn-sm mb-1" onclick="delete_cuttingOrder('.$data->id.')" data-id="'.$data->id.'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                if ($data->status_print == 0) {
                    $action = '
                    <a href="'.route('cutting-order.report', $data->id).'" class="btn btn-primary btn-sm mb-1" target="_blank" data-toggle="tooltip" data-placement="top" title="Print Nota"><i class="fas fa-print"></i></a>
                    '.$action_delete.'
                    <a href="'.route('cutting-order.show', $data->id).'" class="btn btn-info btn-sm mb-1" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fas fa-eye"></i></a>';
                } else {
                    $action = '
                    '.$action_delete.'
                    <a href="'.route('cutting-order.show', $data->id).'" class="btn btn-info btn-sm mb-1" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fas fa-eye"></i></a>';
                }
                return $action;
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
            'buyer' => $layingPlanningDetail->layingPlanning->gl->buyer->name,
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
        $checkCuttingOrder = CuttingOrderRecord::where('laying_planning_detail_id', $request->laying_planning_detail_id)->first();
        if ($checkCuttingOrder != null) {
            return redirect()->route('cutting-order.show', $checkCuttingOrder->id)->with('error', 'Cutting Order already exist.');
        }

        try {
            $dataCuttingOrder = [
                'serial_number' => $this->generate_serial_number(LayingPlanningDetail::find($request->laying_planning_detail_id)),
                'laying_planning_detail_id' => $request->laying_planning_detail_id,
                'created_by' => auth()->user()->id,
            ];
            $cuttingOrder = CuttingOrderRecord::create($dataCuttingOrder);
            return redirect()->route('cutting-order.show', $cuttingOrder->id)->with('success', 'Cutting Order created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('cutting-order.index')->with('error', $th->getMessage());
        }
    }

    public function show($id) {
        $getCuttingOrder = CuttingOrderRecord::with(['layingPlanningDetail'])->find($id);
        $layingPlanningDetail = LayingPlanningDetail::find($getCuttingOrder->layingPlanningDetail->id);
       
        $cutting_order = [
            'id' => $getCuttingOrder->id,
            'serial_number'=> $getCuttingOrder->serial_number,
            'laying_planning_id' => $layingPlanningDetail->layingPlanning->id,
            'no_laying_sheet'=> $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'buyer' => $layingPlanningDetail->layingPlanning->gl->buyer->name,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'fabric_cons' => $layingPlanningDetail->layingPlanning->fabricCons->name,
            'marker_length' => $layingPlanningDetail->marker_yard ." yd ". $layingPlanningDetail->marker_inch. " inch",
            'marker_inches' => $layingPlanningDetail->marker_inch,
            'marker_yards' => $layingPlanningDetail->marker_yard,
            'marker_code' => $layingPlanningDetail->marker_code,
            'is_pilot_run' => $getCuttingOrder->is_pilot_run,
            'created_by' => $getCuttingOrder->user->name,
            'pilot_run' => $getCuttingOrder->pilot_run == null ? null : Carbon::createFromFormat('Y-m-d H:i:s', $getCuttingOrder->pilot_run)->format('d-m-Y H:i'),
            'layer' => $layingPlanningDetail->layer_qty,
            'status_layer' => $getCuttingOrder->statusLayer->name,  
            'status_cut' => $getCuttingOrder->statusCut->name,
            'status_print' => $getCuttingOrder->status_print,
        ];

        $size_ratio = $this->print_size_ratio($layingPlanningDetail);
        $cutting_order = Arr::add($cutting_order, 'size_ratio', $size_ratio);

        $cutting_order_detail = $getCuttingOrder->cuttingOrderRecordDetail;

        $total_width = 0;
        $total_weight = 0;
        $total_layer = 0;
        $total_balance_end = 0;
        foreach( $cutting_order_detail as $key => $detail ){
            $total_width += $detail->yardage;
            $total_weight += $detail->weight;
            $total_layer += $detail->layer;
            $total_balance_end += $detail->balance_end;
            $detail->cutting_date = Carbon::createFromFormat('Y-m-d H:i:s', $detail->created_at)->format('d-m-Y');
        }

        $cutting_order = Arr::add($cutting_order, 'total_width', $total_width);
        $cutting_order = Arr::add($cutting_order, 'total_weight', $total_weight);
        $cutting_order = Arr::add($cutting_order, 'total_layer', $total_layer);
        $cutting_order = Arr::add($cutting_order, 'total_balance_end', $total_balance_end);

        $cutting_order = (object)$cutting_order;

        // reference
        // if ($getCuttingOrder->id_status_layer == 1 && $getCuttingOrder->id_status_cut == 1) {
        //     if ($getCuttingOrder->cuttingOrderRecordDetail != null) {
        //         $getCuttingOrder->id_status_layer = 4;
        //     } else {
        //         $getCuttingOrder->id_status_layer = 1;
        //     }
        // } else {
        //     $getCuttingOrder->id_status_layer = $getCuttingOrder->id_status_layer;
        // }

        // $max_min = $layingPlanningDetail->layer_qty * 0.03;
        // if ($this->print_total_layer($getCuttingOrder->cuttingOrderRecordDetail) <= $layingPlanningDetail->layer_qty + $max_min && $this->print_total_layer($getCuttingOrder->cuttingOrderRecordDetail) >= $layingPlanningDetail->layer_qty - $max_min && $this->print_total_layer($getCuttingOrder->cuttingOrderRecordDetail) != 0) {
        //     $status = StatusLayer::where('name', 'completed')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'completed'
        //         ]);
        //     }
        //     $getCuttingOrder->id_status_layer = $status->id;
        // } else if ($this->print_total_layer($getCuttingOrder->cuttingOrderRecordDetail) > $layingPlanningDetail->layer_qty + $max_min) {
        //     $status = StatusLayer::where('name', 'over Layer')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'over Layer'
        //         ]);
        //     }
        //     $getCuttingOrder->id_status_layer = $status->id;
        // } else {
        //     $status = StatusLayer::where('name', 'not completed')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'not completed'
        //         ]);
        //     }
        //     $getCuttingOrder->id_status_layer = $status->id;
        // }
        
        // $getCuttingOrder->save();

        // update
        // if ($getCuttingOrder->id_status_layer == 1 && $getCuttingOrder->id_status_cut == 1) {
        //     if ($getCuttingOrder->cuttingOrderRecordDetail->isEmpty()) {
        //         $getCuttingOrder->id_status_layer = 4;
        //     } else {
        //         $getCuttingOrder->id_status_layer = 1;
        //     }
        // } else {
        //     $getCuttingOrder->id_status_layer = $getCuttingOrder->id_status_layer;
        // }

        // $max_min = $layingPlanningDetail->layer_qty * 0.03;
        // if ($this->print_total_layer($cutting_order_detail) <= $layingPlanningDetail->layer_qty + $max_min && $this->print_total_layer($cutting_order_detail) >= $layingPlanningDetail->layer_qty - $max_min && $this->print_total_layer($cutting_order_detail) != 0) {
        //     $status = StatusLayer::where('name', 'completed')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'completed'
        //         ]);
        //     }
        //     $cutting_order->id_status_layer = $status->id;
        // } else if ($this->print_total_layer($cutting_order_detail) > $layingPlanningDetail->layer_qty + $max_min) {
        //     $status = StatusLayer::where('name', 'over Layer')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'over Layer'
        //         ]);
        //     }
        //     $cutting_order->id_status_layer = $status->id;
        // } else {
        //     $status = StatusLayer::where('name', 'not completed')->first();
        //     if ($status == null) {
        //         $status = StatusLayer::create([
        //             'name' => 'not completed'
        //         ]);
        //     }
        //     $cutting_order->id_status_layer = $status->id;
        // }

        $getCuttingOrder->save();
        return view('page.cutting-order.detail', compact('cutting_order','cutting_order_detail'));
    }
    
    public function delete_cor_detail($id) {
        try {
            $cutting_order_detail = CuttingOrderRecordDetail::with(['cuttingOrderRecord'])->find($id);
            $cutting_order_detail->delete();
            $cutting_order = $cutting_order_detail->cuttingOrderRecord;
            $cutting_order->id_status_layer = 1;
            $cutting_order->id_status_cut = 1;
            $cutting_order->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Cutting Order Detail berhasil di hapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
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
            'buyer' => $cutting_order->layingPlanningDetail->layingPlanning->gl->buyer->name,
            'remark' => $cutting_order->layingPlanningDetail->layingPlanning->remark,
            'size_ratio' => $this->print_size_ratio($cutting_order->layingPlanningDetail),   
            'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
            'layer' => $cutting_order->layingPlanningDetail->layer_qty,
            'date' => Carbon::now()->format('d-m-Y H:i:s'),
            'printed_by' => Auth::user()->name,
        ];

        // dd($data);
        // return view('page.cutting-order.print', compact('data'));
        $pdf = PDF::loadview('page.cutting-order.print', compact('data'))->setPaper('a4', 'landscape');
        if(!Auth::user()->hasRole('super_admin') || !Auth::user()->hasRole('merchandiser')){
            $cutting_order->status_print = true;
            $cutting_order->save();
        }
        
        return $pdf->stream($filename);
    }
   
    public function print_multiple($id, Request $request)
    {
        $laying_planning_laying_planning_detail_ids = $request->laying_planning_laying_planning_detail_ids;
        $laying_planning_laying_planning_detail_ids = explode(',', $laying_planning_laying_planning_detail_ids);
        $laying_planning_details = LayingPlanningDetail::whereIn('id', $laying_planning_laying_planning_detail_ids)->get();
        $data = [];
        foreach($laying_planning_details as $laying_planning_detail){
            $cutting_order = CuttingOrderRecord::where('laying_planning_detail_id', $laying_planning_detail->id)->first();
            $data[] = [
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
                'buyer' => $cutting_order->layingPlanningDetail->layingPlanning->gl->buyer->name,
                'remark' => $cutting_order->layingPlanningDetail->layingPlanning->remark,   
                'size_ratio' => $this->print_size_ratio($cutting_order->layingPlanningDetail),   
                'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
                'layer' => $cutting_order->layingPlanningDetail->layer_qty,
                'date' => Carbon::now()->format('d-m-Y H:i:s'),
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $cutting_order->created_at)->format('d-m-Y'),
                'printed_by' => Auth::user()->name,
            ];
        }

        // $customPaper = array(0,0,612.00,792.00);
        $pdf = PDF::loadview('page.cutting-order.print-multiple', compact('data'))->setPaper('a4', 'landscape');

        if(!Auth::user()->hasRole('super_admin') || !Auth::user()->hasRole('merchandiser')){
            foreach($laying_planning_details as $laying_planning_detail){
                $cutting_order = CuttingOrderRecord::where('laying_planning_detail_id', $laying_planning_detail->id)->first();
                $cutting_order->status_print = true;
                $cutting_order->save();
            }
        }
        
        return $pdf->stream('cutting-order.pdf');
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
    
    public function approve_pilot_run($id) {
        try {
            $cutting_order = CuttingOrderRecord::find($id);
            $cutting_order->is_pilot_run = !$cutting_order->is_pilot_run;
            $cutting_order->pilot_run = Carbon::now();
            $cutting_order->save();
            
            return redirect()->route('cutting-order.show', $id)->with('success', 'Cutting Order created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('cutting-order.show', $id)->with('error', $th->getMessage());
        }
    }
    
    public function print_report_pdf($cutting_order_id) {

        $cutting_order = CuttingOrderRecord::with(['cuttingOrderRecordDetail.user'])->find($cutting_order_id);
        $filename = $cutting_order->serial_number . '.pdf';

        $cor_details = [];
        $temp_cor_details = [];
        $cutting_order_detail = $cutting_order->CuttingOrderRecordDetail->load('user');
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

        // if null
        $name = $cutting_order_detail[0]->operator ?? null;
        if($name == null){
            $name = '';
        } else {
            $user = User::where('name', $name)->first();
            if($user == null){
                $name = '';
            } else {
                $user_group = UserGroups::where('user_id', $user->id)->first();
                if($user_group == null){
                    $name = '';
                } else {
                    $group = Groups::where('id', $user_group->group_id)->first();
                    if($group == null){
                        $name = '';
                    } else {
                        $name = $group->group_name;
                    }
                }
            }
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
            'buyer' => $cutting_order->layingPlanningDetail->layingPlanning->gl->buyer->name,
            'remark' => $cutting_order->layingPlanningDetail->layingPlanning->remark,
            'size_ratio' => $this->print_size_ratio($cutting_order->layingPlanningDetail),
            'total_size_ratio' => $this->print_total_size_ratio($cutting_order->layingPlanningDetail),
            'color' => $cutting_order->layingPlanningDetail->layingPlanning->color->color,
            'layer' => $cutting_order->layingPlanningDetail->layer_qty,
            'total_size_ratio_layer' => $cutting_order_detail->isEmpty() ? "" : $this->print_total_size_ratio($cutting_order->layingPlanningDetail) * $cutting_order->layingPlanningDetail->layer_qty,
            'total_layer' => $this->print_total_layer($cutting_order_detail),
            'total_yardage' => $cutting_order_detail->isEmpty() ? "" : $this->print_total_yardage($cutting_order_detail),
            'group' => $name,
            'manpower' => count($cutting_order_detail) == 0 ? "" : count($this->manpower($name)),
            'spread_time' => $this->updated_at_status_layer($cutting_order->id),
            'cutting_time' => $this->updated_at_status_cut($cutting_order->id),
            'date' => Carbon::now()->format('d-m-Y H:i:s'),
            'laid_by' => $name,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $cutting_order->created_at)->format('d-m-Y'),
            'printed_by' => Auth::user()->name,
            
        ];

        if(!Auth::user()->hasRole('super_admin')){
            $cutting_order->status_print = true;
            $cutting_order->save();
        }

        $pdf = PDF::loadview('page.cutting-order.report', compact('data','cor_details'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    public function cuttingCompletion()
    {
        $gls = Gl::with('GLCombine')->get();
        return view('page.cutting-order.completion', compact('gls'));
    }

    public function cuttingCompletionReport(Request $request)
    {
        $gl_id = $request->gl_number;
        $gl = GL::find($gl_id);

        $filename = 'Cutting Completion Report GL '. $gl->gl_number . '.pdf';
        $layingPlanning = LayingPlanning::with(['gl','gl.buyer', 'color', 'style','fabricCons','fabricType', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize', 'layingPlanningDetail.cuttingOrderRecord', 'layingPlanningDetail.fabricRequisition', 'layingPlanningDetail.fabricRequisition.fabricIssue', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail'])
            ->whereHas('gl', function($query) use ($gl_id) {
                if ($gl_id != null) {
                    $query->where('id', $gl_id);
                }
            })
            ->orderBy('id', 'asc')
            ->get();
        
        if($layingPlanning->isEmpty()){
            return redirect()->route('cutting-order.cutting-completion')->with('error', 'Planning from GL '. $gl->gl_number .' was not found');
        }
        
        $buyer_list = [];
        $style_list = [];
        $fabric_type_list = [];
        $fabric_cons_list = [];
        foreach ($layingPlanning as $key => $lp) {
            $buyer_list[] = $lp->gl->buyer->name;
            $style_list[] = $lp->style->style;
            $fabric_type_list[] = $lp->fabricType->name;
            $fabric_cons_list[] = $lp->fabricCons->name;
        }

        // ## Data for Completion Header
        $completion_data = [];
        $total_mi_qty = array_sum(array_column($layingPlanning->toArray(), 'order_qty'));
        $completion_data['total_mi_qty'] = $total_mi_qty;
        $completion_data['gl_number'] = $gl->gl_number;

        // ## karena sebenarnya bisa bervariasi permasing masing laying planning. untuk sekarang data seperti fabric_po, style, buyer, fabric_type, fabric_cons akan mengambil dari laying planning pertama dulu saja
        $completion_data['fabric_po'] = $layingPlanning[0]->fabric_po;
        $completion_data['buyer'] = $layingPlanning[0]->buyer->name;
        $completion_data['style'] = $layingPlanning[0]->style->style;
        $completion_data['fabric_type'] = $layingPlanning[0]->fabricType->name;
        $completion_data['fabric_cons'] = $layingPlanning[0]->fabricCons->name;
        $completion_data['plan_date'] = $layingPlanning[0]->plan_date;
        $completion_data['delivery_date'] = $layingPlanning[0]->delivery_date;

        $fabric_consumption = [];

        foreach ($layingPlanning as $key_lp => $lp) {
            $cut_qty_per_size = [];
            $cut_qty_all_size = 0;
            
            $replacement_qty_per_size = [];
            $replacement_qty_all_size = 0;
            
            $diff_qty_per_size = [];
            $diff_qty_all_size = 0;

            foreach ($lp->layingPlanningSize as $key_lp_size => $lp_size) {
                $cut_qty_size = 0;
                $replacement_qty = 0;
                $diff_qty_size = 0;

                foreach ($lp->layingPlanningDetail as $lp_detail) {
                    if(!Str::contains(Str::lower($lp_detail->marker_code), 'repl')){
                        foreach($lp_detail->layingPlanningDetailSize as $lp_detail_size) {
                            if ($lp_detail_size->size_id == $lp_size->size_id) {
                                if(!$lp_detail->cuttingOrderRecord){
                                    $cut_qty_size += 0; 
                                } else {
                                    if(!$lp_detail->cuttingOrderRecord->cut){
                                        $cut_qty_size += 0;
                                    } else {
                                        foreach ($lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail as $cor_detail)
                                        {
                                            $cut_qty_size += $cor_detail->layer * $lp_detail_size->ratio_per_size;
                                        }
                                    }
                                }
                                
                            }
                        }
                        
                    } else {
                        foreach($lp_detail->layingPlanningDetailSize as $lp_detail_size) {
                            if ($lp_detail_size->size_id == $lp_size->size_id) {
                                if(!$lp_detail->cuttingOrderRecord){
                                    $replacement_qty += 0; 
                                } else {
                                    if(!$lp_detail->cuttingOrderRecord->cut){
                                        $replacement_qty += 0;
                                    } else {
                                        foreach ($lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail as $cor_detail)
                                        {
                                            $replacement_qty += $cor_detail->layer * $lp_detail_size->ratio_per_size;
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                }
                
                $cut_qty_per_size[$key_lp_size] = $cut_qty_size;
                $replacement_qty_per_size[$key_lp_size] = $replacement_qty;
                $diff_qty_size = $cut_qty_size - $lp_size->quantity;
                $diff_qty_per_size[$key_lp_size] = ($diff_qty_size > 0) ? '+' . $diff_qty_size : $diff_qty_size;
            }

            $cut_qty_all_size = array_sum($cut_qty_per_size);
            $replacement_qty_all_size = array_sum($replacement_qty_per_size);
            $diff_qty_all_size = array_sum($diff_qty_per_size);

            $layingPlanning[$key_lp]->cut_qty_per_size = $cut_qty_per_size;
            $layingPlanning[$key_lp]->cut_qty_all_size = $cut_qty_all_size;

            $layingPlanning[$key_lp]->replacement_qty_per_size = $replacement_qty_per_size;
            $layingPlanning[$key_lp]->replacement_qty_all_size = $replacement_qty_all_size;
            
            $layingPlanning[$key_lp]->diff_qty_per_size = $diff_qty_per_size;
            $layingPlanning[$key_lp]->diff_qty_all_size = ($diff_qty_all_size > 0) ? '+' . $diff_qty_all_size : $diff_qty_all_size;
            
            $diff_percentage = round((($cut_qty_all_size / $lp->order_qty) * 100) , 1);
            $diff_percentage_color = $diff_percentage < 100 ? 'red' : ($diff_percentage > 100 ? 'blue' : '');
            $layingPlanning[$key_lp]->diff_percentage = $diff_percentage;
            $layingPlanning[$key_lp]->diff_percentage_color = $diff_percentage_color;
            
            $layingPlanning[$key_lp]->color_colspan = count($cut_qty_per_size);


            // ## calculate fabric consuption

            $fabric_request = 0; // ## total length by laying planning detail
            $fabric_received = 0; // ## total length by roll sticker in COR Detail
            $diff_request_and_received = 0; // ## selisih antara yang diminta (request) dan yang diterima (received)
            $actual_used = 0; // ## total length by marker length in laying planning detail di kali dengan layer di cor detail
            $diff_received_and_used = 0; // ##  selisih antara yang diterima (received) dengan yang digunakan (used)

            foreach ($lp->layingPlanningDetail as $key_lp_detail => $lp_detail) {
                $fabric_request += $lp_detail->total_length;
                if($lp_detail->cuttingOrderRecord) {
                    $fabric_received += $lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail->sum('yardage');
                    $actual_used += $lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail->sum('layer') * $lp_detail->marker_length;
                }
            }

            $diff_request_and_received = round($fabric_received - $fabric_request, 3);
            $diff_request_and_received = ($diff_request_and_received > 0) ? '+' . $diff_request_and_received : $diff_request_and_received;
            $diff_received_and_used = round($fabric_received - $actual_used, 3);
            $diff_received_and_used = ($diff_received_and_used > 0) ? '+' . $diff_received_and_used : $diff_received_and_used;
            
            $fabric_consumption[$key_lp] = (object) [
                'color' => $lp->color->color,
                'fabric_request' => $lp->layingPlanningDetail->sum('total_length'),
                'fabric_received' => $fabric_received,
                'diff_request_and_received' => $diff_request_and_received,
                'actual_used' => $actual_used,
                'diff_received_and_used' => $diff_received_and_used,
            ];
        }

        $completion_data['total_output_qty'] = $layingPlanning->sum('cut_qty_all_size');
        $completion_data['total_replacement'] = $layingPlanning->sum('replacement_qty_all_size');
        $diff_output_mi_qty = $completion_data['total_output_qty'] - $total_mi_qty;
        $completion_data['diff_output_mi_qty'] = ($diff_output_mi_qty > 0) ? '+' . $diff_output_mi_qty : $diff_output_mi_qty;
        
        $laying_plannings = $layingPlanning->chunk(2);
        
        $data = [
            'laying_plannings' => $laying_plannings,
            'completion_data' => (object) $completion_data,
            'fabric_consumption' => $fabric_consumption,
        ];

        // return view('page.cutting-order.completion-report', $data);
        $pdf = PDF::loadview('page.cutting-order.completion-report', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    // !! nextnya hapus aja yang ini
    public function cuttingCompletionReport_old(Request $request)
    {
        $start_cut = $request->start_cut;
        if($start_cut == null){
            $start_cut = Carbon::now()->toDateString();
        }
        $finish_cut = $request->finish_cut;
        if($finish_cut == null){
            $finish_cut = Carbon::now()->toDateString();
        }
        $gl_number = $request->gl_number;
        $filename = 'Cutting Completion Report' . '.pdf';
        $layingPlanning = LayingPlanning::with(['gl', 'color', 'style', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize', 'layingPlanningDetail.cuttingOrderRecord', 'layingPlanningDetail.fabricRequisition', 'layingPlanningDetail.fabricRequisition.fabricIssue', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail'])
                ->whereHas('gl', function($query) use ($gl_number) {
                    if ($gl_number != null) {
                        $query->where('id', $gl_number);
                    }
                })
                ->orderBy('id', 'asc')
                ->get();
        
        if($layingPlanning->isEmpty()){
            return redirect()->route('cutting-order.cutting-completion')->with('error', 'Planning from the related gl was not found');
        }
        
        $total_mi_qty = array_sum(array_column($layingPlanning->toArray(), 'order_qty'));
        $total_cut_qty = 0;
        $total_diff_order_and_actual = 0;

        foreach ($layingPlanning as $key_lp => $lp) {
            $cut_qty_per_lp = 0;
            $cut_qty_per_size = [];
            $cut_qty_all_size = 0;
            
            $diff_qty_per_size = [];
            $diff_qty_all_size = 0;

            $diff_percentage;
            
            foreach ($lp->layingPlanningSize as $key_lp_size => $lp_size) {
                $cut_qty_size = 0;
                $diff_qty_size = 0;

                foreach ($lp->layingPlanningDetail as $lp_detail) {
                    foreach($lp_detail->layingPlanningDetailSize as $lp_detail_size) {
                        if ($lp_detail_size->size_id == $lp_size->size_id) {
                            if(!$lp_detail->cuttingOrderRecord){
                                $cut_qty_size += 0; 
                            } else {
                                if(!$lp_detail->cuttingOrderRecord->cut){
                                    $cut_qty_size += 0;
                                } else {
                                    foreach ($lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail as $cor_detail)
                                    {
                                        $cut_qty_size += $cor_detail->layer * $lp_detail_size->ratio_per_size;
                                    }
                                }
                            }
                            
                        }
                    }
                }
                
                $cut_qty_per_size[$key_lp_size] = $cut_qty_size;
                $diff_qty_size = $cut_qty_size - $lp_size->quantity;
                $diff_qty_per_size[$key_lp_size] = ($diff_qty_size > 0) ? '+' . $diff_qty_size : $diff_qty_size;
            }

            $cut_qty_all_size = array_sum($cut_qty_per_size);
            $diff_qty_all_size = array_sum($diff_qty_per_size);

            $layingPlanning[$key_lp]->cut_qty_per_size = $cut_qty_per_size;
            $layingPlanning[$key_lp]->cut_qty_all_size = $cut_qty_all_size;

            $layingPlanning[$key_lp]->diff_qty_per_size = $diff_qty_per_size;
            $layingPlanning[$key_lp]->diff_qty_all_size = ($diff_qty_all_size > 0) ? '+' . $diff_qty_all_size : $diff_qty_all_size;
            
            $diff_percentage = round((($cut_qty_all_size / $lp->order_qty) * 100) , 1);
            $diff_percentage_color = $diff_percentage < 100 ? 'red' : ($diff_percentage > 100 ? 'blue' : '');
            $layingPlanning[$key_lp]->diff_percentage = $diff_percentage;
            $layingPlanning[$key_lp]->diff_percentage_color = $diff_percentage_color;
            
        }

        $data = [
            'layingPlanning' => $layingPlanning,
            'start_cut' => $start_cut,
            'finish_cut' => $finish_cut,
            'gl_number' => $gl_number
        ];

        // return view('page.cutting-order.completion-report', compact('data'));
        $pdf = PDF::loadview('page.cutting-order.completion-report', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    public function statusCuttingOrderRecord()
    {
        $gls = Gl::with('GLCombine')->get();
        $statusLayer = StatusLayer::all();
        $statusCut = StatusCut::all();
        return view('page.cutting-order.status-cutting-order-record', compact('gls', 'statusLayer', 'statusCut'));
    }

    public function printStatusCuttingOrderRecord(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $gl_number = $request->gl_number;
        $status_layer = $request->status_layer;
        $status_cut = $request->status_cut;

        // ## adjust start_date and end_date for day and night shift
        $start_datetime =  Carbon::parse($date_start)->format('Y-m-d 07:00:00');
        $end_datetime =  Carbon::parse($date_end)->addDay()->format('Y-m-d 06:59:00');

        $cuttingOrderRecord = CuttingOrderRecord::with(['statusLayer', 'statusCut', 'CuttingOrderRecordDetail', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanning.gl', 'layingPlanningDetail.layingPlanning.color', 'layingPlanningDetail.layingPlanning.style'])
            ->whereHas('layingPlanningDetail', function($query) use ($gl_number) {
                $query->whereHas('layingPlanning', function($query) use ($gl_number) {
                    if ($gl_number != null) {
                        $query->where('gl_id', $gl_number);
                    }
                });
            })
            ->whereHas('statusLayer', function($query) use ($status_layer) {
                if ($status_layer != null) {
                    $query->where('id', $status_layer);
                }
            })
            ->whereHas('statusCut', function($query) use ($status_cut) {
                if ($status_cut != null) {
                    $query->where('id', $status_cut);
                }
            })
            ->where(function($query) use ($start_datetime, $end_datetime) {
                $query->where('cut', '>=', $start_datetime)
                      ->where('cut', '<=', $end_datetime);
            })
            ->orderBy('serial_number', 'asc')
            ->get();

        $cuttingOrderRecord = $cuttingOrderRecord->sortBy(function($item) {
            return $item->layingPlanningDetail->layingPlanning->color->color;
        });
        $cuttingOrderRecord = $gl_number == null ? $cuttingOrderRecord->sortBy(function($item) {
            return $item->layingPlanningDetail->layingPlanning->gl->gl_number;
        }) : $cuttingOrderRecord->sortBy(function($item) {
            return $item->serial_number;
        });
        $data = [
            'cuttingOrderRecord' => $cuttingOrderRecord,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'gl_number' => $gl_number == null ? '' : ($cuttingOrderRecord->first() == null ? '' : $cuttingOrderRecord->first()->layingPlanningDetail->layingPlanning->gl->gl_number),
            'status_layer' => $status_layer == null ? '' : $this->getStatusLayer($status_layer),
            'status_cut' => $status_cut == null ? '' : $this->getStatusCut($status_cut),
        ];

        $pdf = PDF::loadview('page.cutting-order.report-status', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream('Report Status Cutting Order Record.pdf');
    }

    public function formatDate($date_start, $date_end)
    {
        if ($date_start == null && $date_end == null) {
            $date_start = Carbon::now()->toDateString();
            $date_end = Carbon::now()->toDateString();
        } else if ($date_start == null && $date_end != null) {
            $date_start = $date_end;
        } else if ($date_start != null && $date_end == null) {
            $date_end = $date_start;
        }
        return [$date_start, $date_end];
    }

    public function getStatusLayer($id)
    {
        $statusLayer = StatusLayer::find($id);
        if ($statusLayer->name == 'not completed') {
            return 'Belum Layer';
        } else if ($statusLayer->name == 'completed') {
            return 'Sudah Layer';
        } else {
            return 'Over Layer';
        }
    }

    public function getStatusCut($id)
    {
        $statusCut = StatusCut::find($id);
        if ($statusCut->name == 'belum') {
            return 'Belum Potong';
        } else {
            return 'Sudah Potong';
        }
    }

    public function getCuttingOrderRecordByDate($date)
    {
        $data = CuttingOrderRecord::all();
        $data = $data->filter(function($item) use ($date) {
            return Carbon::parse($item->created_at)->format('Y-m-d') == $date;
        });
        $pdf = PDF::loadView('page.cutting-order.daily-cutting-output-report', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream('Daily Cutting Output Report.pdf');
    }

    function getDataCompleteCuttingOrder(Request $request) {
        $input = $request->all();
        // $date_filter = $request->filter_date ? $request->filter_date : Carbon::now()->toDateString();
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize.size', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color', 'cuttingTicket', 'cuttingTicket.cuttingOrderRecordDetail', 'cuttingTicket.cuttingOrderRecordDetail.color', 'cuttingTicket.size', 'cuttingTicket.size.size', 'cuttingTicket.size.ratio_per_size'])
            ->whereHas('layingPlanningDetail', function($query) use ($input) {
                $query->whereHas('layingPlanning', function($query) use ($input) {
                    $query->where('gl_id', 12);
                    // $query->where('color_id', $input['color_id']);
                });
            })
            // ->whereHas('cuttingOrderRecordDetail', function($query) use ($input) {
            //     $query->where('layer', $query->sum('layer'));
            // })
            ->where('created_at', 'like', '2023-04-17')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $cuttingOrderRecord
        ], 200);
    }
    function generate_serial_number($layingPlanningDetail){
        $gl_number = $layingPlanningDetail->layingPlanning->gl->gl_number;
        $color_code = $layingPlanningDetail->layingPlanning->color->color_code;
        $fabric_type = $layingPlanningDetail->layingPlanning->fabricType->name;
        $style = $layingPlanningDetail->layingPlanning->style->id;
        $fabric_type = Str::substr($fabric_type, 0, 4);
        $fabric_type = Str::upper($fabric_type);
        $fabric_type = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_type);
        $fabric_cons = $layingPlanningDetail->layingPlanning->fabricCons->name;
        $fabric_cons = Str::substr($fabric_cons, 0, 4);
        $fabric_cons = Str::upper($fabric_cons);
        $fabric_cons = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_cons);
        $table_number = Str::padLeft($layingPlanningDetail->table_number, 3, '0');
        $getDuplicateSN = CuttingOrderRecord::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $duplicateSN = count($getDuplicateSN) + 1;
        $duplicateSN = Str::padLeft($duplicateSN, 2, '0');
        
        $serial_number = "COR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-{$duplicateSN}-{$table_number}";
        $checkDuplicateSN = CuttingOrderRecord::where('serial_number', $serial_number)->first();
        if ($checkDuplicateSN != null) {
            $serial_number = $serial_number . "-1";
        }

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

    public function print_total_size_ratio($layingPlanningDetail) {
        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $total_size_ratio = 0;
        foreach( $get_size_ratio as $key => $size ) {
            $total_size_ratio += $size->ratio_per_size;
        }
        return $total_size_ratio;
    }

    public function print_total_layer($cutting_order_detail) {
        $total_layer = 0;
        foreach( $cutting_order_detail as $key => $detail ) {
            $total_layer += $detail->layer;
        }
        return $total_layer == 0 ? "" : $total_layer;
    }

    public function print_total_yardage($cutting_order_detail) {
        $total_yardage = 0;
        foreach( $cutting_order_detail as $key => $detail ) {
            $total_yardage += $detail->yardage;
        }
        return $total_yardage;
    }

    public function manpower($name) {
        $group = Groups::all();
        $manpower = [];
        foreach ($group as $key => $value) {
            $user_group = UserGroups::where('group_id', $value->id)->get();
            $manpower[] = [
                'group_name' => $value->group_name,
                'manpower' => count($user_group)
            ];
        }
        return $manpower;
    }

    public function updated_at_status_cut($cutting_order_id) {
        $cutting_order = CuttingOrderRecord::with(['statusCut'])
        ->whereHas('statusCut', function($query) {
            $query->where('name', '=', 'belum', 'or', 'name', '=', 'sudah');
        })
        ->where('id', $cutting_order_id)
        ->first();
        if ($cutting_order->cut == null) {
            $updated_at = "";
            return $updated_at;
        } else {
            $updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $cutting_order->cut)->format('d-m-Y H:i:s');
            return $updated_at;
        }
    }

    public function updated_at_status_layer($cutting_order_id) {
        $cutting_order = CuttingOrderRecord::with(['statusLayer'])
        ->whereHas('statusLayer', function($query) {
            $query->where('name', '=', 'completed', 'or', 'name', '=', 'over Layer');
        })
        ->where('id', $cutting_order_id)
        ->first();

        $cutting_order_detail = CuttingOrderRecordDetail::where('cutting_order_record_id', $cutting_order_id)->get();
        
        if ($cutting_order_detail->isEmpty()) {
            $updated_at = "";
            return $updated_at;
        } else {
            $updated_at = Carbon::createFromFormat('Y-m-d H:i:s', ($cutting_order_detail->first()->updated_at ?? Carbon::now())
            )->format('d-m-Y H:i:s');
            return $updated_at;
        }
        return $updated_at;
    }

    public function chartCuttingOrder() {
        $cor = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])
            ->select('cutting_order_records.id','laying_planning_detail_id','serial_number')->get();
        
        $cor_group = $cor->groupBy(function($item) {
            $sum_layer = 0;
            foreach ($item->cuttingOrderRecordDetail as $detail) {
                $sum_layer += $detail->layer;
            }
            if ($sum_layer == $item->layingPlanningDetail->layer_qty) {
                return 'complete';
            } else if ($sum_layer > $item->layingPlanningDetail->layer_qty) {
                return 'over layer';
            } else {
                return 'not complete';
            }
        });
        
        $cor_count = $cor_group->map(function ($item, $key) {
            return count($item);
        });
        
        $data = [
            'complete' => $cor_count['complete'],
            'over layer' => $cor_count['over layer'],
            'not complete' => $cor_count['not complete'],
        ];
        
        return view('home', compact('data'));
    }
        
}