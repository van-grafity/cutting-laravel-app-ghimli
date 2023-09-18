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
        // result date filter
        $date_filter = $request->date;
        $date_filter_night_shift = Carbon::parse($date_filter)->addDay()->format('Y-m-d H:i:s');
        $date_filter_night_shift = Carbon::parse($date_filter_night_shift)->format('Y-m-d 00:05:00');
    
        // get data group yang ada potong hari ini
        $groups = Groups::select('groups.id', 'group_name')
        ->join('user_groups', 'user_groups.group_id', '=', 'groups.id')
        ->join('users', 'users.id', '=', 'user_groups.user_id')
        ->join('cutting_order_record_details', 'cutting_order_record_details.operator', '=', 'users.name')
        ->where(function($query) use ($date_filter_night_shift, $date_filter) {
            $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
            ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
        })
        ->groupBy('groups.id')
        ->orderBy('groups.id')
        ->get();

        // get buyer yang potong hari ini
        $buyers = CuttingOrderRecord::select('buyers.*')
        ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
        ->join('laying_plannings', 'laying_plannings.id', '=', 'laying_planning_details.laying_planning_id')
        ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
        ->join('buyers', 'buyers.id', '=', 'gls.buyer_id')
        ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
        ->where(function($query) use ($date_filter_night_shift, $date_filter) {
            $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
            ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
        })
        ->groupBy('buyers.id')
        ->get();

        $data = [];

        foreach($buyers as $key => $buyer) {
            // get laying planning yang potong hari ini
            $layingPlannings = LayingPlanning::select('buyers.name', 'gls.gl_number', 'colors.color', 'styles.style', 'fabric_cons.name as cons_name', 'laying_plannings.order_qty', 'laying_plannings.id as laying_planning_id')
            ->join('laying_planning_details', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
            ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
            ->join('styles', 'styles.id', '=', 'laying_plannings.style_id')
            ->join('buyers', 'buyers.id', '=', 'gls.buyer_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->join('fabric_cons', 'fabric_cons.id', '=', 'laying_plannings.fabric_cons_id')
            ->where(function($query) use ($date_filter_night_shift, $date_filter) {
                $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
                ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
            })
            ->where('gls.buyer_id', '=', $buyer->id)
            ->groupBy('laying_plannings.id')
            ->get();
            foreach($layingPlannings as $key_lp => $laying_planning) {
                $total_qty_per_day = 0;
                $total_previous_cutting = 0;
                $qty_per_groups = [];

                // get qty per groups
                foreach($groups as $key_group => $group)
                {
                    $qty_group = 0;

                    $cutting_order_record_groups = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                    ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
                    ->join('users', 'users.name', '=', 'cutting_order_record_details.operator')
                    ->join('user_groups', 'user_groups.user_id', '=', 'users.id')
                    ->join('groups', 'groups.id', '=', 'user_groups.group_id')
                    ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                    ->where(function($query) use ($date_filter_night_shift, $date_filter) {
                        $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
                        ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
                    })
                    ->where('groups.id', '=', $group->id)
                    ->get();
                    
                    foreach ($cutting_order_record_groups as $key_cor => $cor) {
                        
                        $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                        ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                        ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                        ->where('cutting_order_records.id', '=', $cor->id)
                        ->sum('laying_planning_detail_sizes.ratio_per_size');

                        $cutting_order_record_groups[$key_cor]->total_size_ratio = $total_ratio;
                        $cutting_order_record_groups[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                        $qty_group += $total_ratio * $cor->total_layer;
                    }

                   $qty_per_groups[$key_group] = (object) [
                        'group_id' => $group->id,
                        'group_name' => $group->group_name,
                        'qty_group' => $qty_group,
                   ];
                }
                $layingPlannings[$key_lp]->qty_per_groups = $qty_per_groups;
                
                // get cor dari setiap laying planning
                $cutting_order_records = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
                ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                ->where(function($query) use ($date_filter_night_shift, $date_filter) {
                    $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
                    ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
                })
                ->groupBy('cutting_order_records.id')
                ->get();

                foreach ($cutting_order_records as $key_cor => $cor) {
                    // dd($cor);
                    $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                    ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                    ->where('cutting_order_records.id', '=', $cor->id)
                    ->sum('laying_planning_detail_sizes.ratio_per_size');
                    $cutting_order_records[$key_cor]->total_size_ratio = $total_ratio;
                    $cutting_order_records[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                    $total_qty_per_day += $total_ratio * $cor->total_layer;
                }

                $layingPlannings[$key_lp]->total_qty_per_day = $total_qty_per_day;

                // get previous cor dari setiap laying planning
                $prev_cutting_order_records = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
                ->where('laying_planning_details.laying_planning_id', '=', $laying_planning->laying_planning_id)
                // ->whereDate('cutting_order_record_details.created_at', '<', $date_filter)
                ->where(function($query) use ($date_filter_night_shift, $date_filter) {
                    $query->whereDate('cutting_order_record_details.created_at', '<=', $date_filter_night_shift)
                    // ->whereDate('cutting_order_record_details.created_at', '=', $date_filter);
                    ->whereBetween('cutting_order_record_details.created_at', [Carbon::parse($date_filter)->format('Y-m-d 00:07:00'), $date_filter_night_shift]);
                })
                ->groupBy('cutting_order_records.id')
                ->get();

                foreach ($prev_cutting_order_records as $key_cor => $cor) {
                    // dd($cor);
                    $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                    ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                    ->where('cutting_order_records.id', '=', $cor->id)
                    ->sum('laying_planning_detail_sizes.ratio_per_size');
                    $prev_cutting_order_records[$key_cor]->total_size_ratio = $total_ratio;
                    $prev_cutting_order_records[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                    $total_previous_cutting += $total_ratio * $cor->total_layer;
                }

                $layingPlannings[$key_lp]->total_previous_cutting = $total_previous_cutting;
                $layingPlannings[$key_lp]->previous_balance = $layingPlannings[$key_lp]->order_qty - $total_previous_cutting;
                $layingPlannings[$key_lp]->accumulation = $total_previous_cutting + $total_qty_per_day;
                $layingPlannings[$key_lp]->completed = round(($layingPlannings[$key_lp]->accumulation / $layingPlannings[$key_lp]->order_qty * 100), 2) . "%" ;
            }

            $data[$key] = (object) [
                'buyer' => $buyer->name,
                'laying_plannings' => $layingPlannings
            ];
        } 
        // dd($data);
        // return $data; 
        
        $filename = 'Daily Cutting Output Report';
        $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data', 'date_filter', 'groups'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
        // return view('page.daily-cutting-report.print', compact('data', 'date_filter', 'groups'));
    }
}