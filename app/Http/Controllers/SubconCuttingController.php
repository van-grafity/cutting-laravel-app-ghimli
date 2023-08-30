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
        return view('page.subcon.index');
    }

    public function dataCuttingOrder(){
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
        $cuttingOrderRecordDetail = CuttingOrderRecordDetail::whereIn('operator', $userNames)->get();
        $cuttingOrderRecordDetailIds = [];
        foreach ($cuttingOrderRecordDetail as $key => $value) {
            $cuttingOrderRecordDetailIds[] = $value->cutting_order_record_id;
        }
        $cuttingOrderRecord = CuttingOrderRecord::whereIn('id', $cuttingOrderRecordDetailIds)->get();
        $cuttingOrderRecordIds = [];
        foreach ($cuttingOrderRecord as $key => $value) {
            $cuttingOrderRecordIds[] = $value->id;
        }
        $query = CuttingOrderRecord::with(['statusLayer', 'statusCut', 'layingPlanningDetail'])
            ->whereIn('id', $cuttingOrderRecordIds)
            ->orderBy('id', 'desc')
            ->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('status', function($data){
                $status = '';
                if ($data->statusLayer->name == 'completed') {
                    $status = '<span class="badge rounded-pill badge-success" style="padding: 1em">Selesai Layer</span>';
                } else if ($data->statusLayer->name == 'over layer') {
                    $status = '<span class="badge rounded-pill badge-danger" style="padding: 1em">Over layer</span>';
                } else {
                    $status = '<span class="badge rounded-pill badge-warning" style="padding: 1em">Belum Selesai</span>';
                }
                return $status;
            })
            ->addColumn('status_cut', function($data){
                $status = '';
                if ($data->statusCut->name == 'sudah') {
                    $status = '<span class="badge rounded-pill badge-success" style="padding: 1em">Sudah Potong</span>';
                } else {
                    $status = '<span class="badge rounded-pill badge-warning" style="padding: 1em">Belum Potong</span>';
                }
                return $status;
            })
            ->addColumn('action', function($data){
                $action = '
                <a href="'.route('cutting-order.print', $data->id).'" class="btn btn-primary btn-sm mb-1" target="_blank">Print Nota</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm mb-1" onclick="delete_cuttingOrder('.$data->id.')" data-id="'.$data->id.'">Delete</a>
                <a href="'.route('cutting-order.show', $data->id).'" class="btn btn-info btn-sm mb-1">Detail</a>';
                $action .= $data->cuttingOrderRecordDetail->isEmpty() ? '' : '<a href="'.route('cutting-order.report', $data->id).'" class="btn btn-primary btn-sm mb-1" target="_blank">Print Report</a>';
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
        //
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

    public function cutting_report_subcon()
    {
        $id = 700;
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
        $pdf = PDF::loadView('page.subcon.report', compact('data', 'details', 'cuttingOrderRecord'))->setPaper('a4', 'potrait');
        return $pdf->stream('laying-planning-report.pdf');
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
