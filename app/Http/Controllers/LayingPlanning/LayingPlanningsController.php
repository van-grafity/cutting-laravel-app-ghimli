<?php

namespace App\Http\Controllers\LayingPlanning;

use App\Http\Controllers\Controller;
use App\Models\Gl;
use App\Models\Color;
use App\Models\LayingPlanning;
use App\Models\LayingPlanningSize;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\PDF;

class LayingPlanningsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->get();
        return view('page.layingPlanning.index', compact('data'));
    }

    public function dataLayingPlanning (){
        $query = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])
            ->select('laying_plannings.id','laying_plannings.serial_number','laying_plannings.gl_id','laying_plannings.style_id','laying_plannings.buyer_id','laying_plannings.color_id','laying_plannings.fabric_type_id')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('gl_number', function ($data){
                return $data->gl->gl_number;
            })
            ->addColumn('style', function ($data){
                return $data->style->style;
            })
            ->addColumn('buyer', function ($data){
                return $data->buyer->name;
            })
            ->addColumn('color', function ($data){
                return $data->color->color;
            })
            ->addColumn('fabric_type', function ($data){
                return $data->fabricType->description;
            })
            ->addColumn('action', function($data){
                return '
                <a href="'.route('laying-planning.edit',$data->id).'" class="btn btn-primary btn-sm"">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_layingPlanning('.$data->id.')">Delete</a>
                <a href="'.route('laying-planning.show',$data->id).'" class="btn btn-info btn-sm mt-1">Detail</a>
                <a href="'.route('laying-planning.report',$data->serial_number).'" target="_blank" class="btn btn-info btn-sm mt-1">Report</a>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function layingCreate()
    {
        $gls = DB::table('gls')->get();
        $styles = DB::table('styles')->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();
        return view('page.layingPlanning.add', compact('gls', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'gl' => 'required',
            'buyer' => 'required',
            'style' => 'required',
            'color' => 'required',
            'fabric_type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'required' => 'The :attribute field is required.',
        ]);

        if ($validator->fails()) {
            return redirect('laying-planning-create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $serial_number = $this->generate_serial_number($request->gl,$request->color);
        $layingData = [
            'serial_number' => $serial_number,
            'gl_id' => $request->gl,
            'style_id' => $request->style,
            'buyer_id' => $request->buyer,
            'color_id' => $request->color,
            'order_qty' => $request->order_qty,
            'delivery_date' => Carbon::createFromFormat('d/m/Y', $request->delivery_date)->format('y-m-d'),
            'plan_date' => Carbon::createFromFormat('d/m/Y', $request->plan_date)->format('y-m-d'),
            'fabric_po' => $request->fabric_po,
            'fabric_cons_id' => $request->fabric_cons,
            'fabric_type_id' => $request->fabric_type,
            'fabric_cons_qty' => $request->fabric_cons_qty,
            'fabric_cons_desc' => $request->fabric_cons_desc,
        ];
        $insertLayingData = LayingPlanning::create($layingData);

        $selected_sizes = $request->laying_planning_size_id;
        $selected_sizes_qty = $request->laying_planning_size_qty;
        foreach ($selected_sizes as $key => $size_id) {
            $laying_planning_size = [
                'size_id' => $size_id,
                'quantity' => $selected_sizes_qty[$key],
                'laying_planning_id' => $insertLayingData->id,
            ];
            $insertLayingSize = LayingPlanningSize::create($laying_planning_size);
        }

        return redirect()->route('laying-planning.index')
            ->with('success', 'Laying Planning successfully Added!');
    }

    public function show($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->find($id);
        $details = LayingPlanningDetail::where('laying_planning_id', $id)->get();
        $total_order_qty = LayingPlanning::where('gl_id', $data->gl_id)->sum('order_qty');
        $data->total_order_qty = $total_order_qty;
        $total_pcs_all_table = 0;
        $total_length_all_table = 0;
        
        $delivery_date = Carbon::createFromFormat('Y-m-d', $data->delivery_date)->format('d-m-Y');
        $data->delivery_date = $delivery_date;
        $plan_date = Carbon::createFromFormat('Y-m-d', $data->plan_date)->format('d-m-Y');
        $data->plan_date = $plan_date;

        foreach($details as $key => $value) {
            $details[$key]->cor_status = $value->cuttingOrderRecord ? 'disabled' : '';
            $total_pcs_all_table = $total_pcs_all_table + $value->total_all_size;
            $total_length_all_table = $total_length_all_table + $value->total_length;
        }

        $data->total_pcs_all_table = $total_pcs_all_table;
        $data->total_length_all_table = $total_length_all_table;

        $get_size_list = $data->layingPlanningSize()->get();
        $size_list = [];
        foreach ($get_size_list as $key => $size) {
            $size_list[] = $size->size;
        }
        return view('page.layingPlanning.detail', compact('data', 'details','size_list'))   ;
    }

    public function layingPlanningReport($serial_number)
    {
        $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('serial_number', $serial_number)->first();
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($serial_number) {
            $query->where('serial_number', $serial_number);
        })->get();
        // $data = LayingPlanning::with(['layingPlanningSize', 'layingPlanningSize.size', 'gl', 'style', 'buyer', 'color', 'fabricType'])->where('serial_number', $serial_number)->first();
        // $details = LayingPlanningDetail::where('laying_planning_id', 1)->get();
        $pdf = PDF::loadView('page.layingPlanning.report', compact('data', 'details'))->setPaper('a4', 'landscape');
        // return $pdf->stream();
        return $pdf->stream();
    }

    public function layingQrcode($id)
    {
        $data = LayingPlanning::with(['layingPlanningSize','gl', 'style', 'buyer', 'color', 'fabricType'])->where('id', $id)->first();
        $qrCode = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$data->gl;
        return view('page.layingPlanning.qrcode', compact('data', 'qrCode'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LayingPlanning  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function edit(LayingPlanning $layingPlannings, $id)
    {
        
        $layingPlanning = LayingPlanning::find($id);
        $gls = DB::table('gls')->get();
        $styles = DB::table('styles')->where('gl_id',$layingPlanning->gl_id)->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();

        // $layingPlanning->delivery_date = date('d/m/Y', strtotime($layingPlanning->delivery_date));
        $layingPlanning->plan_date = date('m/d/Y', strtotime($layingPlanning->plan_date));
        
        $layingPlanningSizes = LayingPlanningSize::where('laying_planning_id', $layingPlanning->id)->orderBy('size_id')->get();

        return view('page.layingPlanning.edit', compact('gls', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes','layingPlanning','layingPlanningSizes'));
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LayingPlanning  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LayingPlanning $layingPlannings)
    {
        $rules = [
            'gl' => 'required',
            'buyer' => 'required',
            'style' => 'required',
            'color' => 'required',
            'fabric_type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'required' => 'The :attribute field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('laying-planning.edit', $request->laying_planning_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $layingPlanning = LayingPlanning::find($request->laying_planning_id);

        $layingPlanning->gl_id = $request->gl;
        $layingPlanning->style_id = $request->style;
        $layingPlanning->buyer_id = $request->buyer;
        $layingPlanning->color_id = $request->color;
        $layingPlanning->order_qty = $request->order_qty;
        $layingPlanning->delivery_date = Carbon::createFromFormat('d/m/Y', $request->delivery_date)->format('y-m-d');
        $layingPlanning->fabric_po = $request->fabric_po;
        $layingPlanning->fabric_cons_id = $request->fabric_cons;
        $layingPlanning->fabric_type_id = $request->fabric_type;
        $layingPlanning->fabric_cons_qty = $request->fabric_cons_qty;
        $layingPlanning->fabric_cons_desc = $request->fabric_cons_desc;

        $layingPlanning->save();

        LayingPlanningSize::where('laying_planning_id', $layingPlanning->id)->delete();

        $selected_sizes = $request->laying_planning_size_id;
        $selected_sizes_qty = $request->laying_planning_size_qty;
        foreach ($selected_sizes as $key => $size_id) {
            $laying_planning_size = [
                'size_id' => $size_id,
                'quantity' => $selected_sizes_qty[$key],
                'laying_planning_id' => $layingPlanning->id,
            ];
            $insertLayingSize = LayingPlanningSize::create($laying_planning_size);
        }
        
        return redirect('laying-planning')->with('success', 'Laying Planning '. $layingPlanning->serial_number . " successfully Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LayingPlanning  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $layingPlanning = LayingPlanning::find($id);
            $layingPlanning->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $layingPlanning,
                'message'=> 'Data Laying Planning berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function detail_create(Request $request) 
    {
        $layingPlanning = LayingPlanning::find($request->laying_planning_id);
        if(!$layingPlanning) {
            return redirect('laying-planning-create')
                        ->withInput();
        }
        $getLastDetail = LayingPlanningDetail::where('laying_planning_id', $layingPlanning->id)->orderBy('table_number','desc')->first();

        $layingDetailData = [
            'no_laying_sheet' => $this->generate_no_laying_sheet($layingPlanning),
            'table_number' => $next_table_number = $getLastDetail ? $getLastDetail->table_number + 1 : 1,
            'laying_planning_id' => $layingPlanning->id,
            'layer_qty' => $request->layer_qty,
            'marker_code' => $request->marker_code,
            'marker_yard' => $request->marker_yard,
            'marker_inch' => $request->marker_inch,
            'marker_length' => $request->marker_length,
            'total_length' => $request->marker_total_length,
            'total_all_size' => $request->qty_size_all,
        ];

        $insertLayingDetail = LayingPlanningDetail::create($layingDetailData);
        
        $ratio_size = $request->ratio_size;
        $qty_size = $request->qty_size;
        foreach ($ratio_size as $key => $size_value) {
            $laying_planning_detail_size = [
                'laying_planning_detail_id' => $insertLayingDetail->id,
                'size_id' => $key,
                'ratio_per_size' => $ratio_size[$key],
                'qty_per_size' => $qty_size[$key],
            ];
            $insertPlanningDetailSize = LayingPlanningDetailSize::create($laying_planning_detail_size);
        }

        return redirect()->route('laying-planning.show',$layingPlanning->id)
            ->with('success', 'Data Detail Laying Planning berhasil dibuat.');

    }

    public function detail_edit($id)
    {
        try {
            $layingPlanningDetail = LayingPlanningDetail::with('layingPlanningDetailSize')->find($id);
            $date_return = [
                'status' => 'success',
                'data'=> $layingPlanningDetail,
                'message'=> 'Data Detail Laying Planning berhasil diambil',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    
    public function detail_update(Request $request, $id)
    {
        try {
            $layingPlanningDetail = LayingPlanningDetail::with('layingPlanningDetailSize')->find($id);
            
            $layingPlanningDetail->layer_qty = $request->layer_qty;
            $layingPlanningDetail->marker_code = $request->marker_code;
            $layingPlanningDetail->marker_yard = $request->marker_yard;
            $layingPlanningDetail->marker_inch = $request->marker_inch;
            $layingPlanningDetail->marker_length = $request->marker_length;
            $layingPlanningDetail->total_length = $request->marker_total_length;
            $layingPlanningDetail->total_all_size = $request->qty_size_all;
            $layingPlanningDetail->save();

            $deletePlanningDetailSize = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->delete();
            $ratio_size = $request->ratio_size;
            $qty_size = $request->qty_size;
            foreach ($ratio_size as $key => $size_value) {
                $laying_planning_detail_size = [
                    'laying_planning_detail_id' => $layingPlanningDetail->id,
                    'size_id' => $key,
                    'ratio_per_size' => $ratio_size[$key],
                    'qty_per_size' => $qty_size[$key],
                ];
                $insertPlanningDetailSize = LayingPlanningDetailSize::create($laying_planning_detail_size);
            }

            return redirect()->route('laying-planning.show',$request->laying_planning_id)
                ->with('success', 'Data Detail Laying Planning berhasil diubah.');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function detail_delete($id) 
    {
        try {
            $layingPlanningDetail = LayingPlanningDetail::find($id);
            $layingPlanningDetail->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $layingPlanningDetail,
                'message'=> 'Data Detail Laying Planning berhasil dihapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function detail_duplicate(Request $request){

        $layingPlanningDetail = LayingPlanningDetail::find($request->laying_planning_detail_id);
        $duplicate_qty = $request->duplicate_qty;

        $layingPlanningDetailSize = $layingPlanningDetail->layingPlanningDetailSize;

        for ($i=0; $i < $duplicate_qty; $i++) { 
            
            $layingPlanning = $layingPlanningDetail->layingPlanning;
            $getLastDetail = LayingPlanningDetail::where('laying_planning_id', $layingPlanning->id)->orderBy('table_number','desc')->first();
            

            $layingDetailData = [
                'no_laying_sheet' => $this->generate_no_laying_sheet($layingPlanning),
                'table_number' => $getLastDetail ? $getLastDetail->table_number + 1 : 1,
                'laying_planning_id' => $layingPlanning->id,
                'layer_qty' => $layingPlanningDetail->layer_qty,
                'marker_code' => $layingPlanningDetail->marker_code,
                'marker_yard' => $layingPlanningDetail->marker_yard,
                'marker_inch' => $layingPlanningDetail->marker_inch,
                'marker_length' => $layingPlanningDetail->marker_length,
                'total_length' => $layingPlanningDetail->total_length,
                'total_all_size' => $layingPlanningDetail->total_all_size,
            ];

            $insertLayingDetail = LayingPlanningDetail::create($layingDetailData);
            
            $ratio_size = $request->ratio_size;
            $qty_size = $request->qty_size;
            foreach ($layingPlanningDetailSize as $key => $detailSize) {
                $laying_planning_detail_size = [
                    'laying_planning_detail_id' => $insertLayingDetail->id,
                    'size_id' => $detailSize->size_id,
                    'ratio_per_size' => $detailSize->ratio_per_size,
                    'qty_per_size' => $detailSize->qty_per_size,
                ];
                $insertPlanningDetailSize = LayingPlanningDetailSize::create($laying_planning_detail_size);
            }

        }

        return redirect()->route('laying-planning.show',$layingPlanning->id)
            ->with('success', 'Data Detail Laying Planning berhasil diduplicate sebanyak '. $duplicate_qty .' kali');

    }

    function generate_no_laying_sheet($layingPlanning) {
        $getLastDetail = LayingPlanningDetail::where('laying_planning_id', $layingPlanning->id)->orderBy('table_number','desc')->first();
        $gl_number = explode('-', $layingPlanning->gl->gl_number)[0];
        
        if(!$getLastDetail){
            $no_laying_sheet = $gl_number. "-" . Str::padLeft('1', 3, '0');
        } else {
            $next_table_number = $getLastDetail->table_number + 1;
            $no_laying_sheet = $gl_number. "-" . Str::padLeft($next_table_number, 3, '0');
        }
        return $no_laying_sheet;
    }

    function generate_serial_number($gl_id = null, $color_id = null) {
        if (!$gl_id || !$color_id) {
            return 0;
        }
        $gl = Gl::find($gl_id);
        $gl_number = explode('-', $gl->gl_number)[0];
        $color = Color::find($color_id);

        $getDuplicateSN = LayingPlanning::select('gl_id','color_id')
                            ->where('gl_id', $gl_id)
                            ->where('color_id', $color_id)
                            ->get();
        $count_duplicate = $getDuplicateSN->count();

        if ($count_duplicate <= 0) {
            $serial_number = "LP-{$gl_number}-{$color->color_code}";
            return $serial_number;
        } else {
            $count_duplicate++;
            $serial_number = "LP-{$gl_number}-{$color->color_code}.{$count_duplicate}";
            return $serial_number;
        }
    }
}
