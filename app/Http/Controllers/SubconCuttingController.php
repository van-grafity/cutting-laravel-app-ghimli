<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\User;
use App\Models\Groups;
use App\Models\UserGroups;
use App\Models\LayingPlanning;
use App\Models\LayingPlanningDetail;
use App\Models\LayingPlanningSize;
use App\Models\FabricRequisition;
use App\Models\Gl;
use App\Models\GlCombine;
use App\Models\GlCombineDetail;
use Carbon\Carbon;

use PDF;
use Yajra\DataTables\Facades\DataTables;

class SubconCuttingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Groups::where('group_description', 'Subcon')->get();
        return view('page.subcon.index', compact('group'));
    }

    public function dataCuttingOrder()
    {
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::whereIn('operator', $this->getOperator())->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        $cuttingOrderRecord = CuttingOrderRecord::whereIn('id', $cuttingOrderRecordDetailIds)->get();
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->laying_planning_detail_id;
        }
        $detail = LayingPlanningDetail::whereIn('id', $cuttingOrderRecordIds)->get();
        $query = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])
            ->whereHas('style', function($query) {
                $query->whereNull('deleted_at');
            })
            ->whereIn('id', $detail->pluck('laying_planning_id'))
            ->select('laying_plannings.id','laying_plannings.serial_number','laying_plannings.gl_id','laying_plannings.style_id','laying_plannings.buyer_id','laying_plannings.color_id','laying_plannings.fabric_type_id','laying_plannings.delivery_date','laying_plannings.plan_date')->get();
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
            ->addColumn('delivery_date', function ($data){
                return Carbon::createFromFormat('Y-m-d', $data->delivery_date)->format('d-m-Y');
            })
            ->addColumn('plan_date', function ($data){
                return Carbon::createFromFormat('Y-m-d', $data->plan_date)->format('d-m-Y');
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="'.route('subcon-cutting.show', $data->id).'" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>';
                return $button;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $laying_planning_id = $id;
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::whereIn('operator', $this->getOperator())->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        $cuttingOrderRecord = CuttingOrderRecord::whereIn('id', $cuttingOrderRecordDetailIds)->get();
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->laying_planning_detail_id;
        }
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->find($laying_planning_id);
        $details = LayingPlanningDetail::with(['fabricRequisition'])
            ->where('laying_planning_id', $laying_planning_id)
            ->whereIn('id', $cuttingOrderRecordIds)
            ->get();
        $fabric_requisition = FabricRequisition::with(['layingPlanningDetail'])->whereHas('layingPlanningDetail', function($query) use ($laying_planning_id) {
            $query->where('laying_planning_id', $laying_planning_id);
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
            $details[$key]->fr_status = $value->fabricRequisition ? 'disabled' : '';
            $details[$key]->group = $value->cuttingOrderRecord ? $value->cuttingOrderRecord->cuttingOrderRecordDetail->first()->operator : '';
            $details[$key]->cor_id = $value->cuttingOrderRecord ? $value->cuttingOrderRecord->id : '';
            $cutting_order_record = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($laying_planning_id) {
                $query->where('laying_planning_id', $laying_planning_id);
            })->get();
            $details[$key]->cutting_order_record = $cutting_order_record;
            $total_pcs_all_table = $total_pcs_all_table + $value->total_all_size;
            $total_length_all_table = $total_length_all_table + $value->total_length;
        }
        // $details[$key]->group = value->cuttingOrderRecord->cuttingOrderRecordDetail->operator
        // push to group uniq only
        $group = [];
        foreach ($details as $key => $value) {
            if (!in_array($value->group, $group)) {
                $group[] = $value->group;
            }
        }
        $data->group = $group;

        $data->total_pcs_all_table = $total_pcs_all_table;
        $data->total_length_all_table = $total_length_all_table;
  
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
        return view('page.subcon.detail', compact('data', 'details','size_list')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    // Route::get('cutting-report-subcon/{id}', [SubconCuttingController::class,'cutting_report_subcon'])->name('subcon-cutting.cutting-report-subcon');
    public function cutting_report_subcon2($id)
    {
        // $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('id', $id)->first();
        // $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($id) {
        //     $query->where('id', $id);
        // })->get();
        // $details->load('cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color');
        // $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
        //     $query->whereHas('layingPlanning', function($query) use ($id) {
        //         $query->where('id', $id);
        //     });
        // })->get();
        // ganti get data berdasarkan subcon
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::whereIn('operator', $this->getOperator())->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        $cuttingOrderRecord = CuttingOrderRecord::whereIn('id', $cuttingOrderRecordDetailIds)->get();
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->laying_planning_detail_id;
        }
        $data = LayingPlanning::with(['gl', 'style', 'fabricCons', 'fabricType', 'color'])->where('id', $id)->first();
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereHas('layingPlanning', function($query) use ($id) {
            $query->where('id', $id);
        })->whereIn('id', $cuttingOrderRecordIds)->get();
        $details->load('cuttingOrderRecord', 'cuttingOrderRecord.cuttingOrderRecordDetail', 'cuttingOrderRecord.cuttingOrderRecordDetail.color');
        $cuttingOrderRecord = CuttingOrderRecord::with(['layingPlanningDetail', 'cuttingOrderRecordDetail'])->whereHas('layingPlanningDetail', function($query) use ($id) {
            $query->whereHas('layingPlanning', function($query) use ($id) {
                $query->where('id', $id);
            });
        })->get();
        $pdf = PDF::loadView('page.subcon.report', compact('data', 'details', 'cuttingOrderRecord'))->setPaper('a4', 'potrait');
        return $pdf->stream('laying-planning-report.pdf');
    }

    public function print2()
    {
        $group = Groups::get();
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::whereIn('operator', $this->getOperator())->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        $cuttingOrderRecord = CuttingOrderRecord::whereIn('id', $cuttingOrderRecordDetailIds)->get();
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->laying_planning_detail_id;
        }
        $detail = LayingPlanningDetail::whereIn('id', $cuttingOrderRecordIds)->get();
        $laying_plannings = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])
            ->whereHas('style', function($query) {
                $query->whereNull('deleted_at');
            })
            ->whereIn('id', $detail->pluck('laying_planning_id'))
            ->select('laying_plannings.id','laying_plannings.serial_number')->get();
        return view('page.subcon.print', compact('laying_plannings', 'group'));
    }
    
    public function summary_report_group_cutting_order_record(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $group_id = $request->group_id;
        $group = Groups::find($group_id);
        
        $datetime_filter_start =  Carbon::parse($date_start)->format('Y-m-d 07:00:00');
        $datetime_filter_end =  Carbon::parse($date_end)->addDay()->format('Y-m-d 06:59:00');

        $gl_list = GL::select('gls.*')
            ->join('laying_plannings','laying_plannings.gl_id','=','gls.id')
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')
            ->join('cutting_order_records','cutting_order_records.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('cutting_order_record_details','cutting_order_record_details.cutting_order_record_id','=','cutting_order_records.id')
            ->whereIn('cutting_order_record_details.user_id',$this->getUserIdByGroup($group_id))
            ->where('cutting_order_records.cut', '>=', $datetime_filter_start)
            ->where('cutting_order_records.cut', '<', $datetime_filter_end)
            ->groupBy('gls.id')
            ->orderBy('gls.gl_number')
            ->get();
            
        
        $all_size_in_summary = GL::select('sizes.*')
            ->join('laying_plannings','laying_plannings.gl_id','=','gls.id')
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')
            ->join('laying_planning_detail_sizes','laying_planning_detail_sizes.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('sizes','sizes.id','=','laying_planning_detail_sizes.size_id')
            ->join('cutting_order_records','cutting_order_records.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('cutting_order_record_details','cutting_order_record_details.cutting_order_record_id','=','cutting_order_records.id')
            ->whereIn('cutting_order_record_details.user_id',$this->getUserIdByGroup($group_id))
            ->where('cutting_order_records.cut', '>=', $datetime_filter_start)
            ->where('cutting_order_records.cut', '<', $datetime_filter_end)
            ->groupBy('sizes.id')
            ->orderBy('laying_planning_detail_sizes.id')
            ->get();

        
        $cutting_summary = [];
        $general_total_pcs = 0;
        $general_total_dozen = 0;
        
        foreach ($gl_list as $key_lp => $gl) {
            
            $cor_list = CuttingOrderRecord::select(
                    'cutting_order_records.id as cor_id',
                    'cutting_order_records.serial_number as cor_serial_number',
                    'cutting_order_records.cut as cut_date',
                    'laying_planning_details.no_laying_sheet',
                    'laying_planning_details.marker_code',
                    'cutting_order_records.laying_planning_detail_id',
                    'colors.color',
                )
                ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
                ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
                ->join('cutting_order_record_details','cutting_order_record_details.cutting_order_record_id','=','cutting_order_records.id')
                ->join('colors','colors.id','=','laying_plannings.color_id')
                ->where('laying_plannings.gl_id', $gl->id)
                ->whereIn('cutting_order_record_details.user_id',$this->getUserIdByGroup($group_id))
                ->where('cutting_order_records.cut', '>=', $datetime_filter_start)
                ->where('cutting_order_records.cut', '<', $datetime_filter_end)
                ->groupBy('cutting_order_records.id')
                ->get();

            
            $subtotal_pcs_per_gl = 0;
            $subtotal_dozen_per_gl = 0;
            foreach ($cor_list as $key_cor => $cor) {
                
                $cutting_order_record_detail = CuttingOrderRecordDetail::where('cutting_order_record_id', $cor->cor_id)->get();
                $cor_layer = $cutting_order_record_detail->sum('layer');
                $cor_total_ratio = 0;

                $ratio_per_size_in_summary = [];
                $cor_size_list = $cor->layingPlanningDetail->layingPlanningDetailSize;
                
                foreach ($all_size_in_summary as $key_summary_size => $summary_size) {
                    $ratio = 0;
                    foreach ($cor_size_list as $key_cor_size => $cor_size){
                        if ($summary_size->id == $cor_size->size_id){
                            $ratio = $cor_size->ratio_per_size;
                            $cor_total_ratio += $ratio;
                        }
                    }
                    $ratio_per_size_in_summary[] = $ratio > 0 ? $ratio : '-';
                }
                $cor->ratio_per_size_in_summary = $ratio_per_size_in_summary;
                $cor->cor_layer = $cor_layer;
                $cor->cor_total_ratio = $cor_total_ratio;
                $cor->cor_pcs = $cor_layer * $cor_total_ratio;
                $cor->cor_dozen =  number_format((float) ($cor->cor_pcs / 12), 2, '.', '');

                $subtotal_pcs_per_gl += $cor->cor_pcs;
                $subtotal_dozen_per_gl += $cor->cor_dozen;



                $carbon_real_cut_datetime = Carbon::parse($cor->cut_date);
                $real_cut_date_only = Carbon::parse(date($carbon_real_cut_datetime))->format('Y-m-d');
                $start_shift_datetime =  Carbon::parse($real_cut_date_only)->format('Y-m-d 07:00:00');
                
                if($carbon_real_cut_datetime->lt($start_shift_datetime)){
                    $cor->shift_date = $carbon_real_cut_datetime->subDays()->format('d-m-Y');
                } else {
                    $cor->shift_date = $carbon_real_cut_datetime->format('d-m-Y');
                }
            }

            $cor_per_gl = (object) [
                'gl' => $gl,
                'cor_list' => $cor_list,
                'subtotal_pcs_per_gl' => $subtotal_pcs_per_gl,
                'subtotal_dozen_per_gl' => $subtotal_dozen_per_gl,
            ];
            $cutting_summary[] = $cor_per_gl;
            $general_total_pcs += $subtotal_pcs_per_gl;
            $general_total_dozen += $subtotal_dozen_per_gl;
        }

        $pdf = PDF::loadView('page.subcon.report_clean', compact('group','date_start', 'date_end','cutting_summary','all_size_in_summary','general_total_pcs','general_total_dozen'))->setPaper('a4', 'landscape');
        return $pdf->stream('Summary Group Cutting.pdf');
    }

    public function getUserIdByGroup($group_id)
    {
        $user_groups = UserGroups::where('group_id', $group_id)->get();
        $user_ids = [];
        foreach ($user_groups as $key => $value) {
            $user_ids[] = $value->user_id;
        }
        return $user_ids;
    }

    public function print()
    {
        $group = Groups::orderBy('id', 'asc')->get();
        return view('page.subcon.print2', compact('group'));
    }

    public function getOperator(){
        $group = Groups::where('group_description', 'Subcon')->get();
        $groupIds = [];
        foreach ($group as $key => $value) {
            $groupIds[] = $value->id;
        }
        $userGroup = UserGroups::whereIn('group_id', $groupIds)->get();
        $userIds = [];
        foreach ($userGroup as $key => $value) {
            $userIds[] = $value->user_id;
        }
        $users = User::whereIn('id', $userIds)->get();
        $userNames = [];
        foreach ($users as $key => $value) {
            $userNames[] = $value->name;
        }
        return $userNames;
    }

    public function getOperatorId(){
        $group = Groups::where('group_description', 'Subcon')->get();
        $groupIds = [];
        foreach ($group as $key => $value) {
            $groupIds[] = $value->id;
        }
        $userGroup = UserGroups::whereIn('group_id', $groupIds)->get();
        $userIds = [];
        foreach ($userGroup as $key => $value) {
            $userIds[] = $value->user_id;
        }
        $users = User::whereIn('id', $userIds)->get();
        $userNames = [];
        foreach ($users as $key => $value) {
            $userNames[] = $value->id;
        }
        return $userNames;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
