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

        $date_filter_night_shift = Carbon::parse($date_end)->addDay()->format('Y-m-d H:i:s');
        $date_filter_night_shift = Carbon::parse($date_filter_night_shift)->format('Y-m-d 05:00:00');

        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::with(['user'])->whereIn('user_id', $this->getUserIdByGroup($group_id))->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        
        $cuttingOrderRecord = CuttingOrderRecord::with(['cuttingOrderRecordDetail'])->whereIn('id', $cuttingOrderRecordDetailIds)
            ->whereDate('updated_at', '>=', $date_start)
            ->whereDate('updated_at', '<=', $date_filter_night_shift)
            ->whereNotNull('cut')
            ->orderBy('updated_at', 'asc')
            ->get();

        
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->laying_planning_detail_id;
        }
        $details = LayingPlanningDetail::with(['layingPlanning', 'layingPlanningDetailSize', 'layingPlanning.gl', 'layingPlanning.style', 'layingPlanning.buyer', 'layingPlanning.color', 'layingPlanning.fabricType', 'layingPlanning.layingPlanningSize.size'])->whereIn('id', $cuttingOrderRecordIds)->get();

        $pdf = PDF::loadView('page.subcon.report2', compact('cuttingOrderRecord', 'cuttingOrderRecordDetail', 'details', 'date_start', 'date_end'))->setPaper('a4', 'landscape');
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
