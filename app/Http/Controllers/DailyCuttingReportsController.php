<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LayingPlanning;
use App\Models\LayingPlanningDetailSize;
use App\Models\Gl;
use App\Models\CuttingOrderRecordDetail;
use App\Models\CuttingOrderRecord;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use PDF;


class DailyCuttingReportsController extends Controller
{
    public function index()
    {
        return view('page.daily-cutting-report.index');
    }

    public function dailyCuttingReport(Request $request) {
        $date_filter = $request->date;
        $cuttingOrderRecord = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanningDetailSize.size', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color')
        
        ->whereHas('cuttingOrderRecordDetail', function($query) use ($date_filter) {
            $query->whereDate('created_at', $date_filter);
        })
        ->get();
        
        $data = [];
        // $color = $cuttingOrderRecord->pluck('cuttingOrderRecordDetail')->flatten()->pluck('color')->flatten()->unique('id')->values()->all();
        $gl_datas = $cuttingOrderRecord->pluck('layingPlanningDetail')->flatten()->pluck('layingPlanning')->flatten()->pluck('gl')->flatten()->unique('id')->values()->sortBy('gl_number')->all();
        
        $cuttingOrderRecordDetails = $cuttingOrderRecord->pluck('cuttingOrderRecordDetail')->flatten()->all();
        foreach ($cuttingOrderRecordDetails as $key => $value) {
            $data['cutting_order_record_detail'][$key] = $value;
            $color = $value->color;
            $data['cutting_order_record_detail'][$key]['color'] = $color;
            $cuttingOrderRecord = $value->cuttingOrderRecord;
            $data['cutting_order_record'][$key] = $cuttingOrderRecord;
            $layingPlanningDetail = $cuttingOrderRecord->layingPlanningDetail;
            $data['cutting_order_record'][$key]['laying_planning_detail'] = $layingPlanningDetail;
            $layingPlanningDetailSize = $layingPlanningDetail->layingPlanningDetailSize;
            $data['cutting_order_record'][$key]['laying_planning_detail']['laying_planning_detail_size'] = $layingPlanningDetailSize;
            $layingPlanning = $layingPlanningDetail->layingPlanning;
            $data['cutting_order_record'][$key]['laying_planning_detail']['laying_planning'] = $layingPlanning;
           
        }
        
        foreach ($gl_datas as $key => $value) {
            $layingPlannings = $value->layingPlanning;
            foreach ($layingPlannings as $keyLayingPlanning => $layingPlanning) {
                $data['laying_planning'][$keyLayingPlanning] = $layingPlanning;
                $gl = $layingPlanning->gl;
                $data['laying_planning'][$keyLayingPlanning]['gl'] = $gl;
                $style = $layingPlanning->style;
                $data['laying_planning'][$keyLayingPlanning]['style'] = $style;
                $buyer = $layingPlanning->buyer;
                $data['laying_planning'][$keyLayingPlanning]['buyer'] = $buyer;
                $colors = $layingPlanning->color;
                $data['laying_planning'][$keyLayingPlanning]['color'] = $colors;
            }
        }
        $previous_balance = 0;
        // total ratio * layer
        
        // SELECT * FROM `cutting_order_records` WHERE id IN (SELECT cutting_order_record_id FROM `cutting_order_record_details` WHERE created_at LIKE '2021-08-31%')
        
        $filename = 'Daily Cutting Output Report';
        $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data', 'date_filter'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
        // return $data;
    }
}