<?php

namespace App\Http\Controllers\LayingPlanning;

use App\Http\Controllers\Controller;
use App\Models\LayingPlanning;
use App\Models\LayingPlanningSize;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $buyers = DB::table('buyers')->get();
        $styles = DB::table('styles')->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();
        return view('page.layingPlanning.add', compact('gls', 'buyers', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes'));
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

        $layingData = [
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
            ->with('success', 'Laying Planning created successfully.');
    }

    public function show($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->find($id);
        $details = LayingPlanningDetail::where('laying_planning_id', $id)->get();
        foreach($details as $key => $value) {
            $details[$key]->cor_status = $value->cuttingOrderRecord ? 'disabled' : '';
        }

        $get_size_list = $data->layingPlanningSize()->get();
        $size_list = [];
        foreach ($get_size_list as $key => $size) {
            $size_list[] = $size->size;
        }
        
        return view('page.layingPlanning.detail', compact('data', 'details','size_list'))   ;
    }

    public function layingQrcode($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->where('id', $id)->first();
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
        $buyers = DB::table('buyers')->get();
        $styles = DB::table('styles')->where('gl_id',$layingPlanning->gl_id)->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();

        // $layingPlanning->delivery_date = date('d/m/Y', strtotime($layingPlanning->delivery_date));
        $layingPlanning->plan_date = date('m/d/Y', strtotime($layingPlanning->plan_date));
        
        $layingPlanningSizes = LayingPlanningSize::where('laying_planning_id', $layingPlanning->id)->orderBy('size_id')->get();

        return view('page.layingPlanning.edit', compact('gls', 'buyers', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes','layingPlanning','layingPlanningSizes'));
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
            return redirect('laying-planning-create')
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
        
        return redirect('laying-planning');
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
        // dd($request->all());

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
            ->with('success', 'Laying Planning created successfully.');

    }

    public function detail_edit($id)
    {
        try {
            $layingPlanningDetail = LayingPlanningDetail::with('layingPlanningDetailSize')->find($id);
            $date_return = [
                'status' => 'success',
                'data'=> $layingPlanningDetail,
                'message'=> 'Data Detail Laying Planning berhasil di ambil',
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
                ->with('success', 'Detail Laying Planning updated successfully.');
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
                'message'=> 'Data Detail Laying Planning berhasil di hapus',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
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
}
