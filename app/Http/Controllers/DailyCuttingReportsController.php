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
use App\Models\Buyer;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use PDF;


class DailyCuttingReportsController extends Controller
{
    public function index()
    {
        return view('page.daily-cutting-report.index');
    }

    public function dailyCuttingReport(Request $request) 
    {
        $date_filter = $request->date;

        // ## adjust start_date and end_date for day and night shift
        $start_datetime =  Carbon::parse($date_filter)->format('Y-m-d 07:00:00');
        $end_datetime =  Carbon::parse($date_filter)->addDay()->format('Y-m-d 06:59:00');
    
        $groups = $this->getGroups($start_datetime, $end_datetime);
        $buyers = $this->getBuyers($start_datetime, $end_datetime);

        $data_per_buyer = [];
        $general_totals = $this->initializeGeneralTotals();

        foreach($buyers as $key => $buyer) {
            $layingPlannings = $this->getLayingPlannings($buyer->id, $start_datetime, $end_datetime);
            $subtotals = $this->initializeSubtotals();

            foreach($layingPlannings as $key_lp => $laying_planning) {
                $laying_planning->qty_per_groups = $this->getQtyPerGroups($laying_planning->laying_planning_id, $groups, $start_datetime, $end_datetime);
                $laying_planning->total_qty_per_day = $this->getTotalQtyPerDay($laying_planning->laying_planning_id, $start_datetime, $end_datetime);
                $laying_planning->previous_accumulation = $this->getPreviousAccumulation($laying_planning->laying_planning_id, $start_datetime);
                $laying_planning->replacement = $this->getReplacement($laying_planning->laying_planning_id, $start_datetime, $end_datetime);
                $laying_planning->accumulation = $laying_planning->previous_accumulation + $laying_planning->total_qty_per_day;
                $laying_planning->balance_to_cut = $this->calculateBalanceToCut($laying_planning->accumulation, $laying_planning->order_qty);
                $laying_planning->completed = $this->calculateCompleted($laying_planning->accumulation, $laying_planning->order_qty);

                $this->updateSubtotals($subtotals, $laying_planning);
            }

            $this->updateGeneralTotals($general_totals, $subtotals);

            $data_per_buyer[$key] = (object) [
                'buyer' => $buyer->name,
                'laying_plannings' => $layingPlannings,
                'subtotal_mi_qty' => $subtotals['subtotal_mi_qty'],
                'subtotal_qty_per_day' => $subtotals['subtotal_qty_per_day'],
                'subtotal_previous_accumulation' => $subtotals['subtotal_previous_accumulation'],
                'subtotal_accumulation' => $subtotals['subtotal_accumulation'],
                'subtotal_balance_to_cut' => $subtotals['subtotal_balance_to_cut'],
                'subtotal_replacement' => $subtotals['subtotal_replacement'],
            ];
        }

        $general_total = (object) $general_totals;

        $filename = 'Daily Cutting Output Report.pdf';
        $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data_per_buyer', 'date_filter', 'groups','general_total'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

    public function dailyCuttingReportYds(Request $request)
    {
        $date_filter = $request->date;

        // ## adjust start_date and end_date for day and night shift
        $start_datetime =  Carbon::parse($date_filter)->format('Y-m-d 07:00:00');
        $end_datetime =  Carbon::parse($date_filter)->addDay()->format('Y-m-d 06:59:00');
    
        $groups = $this->getGroups($start_datetime, $end_datetime);
        $buyers = $this->getBuyers($start_datetime, $end_datetime);

        $data_per_buyer = [];
        $general_totals = $this->initializeGeneralTotals('yds');

        foreach($buyers as $key => $buyer) {
            $layingPlannings = $this->getLayingPlannings($buyer->id, $start_datetime, $end_datetime);
            $subtotals = $this->initializeSubtotals('yds');

            foreach($layingPlannings as $key_lp => $laying_planning) {
                $laying_planning->plan_yds = $this->calculatePlanYds($laying_planning->laying_planning_id);
                $laying_planning->qty_per_groups = $this->getQtyPerGroups($laying_planning->laying_planning_id, $groups, $start_datetime, $end_datetime,'yds');
                $laying_planning->total_qty_per_day = collect($laying_planning->qty_per_groups)->pluck('qty_group')->sum();
                $laying_planning->previous_accumulation = $this->getPreviousAccumulation($laying_planning->laying_planning_id, $start_datetime , 'yds');
                $laying_planning->replacement = $this->getReplacement($laying_planning->laying_planning_id, $start_datetime, $end_datetime);
                $laying_planning->accumulation = $laying_planning->previous_accumulation + $laying_planning->total_qty_per_day;
                $laying_planning->balance_to_cut = $this->calculateBalanceYdsToCut($laying_planning->laying_planning_id);

                $pcs_prev_accumulation = $this->getPreviousAccumulation($laying_planning->laying_planning_id, $start_datetime);
                $total_qty_per_day = $this->getTotalQtyPerDay($laying_planning->laying_planning_id, $start_datetime, $end_datetime);
                $pcs_accumulation = $pcs_prev_accumulation + $total_qty_per_day;

                $laying_planning->completed = $this->calculateCompleted($pcs_accumulation, $laying_planning->order_qty);
                $this->updateSubtotals($subtotals, $laying_planning,'yds');
            }

            $this->updateGeneralTotals($general_totals, $subtotals,'yds');

            $data_per_buyer[$key] = (object) [
                'buyer' => $buyer->name,
                'laying_plannings' => $layingPlannings,
                'subtotal_plan_yds' => $subtotals['subtotal_plan_yds'],
                'subtotal_yds_per_day' => $subtotals['subtotal_yds_per_day'],
                'subtotal_previous_accumulation' => $subtotals['subtotal_previous_accumulation'],
                'subtotal_accumulation' => $subtotals['subtotal_accumulation'],
                'subtotal_balance_to_cut' => $subtotals['subtotal_balance_to_cut'],
                'subtotal_replacement' => $subtotals['subtotal_replacement'],
            ];
        }

        $general_total = (object) $general_totals;

        $filename = 'Daily Cutting Output Report.pdf';
        $data = [
            'data_per_buyer' => $data_per_buyer,
            'date_filter' => $date_filter,
            'groups' => $groups,
            'general_total' => $general_total,
        ];
        
        $pdf = PDF::loadview('page.daily-cutting-report.print-yds', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }


    private function getGroups($start_datetime, $end_datetime) {
        $groups = Groups::select('groups.id', 'group_name')
            ->join('user_groups', 'user_groups.group_id', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'user_groups.user_id')
            ->join('cutting_order_record_details', 'cutting_order_record_details.user_id', '=', 'users.id')
            ->join('cutting_order_records','cutting_order_records.id', '=' ,'cutting_order_record_details.cutting_order_record_id')
            ->where('cutting_order_records.cut', '>=', $start_datetime)
            ->where('cutting_order_records.cut', '<=', $end_datetime)
            ->groupBy('groups.id')
            ->orderBy('groups.group_name')
            ->get();
        
        foreach ($groups as $key_group => $group) {
            $group_name_show = str_replace('Group', '', substr($group->group_name, 0, 8));
            $group_name_show = str_replace(' ', '', $group_name_show);
            $groups[$key_group]->group_name_show = $group_name_show;
        }

        return $groups;
    }

    private function getBuyers($start_datetime, $end_datetime) {
        return Buyer::select('buyers.*')
            ->join('laying_plannings','laying_plannings.buyer_id','=','buyers.id')
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')
            ->join('cutting_order_records','cutting_order_records.laying_planning_detail_id','=','laying_planning_details.id')
            ->where('cutting_order_records.cut', '>=', $start_datetime)
            ->where('cutting_order_records.cut', '<=', $end_datetime)
            ->groupBy('buyers.id')
            ->orderBy('buyers.name')
            ->get();
    }

    private function getLayingPlannings($buyer_id, $start_datetime, $end_datetime) {
        return LayingPlanning::select(
                'buyers.name', 
                'styles.style', 
                'gls.gl_number', 
                'colors.color', 
                'laying_plannings.order_qty', 
                'laying_plannings.id as laying_planning_id'
            )
            ->join('buyers', 'buyers.id', '=', 'laying_plannings.buyer_id')
            ->join('laying_planning_details', 'laying_planning_details.laying_planning_id', '=', 'laying_plannings.id')
            ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
            ->join('styles', 'styles.id', '=', 'laying_plannings.style_id')
            ->join('gls', 'gls.id', '=', 'laying_plannings.gl_id')
            ->join('colors', 'colors.id', '=', 'laying_plannings.color_id')
            ->where(function($query) use ($start_datetime, $end_datetime){
                $query->where('cutting_order_records.cut', '>=', $start_datetime)
                      ->where('cutting_order_records.cut', '<=', $end_datetime);
            })
            ->where('laying_plannings.buyer_id', '=', $buyer_id)
            ->groupBy('laying_plannings.id')
            ->orderBy('gls.gl_number')
            ->get();
    }

    private function getQtyPerGroups($laying_planning_id, $groups, $start_datetime, $end_datetime, $unit = 'pcs') {
        $qty_per_groups = [];
        
        foreach($groups as $key_group => $group) {
            $qty_group = 0;

            $cutting_order_record_groups = CuttingOrderRecord::select(
                    'cutting_order_records.*',
                    'laying_planning_details.marker_code',
                    DB::raw("SUM(cutting_order_record_details.layer) as total_layer")
                )
                ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
                ->join('users', 'users.id', '=', 'cutting_order_record_details.user_id')
                ->join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('groups', 'groups.id', '=', 'user_groups.group_id')
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
                ->where('laying_planning_details.laying_planning_id', '=', $laying_planning_id)
                ->where(function($query) use ($start_datetime, $end_datetime) {
                    $query->where('cutting_order_records.cut', '>=', $start_datetime)
                          ->where('cutting_order_records.cut', '<=', $end_datetime);
                })
                ->whereNot(DB::raw('lower(laying_planning_details.marker_code)'), 'LIKE', '%' . strtolower('REPL') . '%')
                ->where('groups.id', '=', $group->id)
                ->groupBy('cutting_order_records.id')
                ->get();

            foreach ($cutting_order_record_groups as $key_cor => $cor) {
                if($unit == 'yds') {
                    $qty_group += $cor->total_layer * $cor->layingPlanningDetail->marker_length;
                    
                } else {
                    $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                        ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                        ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                        ->where('cutting_order_records.id', '=', $cor->id)
                        ->sum('laying_planning_detail_sizes.ratio_per_size');

                    $cutting_order_record_groups[$key_cor]->total_size_ratio = $total_ratio;
                    $cutting_order_record_groups[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                    $qty_group += $total_ratio * $cor->total_layer;
                }
            }

            $qty_per_groups[$key_group] = (object) [
                'group_id' => $group->id,
                'group_name' => $group->group_name,
                'qty_group' => $qty_group,
            ];
        }

        return $qty_per_groups;
    }

    private function getTotalQtyPerDay($laying_planning_id, $start_datetime, $end_datetime) {
        $total_qty_per_day = 0;

        $cutting_order_records = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
            ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
            ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
            ->where('laying_planning_details.laying_planning_id', '=', $laying_planning_id)
            ->whereNot(DB::raw('lower(laying_planning_details.marker_code)'), 'LIKE', '%'. strtolower('REPL') . '%')
            ->where(function($query) use ($start_datetime, $end_datetime){
                $query->where('cutting_order_records.cut', '>=', $start_datetime)
                      ->where('cutting_order_records.cut', '<=', $end_datetime);
            })
            ->groupBy('cutting_order_records.id')
            ->get();

        foreach ($cutting_order_records as $key_cor => $cor) {
            $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                ->where('cutting_order_records.id', '=', $cor->id)
                ->sum('laying_planning_detail_sizes.ratio_per_size');
            $cutting_order_records[$key_cor]->total_size_ratio = $total_ratio;
            $cutting_order_records[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
            $total_qty_per_day += $total_ratio * $cor->total_layer;
        }

        return $total_qty_per_day;
    }

    private function getPreviousAccumulation($laying_planning_id, $start_datetime, $unit = 'pcs') {
        $total_previous_cutting = 0;

        $prev_cutting_order_records = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
            ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
            ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
            ->where('laying_planning_details.laying_planning_id', '=', $laying_planning_id)
            ->whereNot(DB::raw('lower(laying_planning_details.marker_code)'), 'LIKE', '%'. strtolower('REPL') . '%')
            ->where(function($query) use ($start_datetime){
                $query->where('cutting_order_records.cut', '<=', $start_datetime);
            })
            ->groupBy('cutting_order_records.id')
            ->get();

        foreach ($prev_cutting_order_records as $key_cor => $cor) {
            if($unit == 'yds') {
                $total_previous_cutting += $cor->total_layer * $cor->layingPlanningDetail->marker_length;
                
            } else {
                $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                    ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                    ->where('cutting_order_records.id', '=', $cor->id)
                    ->sum('laying_planning_detail_sizes.ratio_per_size');
    
                $prev_cutting_order_records[$key_cor]->total_size_ratio = $total_ratio;
                $prev_cutting_order_records[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                $total_previous_cutting += $total_ratio * $cor->total_layer;
            }
        }

        return $total_previous_cutting;
    }

    private function getReplacement($laying_planning_id, $start_datetime, $end_datetime) {
        $total_replacement = 0;

        $cutting_order_records_replacement = CuttingOrderRecord::select('cutting_order_records.*', DB::raw("SUM(cutting_order_record_details.layer) as total_layer"))
            ->join('laying_planning_details', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
            ->join('cutting_order_record_details', 'cutting_order_record_details.cutting_order_record_id', '=', 'cutting_order_records.id')
            ->where('laying_planning_details.laying_planning_id', '=', $laying_planning_id)
            ->where(DB::raw('lower(laying_planning_details.marker_code)'), 'LIKE', '%'. strtolower('REPL') . '%')
            ->where(function($query) use ($start_datetime, $end_datetime){
                $query->where('cutting_order_records.cut', '>=', $start_datetime)
                      ->where('cutting_order_records.cut', '<=', $end_datetime);
            })
            ->groupBy('cutting_order_records.id')
            ->get();

        foreach ($cutting_order_records_replacement as $key_cor => $cor) {
            if($unit == 'yds') {
                $total_replacement += $cor->total_layer * $cor->layingPlanningDetail->marker_length;
                
            } else {
                $total_ratio = LayingPlanningDetailSize::select('laying_planning_detail_sizes.*')
                    ->join('laying_planning_details', 'laying_planning_details.id', '=', 'laying_planning_detail_sizes.laying_planning_detail_id')
                    ->join('cutting_order_records', 'cutting_order_records.laying_planning_detail_id', '=', 'laying_planning_details.id')
                    ->where('cutting_order_records.id', '=', $cor->id)
                    ->sum('laying_planning_detail_sizes.ratio_per_size');
    
                $cutting_order_records_replacement[$key_cor]->total_size_ratio = $total_ratio;
                $cutting_order_records_replacement[$key_cor]->total_qty_per_cor = $total_ratio * $cor->total_layer;
                $total_replacement += $total_ratio * $cor->total_layer;
            }
        }

        return $total_replacement;
    }

    private function calculateBalanceToCut($accumulation, $order_qty) {
        $balance_to_cut = ($accumulation) - $order_qty;
        return $balance_to_cut > 0 ? '+'.$balance_to_cut : $balance_to_cut;
    }

    private function calculateCompleted($accumulation, $order_qty) {
        return round(($accumulation / $order_qty * 100), 2) . "%" ;
    }

    private function initializeGeneralTotals($unit = 'pcs') {
        $general_totals = [];

        if ($unit === 'yds') {
            $general_totals['general_total_plan_yds'] = 0;
            $general_totals['general_total_yds_per_day'] = 0;
        } else {
            $general_totals['general_total_mi_qty'] = 0;
            $general_totals['general_total_qty_per_day'] = 0;
        }

        $general_totals['general_total_previous_accumulation'] = 0;
        $general_totals['general_total_accumulation'] = 0;
        $general_totals['general_total_balance_to_cut'] = 0;
        $general_totals['general_total_replacement'] = 0;

        return $general_totals;
    }

    private function initializeSubtotals($unit = 'pcs') {
        $subtotals = [];

        if ($unit === 'yds') {
            $subtotals['subtotal_plan_yds'] = 0;
            $subtotals['subtotal_yds_per_day'] = 0;
        } else {
            $subtotals['subtotal_mi_qty'] = 0;
            $subtotals['subtotal_qty_per_day'] = 0;
        }

        $subtotals['subtotal_previous_accumulation'] = 0;
        $subtotals['subtotal_accumulation'] = 0;
        $subtotals['subtotal_balance_to_cut'] = 0;
        $subtotals['subtotal_replacement'] = 0;

        return $subtotals;
    }

    private function updateSubtotals(&$subtotals, $laying_planning, $unit = 'pcs') {

        if ($unit === 'yds') {
            $subtotals['subtotal_plan_yds'] += $laying_planning->plan_yds;
            $subtotals['subtotal_yds_per_day'] += $laying_planning->total_qty_per_day;
        } else {
            $subtotals['subtotal_mi_qty'] += $laying_planning->order_qty;
            $subtotals['subtotal_qty_per_day'] += $laying_planning->total_qty_per_day;
        }
        
        $subtotals['subtotal_previous_accumulation'] += $laying_planning->previous_accumulation;
        $subtotals['subtotal_accumulation'] += $laying_planning->accumulation;
        $subtotals['subtotal_balance_to_cut'] += $laying_planning->balance_to_cut;
        $subtotals['subtotal_replacement'] += $laying_planning->replacement;
    }

    private function updateGeneralTotals(&$general_totals, $subtotals, $unit = 'pcs') {
        
        if ($unit === 'yds') {
            $general_totals['general_total_plan_yds'] += $subtotals['subtotal_plan_yds'];
            $general_totals['general_total_yds_per_day'] += $subtotals['subtotal_yds_per_day'];
        } else {
            $general_totals['general_total_mi_qty'] += $subtotals['subtotal_mi_qty'];
            $general_totals['general_total_qty_per_day'] += $subtotals['subtotal_qty_per_day'];
        }

        $general_totals['general_total_previous_accumulation'] += $subtotals['subtotal_previous_accumulation'];
        $general_totals['general_total_accumulation'] += $subtotals['subtotal_accumulation'];
        $general_totals['general_total_balance_to_cut'] += $subtotals['subtotal_balance_to_cut'];
        $general_totals['general_total_replacement'] += $subtotals['subtotal_replacement'];
    }

    private function calculatePlanYds($laying_planning_id)
    {
        $laying_planning = LayingPlanning::find($laying_planning_id);
        return $laying_planning->layingPlanningDetail->pluck('total_length')->sum();
    }

    private function calculateBalanceYdsToCut($laying_planning_id)
    {
        $total_balance_yds_to_cut = 0;
        $laying_planning = LayingPlanning::find($laying_planning_id);
        
        foreach ($laying_planning->layingPlanningDetail as $key => $lp_detail) {
            if(!$lp_detail->cuttingOrderRecord || !$lp_detail->cuttingOrderRecord->cut) {
                $total_balance_yds_to_cut += $lp_detail->total_length;
            }
        }
        return $total_balance_yds_to_cut;
    }
}
