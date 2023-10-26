<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FabricRequisition;
use App\Models\FabricIssue;
use App\Models\LayingPlanningDetail;

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use PDF;

class FabricIssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fabric_requisitions = FabricRequisition::all();
        return view('page.fabric-issue.index2', compact('fabric_requisitions'));
    }

    public function dataFabricIssue(){
        $query = FabricRequisition::with(['layingPlanningDetail'])->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('serial_number', function ($data){
                return $data->serial_number;
            })
            ->addColumn('status', function ($data){
                if($data->is_issue == 1){
                    return '<span class="badge badge-danger">Not Issued</span>';
                }else{
                    return '<span class="badge badge-success">Issued</span>';
                }
            })
            ->addColumn('action', function($data){
                $print = '<i class="fas fa-print"></i>';
                $detail = '<i class="fas fa-eye"></i>';
                return '<a href="'.route('fabric-requisition.print', $data->id).'" class="btn btn-primary btn-sm" target="_blank">'.$print.'</a>
                <a href="'.route('fabric-issue.show', $data->id).'" class="btn btn-info btn-sm">'.$detail.'</a>';

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
        $fabric_requisition_id = $request->fabric_requisition_id;

        $fabric_requisition = FabricRequisition::find($fabric_requisition_id);
        $fabric_issues = FabricIssue::where('fabric_request_id', $fabric_requisition_id)->get();
        
        $rollNoIds = $request->roll_no;
        $batchNumberIds = $request->batch_number;
        $weightIds = $request->weight;
        $yardIds = $request->yard;
        $remark = $request->remark;
        
        $fabric_requisition->remark = $remark;
        $fabric_requisition->save();
        foreach ($rollNoIds as $key => $rollNoId) {
            $fabric_issue = new FabricIssue;
            $fabric_issue->roll_no = $rollNoId;
            $fabric_issue->batch_number = $batchNumberIds[$key];
            $fabric_issue->weight = $weightIds[$key];
            $fabric_issue->yard = $yardIds[$key];
            $fabric_issue->fabric_request_id = $fabric_requisition_id;
            // $fabric_issue->save();
            // if null
            if($fabric_issue->roll_no == null || $fabric_issue->batch_number == null || $fabric_issue->weight == null || $fabric_issue->yard == null){
                return redirect()->back()->with('error', 'Fabric Issue '.$fabric_requisition->serial_number.' Failed to Create!');
            }
            $fabric_issue->save();
        }
        

        // return $fabric_issues->sum('yard') . " " . $fabric_requisition->layingPlanningDetail->total_length;
        
        

        // 664 dari 3% 664 * 0.03 = 19.92
        
        return redirect()->back()->with('success', 'Fabric Issue '.$fabric_requisition->serial_number.' Successfully Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getFabricRequisition = FabricRequisition::with(['layingPlanningDetail'])->find($id);
        $layingPlanningDetail = LayingPlanningDetail::find($getFabricRequisition->layingPlanningDetail->id);
        $fabric_issues = FabricIssue::where('fabric_request_id', $id)->get();
        
        $getFabricRequisition->is_issue = 1;
        $max_min = $getFabricRequisition->layingPlanningDetail->total_length * 0.03;
        if($fabric_issues->sum('yard') <= $getFabricRequisition->layingPlanningDetail->total_length + $max_min && $fabric_issues->sum('yard') >= $getFabricRequisition->layingPlanningDetail->total_length - $max_min && $fabric_issues->sum('yard') != 0){
            $getFabricRequisition->is_issue = 1;
        }else{
            $getFabricRequisition->is_issue = 0;
        }

        $getFabricRequisition->save();
        $fabric_requisition = [
            'id' => $getFabricRequisition->id,
            'serial_number'=> $layingPlanningDetail->fabricRequisition->serial_number,
            'status' => $getFabricRequisition->is_issue,
            'no_laying_sheet' => $layingPlanningDetail->no_laying_sheet,
            'table_number' => $layingPlanningDetail->table_number,
            'gl_number' => $layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $layingPlanningDetail->layingPlanning->style->style,
            'color' => $layingPlanningDetail->layingPlanning->color->color,
            'fabric_po' => $layingPlanningDetail->layingPlanning->fabric_po,
            'fabric_type' => $layingPlanningDetail->layingPlanning->fabricType->name,
            'quantity_required' => $layingPlanningDetail->total_length,
            'quantity_issued' => "-",
            'difference' => "-",
            'remark' => $getFabricRequisition->remark,
        ];

        $fabric_requisition = (object)$fabric_requisition;

        return view('page.fabric-issue.show', compact('fabric_requisition', 'fabric_issues'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            try {
                $data = FabricIssue::find($id);
                return response()->json($data, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);
            }
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

        $fabric_issue = FabricIssue::find($id);
        $fabric_issue->roll_no = $request->roll_no;
        $fabric_issue->batch_number = $request->batch_number;
        $fabric_issue->weight = $request->weight;
        $fabric_issue->yard = $request->yard;
        $fabric_issue->save();

        $fabric_requisition = FabricRequisition::find($fabric_issue->fabric_request_id);
        $fabric_issues = FabricIssue::where('fabric_request_id', $fabric_issue->fabric_request_id)->get();
        if($fabric_issues->sum('yard') <= $fabric_requisition->layingPlanningDetail->total_length  && $fabric_issues->sum('yard') != 0){
            $fabric_requisition->is_issue = 1;
            $fabric_requisition->save();
        }else{
            $fabric_requisition->is_issue = 0;
            $fabric_requisition->save();
        }

        return redirect()->back()->with('success', 'Fabric Issue '.$fabric_issue->roll_no.' Successfully Updated!');
    }

    public function print($id)
    {
        $fabric_requisition = FabricRequisition::find($id);
        $filename = $fabric_requisition->serial_number . '.pdf';

        $data = (object)[
            'serial_number' => $fabric_requisition->serial_number,
            'gl_number' => $fabric_requisition->layingPlanningDetail->layingPlanning->gl->gl_number,
            'style' => $fabric_requisition->layingPlanningDetail->layingPlanning->style->style,
            'fabric_po' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabric_po,
            'no_laying_sheet' => $fabric_requisition->layingPlanningDetail->no_laying_sheet,
            'fabric_type' => $fabric_requisition->layingPlanningDetail->layingPlanning->fabricType->name,   
            'color' => $fabric_requisition->layingPlanningDetail->layingPlanning->color->color,
            'quantity_required' => $fabric_requisition->layingPlanningDetail->total_length . " yards",
            'quantity_issued' => "-",
            'difference' => "-",
            'table_number' => $fabric_requisition->layingPlanningDetail->table_number,   
            'date' => Carbon::now()->format('d-m-Y'),
        ];
        
        $fabric_issues = FabricIssue::where('fabric_request_id', $id)->get();

        $header = [
            'ROLL No / Nomor Roll',
            'WEIGHT / Berat',
            'Yard',
        ];
        for ($i=0; $i < 1; $i++) { 
            $header[] = 'ROLL No / Nomor Roll';
            $header[] = 'WEIGHT / Berat';
            $header[] = 'Yard';
        }

        $customPaper = array(0,0,612.00,400.00);
        $pdf = PDF::loadview('page.fabric-issue.print', compact('data', 'fabric_issues', 'header'))->setPaper($customPaper, 'portrait');
        return $pdf->stream($filename);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // destroy fabric issue
        try {
            $fabric_issue = FabricIssue::find($id);
            $fabric_issue->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Fabric Issue deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Fabric Issue failed to delete'
            ]);
        }
    }
}