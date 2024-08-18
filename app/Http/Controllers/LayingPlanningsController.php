<?php

namespace App\Http\Controllers;

use App\Models\Gl;
use App\Models\Color;
use App\Models\Style;
use App\Models\LayingPlanning;
use App\Models\LayingPlanningSize;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningDetailSize;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\FabricType;
use App\Models\FabricCons;
use App\Models\FabricRequisition;
use App\Models\GlCombine;
use App\Models\LayingPlanningSizeGlCombine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use PDF;

class LayingPlanningsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('page.layingPlanning.index');
    }

    public function dataLayingPlanning (){
        $query = DB::table('laying_plannings')
            ->join('gls', 'laying_plannings.gl_id', '=', 'gls.id')
            ->join('styles', 'laying_plannings.style_id', '=', 'styles.id')
            ->join('colors', 'laying_plannings.color_id', '=', 'colors.id')
            ->join('buyers', 'laying_plannings.buyer_id', '=', 'buyers.id')
            ->join('fabric_types', 'laying_plannings.fabric_type_id', '=', 'fabric_types.id')
            ->join('fabric_cons', 'laying_plannings.fabric_cons_id', '=', 'fabric_cons.id')
            ->select('laying_plannings.*', 'gls.gl_number', 'styles.style', 'colors.color', 'fabric_types.description as fabric_type', 'buyers.name as buyer', 'fabric_cons.description as fabric_cons')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            // ->addColumn('serial_number', function ($data){
            //     return '<a href="'.route('laying-planning.show',$data->id).'">'.$data->serial_number.'</a>';
            // })
            ->addColumn('gl_number', function ($data){
                return $data->gl_number;
            })
            ->addColumn('style', function ($data){
                return $data->style;
            })
            ->addColumn('buyer', function ($data){
                return $data->buyer;
            })
            ->addColumn('color', function ($data){
                return $data->color;
            })
            ->addColumn('fabric_type', function ($data){
                return $data->fabric_type;
            })
            ->addColumn('action', function($data){
                // $action = '<a href="'.route('laying-planning.edit',$data->id).'" class="btn btn-primary btn-sm"">Edit</a>
                // <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_layingPlanning('.$data->id.')">Delete</a>
                // <a href="'.route('laying-planning.show',$data->id).'" class="btn btn-info btn-sm mt-1">Detail</a>
                // <a href="'.route('laying-planning.report',$data->id).'" target="_blank" class="btn btn-info btn-sm mt-1">Report</a>';
                // return $action;

                $action = '';
                if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('cutter')){
                    $action = '<a href="'.route('laying-planning.edit',$data->id).'" class="btn btn-primary btn-sm"">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_layingPlanning('.$data->id.')">Delete</a>
                    <a href="'.route('laying-planning.show',$data->id).'" class="btn btn-info btn-sm mt-1">Detail</a>
                    <a href="'.route('laying-planning.report',$data->id).'" target="_blank" class="btn btn-info btn-sm mt-1">Report</a>';
                } else {
                    $action = '<a href="'.route('laying-planning.show',$data->id).'" class="btn btn-info btn-sm mt-1">Detail</a>
                    <a href="'.route('laying-planning.report',$data->id).'" target="_blank" class="btn btn-info btn-sm mt-1">Report</a>';
                }
                return $action;
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
        $gls = GL::with('GLCombine')->get();
        $styles = DB::table('styles')->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();
        $gl_combines = GlCombine::all();
        return view('page.layingPlanning.add', compact('gls', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes','gl_combines'));
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
            'unique' => 'The :attribute field is duplicate.',
        ]);

        if ($validator->fails()) {
            return redirect('laying-planning-create')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            $serial_number = $this->generate_serial_number($request->gl,$request->color, $request->style, $request->fabric_type, $request->fabric_cons);
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
                'remark' => $request->remark,
            ];
            $insertLayingData = LayingPlanning::create($layingData);

            $selected_sizes = $request->laying_planning_size_id;
            $selected_sizes_qty = $request->laying_planning_size_qty;
            $gl_combine_id = $request->gl_combine_id;
            foreach ($selected_sizes as $key => $size_id) {
                $laying_planning_size = [
                    'size_id' => $size_id,
                    'quantity' => $selected_sizes_qty[$key],
                    'laying_planning_id' => $insertLayingData->id,
                ];
                $insertLayingSize = LayingPlanningSize::create($laying_planning_size);
                $id_laying_planning_size = $insertLayingSize->id;
                if (isset($gl_combine_id[$key])) {
                    $id_gl_combine = $gl_combine_id[$key];
                    $laying_planning_size_gl_combine = [
                        'id_laying_planning_size' => $id_laying_planning_size,
                        'id_gl_combine' => $id_gl_combine,
                    ];
                    $insertLayingSizeGlCombine = LayingPlanningSizeGlCombine::create($laying_planning_size_gl_combine);
                }
            }
            return redirect()->route('laying-planning.show',$insertLayingData->id)
                ->with('success', 'Data Laying Planning berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect('laying-planning-create')
                        ->withErrors($th->getMessage())
                        ->withInput();
        }
    }

    public function show($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->find($id);
        $details = LayingPlanningDetail::with(['fabricRequisition'])->where('laying_planning_id', $id)->get();
        $fabric_requisition = FabricRequisition::with(['layingPlanningDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->where('laying_planning_id', $id);
        })->get();
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
            // faric requisition disable
            $details[$key]->fr_status = $value->fabricRequisition ? 'disabled' : '';
            // cuttingOrderRecord->layingPlanningDetail-layingPlanning
            $details[$key]->cor_id = $value->cuttingOrderRecord ? $value->cuttingOrderRecord->id : '';
            $details[$key]->fr_id = $value->fabricRequisition ? $value->fabricRequisition->id : '';
            $details[$key]->cor_status_print = $value->cuttingOrderRecord ? $value->cuttingOrderRecord->status_print : '';
            $details[$key]->fr_status_print = $value->fabricRequisition ? $value->fabricRequisition->status_print : '';
            // $cutting_order_record = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            //     $query->where('laying_planning_id', $id);
            // })->get();
            // $details[$key]->cutting_order_record = $cutting_order_record;
            $total_pcs_all_table = $total_pcs_all_table + $value->total_all_size;
            $total_length_all_table = $total_length_all_table + $value->total_length;
        }

        $data->total_pcs_all_table = $total_pcs_all_table;
        $data->total_length_all_table = $total_length_all_table;

        // $gl_combine = GlCombine::with('layingPlanningSizeGlCombine')->whereHas('layingPlanningSizeGlCombine', function($query) use ($id) {
        //     $query->whereHas('layingPlanningSize', function($query) use ($id) {
        //         $query->where('laying_planning_id', $id);
        //     });
        // })->get();

        $get_size_list = $data->layingPlanningSize()->with('glCombine')->get();
        $size_list = [];
        foreach ($get_size_list as $key => $size) {
            $size_list[] = $size->size;
            $gl_combine_name = "";
            foreach ($size->glCombine as $key => $gl_combine) {
                $gl_combine_name = $gl_combine_name . $gl_combine->glCombine->name . " ";
            }
            $size->size->size = $size->size->size ."". $gl_combine_name;
        }
        $styles = DB::table('styles')->where('gl_id',$data->gl_id)->get();
        return view('page.layingPlanning.detail', compact('data', 'details','size_list','styles'));
    }

    public function dataLayingPlanningDetail($id){
        $query = LayingPlanningDetail::with(['fabricRequisition'])->where('laying_planning_id', $id)->get();
        $total_pcs_all_table = 0;
        $total_length_all_table = 0;

        foreach($query as $key => $value) {
            $query[$key]->cor_status = $value->cuttingOrderRecord ? 'disabled' : '';
            $query[$key]->fr_status = $value->fabricRequisition ? 'disabled' : '';
            $query[$key]->cor_id = $value->cuttingOrderRecord ? $value->cuttingOrderRecord->id : '';
            $cutting_order_record = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($cor) use ($id) {
                $cor->where('laying_planning_id', $id);
            })->get();
            $query[$key]->cutting_order_record = $cutting_order_record;
            $total_pcs_all_table = $total_pcs_all_table + $value->total_all_size;
            $total_length_all_table = $total_length_all_table + $value->total_length;
        }

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                $action = '<a href="javascript:void(0);" class="btn btn-primary btn-sm btn-detail-edit" data-id="'.$data->id.'" data-url="'.route('laying-planning.detail-edit', $data->id).'">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-detail-delete" data-id="'.$data->id.'" data-url="'.route('laying-planning.detail-delete', $data->id).'" >Delete</a>
                <a href="'.route('cutting-order.createNota', $data->id).'" class="btn btn-info btn-sm '.$data->cor_status.'">Create COR</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-dark btn-detail-duplicate" data-id="'.$data->id.'">Duplicate</a>
                <a href="'.route('fabric-requisition.createNota', $data->id).'" class="btn btn-sm btn-outline-dark '.$data->fr_status.'" data-id="'.$data->id.'">Fabric Req</a>';
                return $action;
            })
            ->make(true);
    }
        
    public function layingPlanningReport($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('id', $id)->first();
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($id) {
            $query->where('id', $id);
        })->get();
        $details->load('cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color');
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->whereHas('layingPlanning', function($query) use ($id) {
                $query->where('id', $id);
            });
        })->get();

        // ## Adjust Cut Date using Shift instead real cut date
        foreach ($details as $key => $lp_detail) {
            if(!$lp_detail->cuttingOrderRecord) {
                $lp_detail->cut_date = null;
                continue;
            }
            
            $cut_date = $lp_detail->cuttingOrderRecord->cut;
            if($cut_date) {
                $carbon_real_cut_datetime = Carbon::parse($cut_date);
                $real_cut_date_only = Carbon::parse(date($carbon_real_cut_datetime))->format('Y-m-d');
                
                $start_shift_datetime =  Carbon::parse($real_cut_date_only)->format('Y-m-d 07:00:00');
                
                if($carbon_real_cut_datetime->lt($start_shift_datetime)){
                    $shift_date = $carbon_real_cut_datetime->subDays();
                } else {
                    $shift_date = $carbon_real_cut_datetime;
                }
    
                $lp_detail->cut_date = $shift_date->format('Y-m-d');
            } else {
                $lp_detail->cut_date = null;
            }
        }
        
        

        $pdf = PDF::loadView('page.layingPlanning.report', compact('data', 'details', 'cuttingOrderRecord'))->setPaper('a4', 'landscape');
        
        if(!Auth::user()->hasRole('super_admin') || !Auth::user()->hasRole('merchandiser')){
            $data->status_print = true;
            $data->save();
        }

        return $pdf->stream('laying-planning-report.pdf');
    }

    public function layingPlanningv2Report($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('id', $id)->first();
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($id) {
            $query->where('id', $id);
        })->get();
        $details->load('cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color');
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->whereHas('layingPlanning', function($query) use ($id) {
                $query->where('id', $id);
            });
        })->get();
        
        $pdf = PDF::loadView('page.layingPlanning.report-laying-planning', compact('data', 'details', 'cuttingOrderRecord'))->setPaper('a4', 'landscape');
        
        $data->status_print = true;
        $data->save();

        return $pdf->stream('laying-planning-report.pdf');
    }

    public function cuttingOrderv2Report($id)
    {
        $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('id', $id)->first();
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($id) {
            $query->where('id', $id);
        })->get();
        $details->load('cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color');
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->whereHas('layingPlanning', function($query) use ($id) {
                $query->where('id', $id);
            });
        })->get();
        
        $pdf = PDF::loadView('page.layingPlanning.report-cutting-order', compact('data', 'details', 'cuttingOrderRecord'))->setPaper('a4', 'landscape');
        
        $data->status_print = true;
        $data->save();

        return $pdf->stream('cutting-order-report.pdf');
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
        
        $layingPlanningSizes = LayingPlanningSize::where('laying_planning_id', $layingPlanning->id)->get();

        return view('page.layingPlanning.edit', compact('gls', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes','layingPlanning','layingPlanningSizes'));
    }

    public function duplicate(Request $request, $id)
    {
        $layingPlanning = LayingPlanning::find($id);
        $gls = DB::table('gls')->get();
        $styles = DB::table('styles')->where('gl_id',$layingPlanning->gl_id)->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();

        // $layingPlanning->delivery_date = date('d/m/Y', strtotime($layingPlanning->delivery_date));
        // $layingPlanning->plan_date = date('m/d/Y', strtotime($layingPlanning->plan_date));

        $layingPlanningSizes = LayingPlanningSize::where('laying_planning_id', $layingPlanning->id)->get();

        $layingPlanningData = [
            'serial_number' => $this->generate_serial_number($layingPlanning->gl_id,$layingPlanning->color_id, $layingPlanning->style_id, $layingPlanning->fabric_type_id, $layingPlanning->fabric_cons_id),
            'gl_id' => $layingPlanning->gl_id,
            'style_id' => $layingPlanning->style_id,
            'buyer_id' => $layingPlanning->buyer_id,
            'color_id' => $layingPlanning->color_id,
            'order_qty' => $layingPlanning->order_qty,
            // DATE DEFAULT y-m-d 1945-08-17
            'delivery_date' => Carbon::createFromFormat('Y-m-d', '1945-08-17')->format('y-m-d'),
            'plan_date' => Carbon::now()->format('y-m-d'),
            'fabric_po' => $layingPlanning->fabric_po,
            'fabric_cons_id' => $layingPlanning->fabric_cons_id,
            'fabric_type_id' => $layingPlanning->fabric_type_id,
            'fabric_cons_qty' => $layingPlanning->fabric_cons_qty,
            'fabric_cons_desc' => $layingPlanning->fabric_cons_desc,
            'remark' => $layingPlanning->remark,
        ];
        $insertLayingData = LayingPlanning::create($layingPlanningData);

        return redirect()->route('laying-planning.show',$insertLayingData->id)
            ->with('success', 'Planning Successfully Duplicated.');
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

        $layingPlanning->serial_number = $this->generate_serial_number($request->gl,$request->color, $request->style, $request->fabric_type, $request->fabric_cons);

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
        $layingPlanning->remark = $request->remark;

        $layingPlanningDetail = LayingPlanningDetail::where('laying_planning_id', $layingPlanning->id)->get();
        
        foreach ($layingPlanningDetail as $key => $value) {
            $table_number = $value->table_number;
            $gl = $layingPlanning->gl->gl_number;
            $layingPlanningDetail[$key]->no_laying_sheet = $gl . "-" . str_pad($table_number, 3, "0", STR_PAD_LEFT);
            $layingPlanningDetail[$key]->save();
        }


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
        
        // $cutting_order_record = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($layingPlanning) {
        //     $query->where('laying_planning_id', $layingPlanning->id);
        // })->get();
        // foreach ($cutting_order_record as $key => $value) {
        //     $value->serial_number = $this->generate_serial_numberCor($value->layingPlanningDetail);
        //     $value->serial_number = str_replace('--', '-', $value->serial_number);
        //     $value->save();
        // }
        
        // $fabric_requisition = FabricRequisition::with(['layingPlanningDetail'])->whereHas('layingPlanningDetail', function($query) use ($layingPlanning) {
        //     $query->where('laying_planning_id', $layingPlanning->id);
        // })->get();
        // foreach ($fabric_requisition as $key => $value) {
        //     $value->serial_number = $this->generate_serial_numberFbr($value->layingPlanningDetail);
        //     $value->serial_number = str_replace('--', '-', $value->serial_number);
        //     $value->save();
        // }
        
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

        $checkCuttingOrder = CuttingOrderRecord::where('laying_planning_detail_id', $insertLayingDetail->id)->first();
        if ($checkCuttingOrder != null) {
            return redirect()->route('laying-planning.show',$layingPlanning->id)->with('error', 'Cutting Order already exist.');
        }

        $checkFabricRequisition = FabricRequisition::where('laying_planning_detail_id', $insertLayingDetail->id)->first();
        if ($checkFabricRequisition != null) {
            return redirect()->route('laying-planning.show',$layingPlanning->id)->with('error', 'Fabric Requisition already exist.');
        }

        try {
            $dataCuttingOrder = [
                'serial_number' => $this->generate_serial_numberCor(LayingPlanningDetail::find($insertLayingDetail->id)),
                'laying_planning_detail_id' => $insertLayingDetail->id,
                'created_by' => auth()->user()->id,
            ];
            $cuttingOrder = CuttingOrderRecord::create($dataCuttingOrder);

            $dataFabricRequisition = [
                'serial_number' => $this->generate_serial_numberFbr(LayingPlanningDetail::find($insertLayingDetail->id)),
                'laying_planning_detail_id' => $insertLayingDetail->id
            ];
            $insertFabricRequisition = FabricRequisition::create($dataFabricRequisition);
            
            return redirect()->route('laying-planning.show',$layingPlanning->id)
                ->with('success', 'Data Detail Laying Planning berhasil dibuat.');
        } catch (\Throwable $th) {
            return redirect()->route('cutting-order.index')->with('error', $th->getMessage());
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

            $checkCuttingOrder = CuttingOrderRecord::where('laying_planning_detail_id', $insertLayingDetail->id)->first();
            if ($checkCuttingOrder != null) {
                return redirect()->route('laying-planning.show',$layingPlanning->id)->with('error', 'Cutting Order already exist.');
            }

            $checkFabricRequisition = FabricRequisition::where('laying_planning_detail_id', $insertLayingDetail->id)->first();
            if ($checkFabricRequisition != null) {
                return redirect()->route('laying-planning.show',$layingPlanning->id)->with('error', 'Fabric Requisition already exist.');
            }

            try {
                $dataCuttingOrder = [
                    'serial_number' => $this->generate_serial_numberCor(LayingPlanningDetail::find($insertLayingDetail->id)),
                    'laying_planning_detail_id' => $insertLayingDetail->id,
                    'created_by' => auth()->user()->id,
                ];
                $cuttingOrder = CuttingOrderRecord::create($dataCuttingOrder);

                $dataFabricRequisition = [
                    'serial_number' => $this->generate_serial_numberFbr(LayingPlanningDetail::find($insertLayingDetail->id)),
                    'laying_planning_detail_id' => $insertLayingDetail->id
                ];
                $insertFabricRequisition = FabricRequisition::create($dataFabricRequisition);
            
            } catch (\Throwable $th) {
                return redirect()->route('cutting-order.index')->with('error', $th->getMessage());
            }
        }

        return redirect()->route('laying-planning.show',$layingPlanning->id)
            ->with('success', 'Data Detail Laying Planning berhasil diduplicate sebanyak '. $duplicate_qty .' kali');

    }

    function generate_no_laying_sheet($layingPlanning) {
        $getLastDetail = LayingPlanningDetail::where('laying_planning_id', $layingPlanning->id)->orderBy('table_number','desc')->first();
        $gl_number = $layingPlanning->gl->gl_number;
        
        if(!$getLastDetail){
            $no_laying_sheet = $gl_number. "-" . Str::padLeft('1', 3, '0');
        } else {
            $next_table_number = $getLastDetail->table_number + 1;
            $no_laying_sheet = $gl_number. "-" . Str::padLeft($next_table_number, 3, '0');
        }
        return $no_laying_sheet;
    }

    function generate_serial_number($gl_id = null, $color_id = null, $style_id = null, $fabric_type_id = null, $fabric_cons_id = null) {
        if (!$gl_id || !$color_id || !$style_id || !$fabric_type_id || !$fabric_cons_id) {
            return 0;
        }

        $gl = Gl::find($gl_id);
        $gl_number = $gl->gl_number;
        $color = Color::find($color_id);
        $style = DB::table('styles')->where('gl_id',$gl_id)->get();
        $style_serial = $style->where('id',$style_id)->first()->style;
        $style_serial = substr($style_serial, 0, 2).substr($style_serial, 4, 2);
        $style_serial = $style_serial . Str::padLeft(rand(0, 99), 2, '0');
        $fabric_type = FabricType::find($fabric_type_id);
        $fabric_type_serial = $fabric_type->name;
        $fabric_type_serial = substr($fabric_type_serial, 0, 2).substr($fabric_type_serial, 4, 2);
        $fabric_type_serial = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_type_serial);
        $fabric_type_serial = Str::upper($fabric_type_serial);
        $fabric_cons_serial = FabricCons::find($fabric_cons_id)->name;
        $fabric_cons_serial = substr($fabric_cons_serial, 0, 2).substr($fabric_cons_serial, 4, 2);
        $fabric_cons_serial = preg_replace('/[^A-Za-z0-9\-]/', '', $fabric_cons_serial);
        $fabric_cons_serial = Str::upper($fabric_cons_serial);

        $getDuplicateSN = LayingPlanning::select('gl_id','color_id')
                            ->where('gl_id', $gl_id)
                            ->where('color_id', $color_id)
                            ->where('style_id', $style_id)
                            ->get();
        $count_duplicate = $getDuplicateSN->count();

        if ($getDuplicateSN->count() > 0) {
            $count_duplicate = $count_duplicate + 1;
            $count_duplicate = Str::padLeft($count_duplicate, 2, '0');
            $serial_number = "LP-{$gl_number}-{$color->color_code}{$fabric_type_serial}{$fabric_cons_serial}-S{$style_serial}-{$count_duplicate}";
        } else {
            $serial_number = "LP-{$gl_number}-{$color->color_code}{$fabric_type_serial}{$fabric_cons_serial}-S{$style_serial}-01";
        }
        return $serial_number;
    }

    function generate_serial_numberCor($layingPlanningDetail){
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
        
        if ($getDuplicateSN->count() > 0) {
            $duplicate = $getDuplicateSN->count() + 1;
            $duplicate = Str::padLeft($duplicate, 2, '0');
            $serial_number = "COR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-{$duplicate}-{$table_number}";
        } else {
            $serial_number = "COR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-01-{$table_number}";
        }
        return $serial_number;
    }

    function generate_serial_numberFbr($layingPlanningDetail){
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
        $getDuplicateSN = FabricRequisition::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();

        if ($getDuplicateSN->count() > 0) {
            $duplicate = $getDuplicateSN->count() + 1;
            $duplicate = Str::padLeft($duplicate, 2, '0');
            $serial_number = "FBR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-{$duplicate}-{$table_number}";
        } else {
            $serial_number = "FBR-{$gl_number}-{$color_code}{$fabric_type}{$fabric_cons}-S{$style}-01-{$table_number}";
        }
        return $serial_number;
    }

}
