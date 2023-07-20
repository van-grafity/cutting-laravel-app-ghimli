<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LayingPlanning;
use App\Models\LayingPlanningDetailSize;
use App\Models\Gl;
use App\Models\CuttingOrderRecordDetail;
use App\Models\CuttingOrderRecord;
use App\Models\User;
use App\Models\UserGroups;
use App\Models\Groups;

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
        
        // get buyer yang potong hari ini
        $buyers = CuttingOrderRecord::select('buyers.*')
        ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
        ->join('laying_plannings', 'laying_plannings.id', '=', 'laying_planning_details.laying_planning_id')
        ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
        ->join('buyers', 'buyers.id', '=', 'gls.buyer_id')
        ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
        ->whereDate('cutting_order_record_details.created_at', '=', $date_filter)
        ->groupBy('buyers.id')
        ->get();

        $data = [];

        foreach($buyers as $key => $buyer) {
            // get laying planning yang potong hari ini
            $layingPlannings = LayingPlanning::select('buyers.name', 'gls.gl_number', 'colors.color', 'styles.style', 'laying_plannings.order_qty', 'laying_plannings.id as laying_planning_id')
            ->join('laying_planning_details', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
            ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
            ->join('styles', 'styles.id', '=', 'laying_plannings.style_id')
            ->join('buyers', 'buyers.id', '=', 'gls.buyer_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->whereDate('cutting_order_record_details.created_at', '=', $date_filter)
            ->where('gls.buyer_id', '=', $buyer->id)
            ->groupBy('laying_plannings.id')
            ->get();
            
            foreach($layingPlannings as $key_lp => $laying_planning) {
                // $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
                // $total_size_ratio = 0;
                // foreach( $get_size_ratio as $key => $size ) {
                //     $total_size_ratio += $size->ratio_per_size;
                // }

                // laying planning details get laying planning detail size
                

                $cutting_order_records = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
                ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                ->groupBy('cutting_order_records.id')
                ->get();

                foreach ($cutting_order_records as $key_cor => $cor) {
                    $laying_planning_detail_size = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                    ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                    ->sum('laying_planning_detail_sizes.ratio_per_size');
                }
                
                // get previous balance per laying planning dari cor detail
                $cutting_order_record_details = CuttingOrderRecordDetail::select('cutting_order_record_details.*')
                ->join('cutting_order_records', 'cutting_order_records.id', '=', 'cutting_order_record_details.cutting_order_record_id')
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                ->whereDate('cutting_order_record_details.created_at', '<', $date_filter)
                ->get();
                // dd($cutting_order_record_details);
                return $cutting_order_records; 
            }

            $data[$key] = (object) [
                'buyer' => $buyer->name,
                'laying_plannings' => $layingPlannings
            ];
        } 
        // SELECT * FROM `cutting_order_records` WHERE id IN (SELECT cutting_order_record_id FROM `cutting_order_record_details` WHERE created_at LIKE '2021-08-31%')
        
        // $filename = 'Daily Cutting Output Report';
        // $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data', 'date_filter'))->setPaper('a4', 'landscape');
        // return $pdf->stream($filename);
        return view('page.daily-cutting-report.print', compact('data', 'date_filter'));
        // return $data;
    }
}