<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LayingPlanning;
use App\Models\Groups;
use App\Models\UserGroups;
use App\Models\Gl;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use PDF;


class CuttingOutputReportController extends Controller
{
    public function index()
    {
        $groups = Groups::all();
        $data = [
            'groups' => $groups
        ];
        return view('page.cutting-output-report.index', $data);
    }

    public function print(Request $request) {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $groups = $request->groups;

        // ## adjust start_date and end_date for day and night shift
        $start_datetime =  Carbon::parse($start_date)->format('Y-m-d 07:00:00');
        $end_datetime =  Carbon::parse($end_date)->addDay()->format('Y-m-d 06:59:00');

        $groups = explode(',', $groups);
        $groups = array_map('intval', $groups);
        $groups = array_unique($groups);

        $all_users_selected = [];
        foreach ($groups as $key => $group_id) {
            $users_in_group = $this->getUserIdByGroup($group_id);
            $all_users_selected = array_merge($all_users_selected,$users_in_group);
        }
        $all_users_selected = array_unique($all_users_selected);
        sort($all_users_selected);


        $group_list = Groups::whereIn('id', $groups)->get();
        $group_name_list = ($group_list->pluck('group_name'));
        
        $group_name_list = implode('; ', $group_name_list->toArray());

        $gl_list = GL::select('gls.*')
            ->join('laying_plannings','laying_plannings.gl_id','=','gls.id')
            ->join('laying_planning_details','laying_planning_details.laying_planning_id','=','laying_plannings.id')
            ->join('cutting_order_records','cutting_order_records.laying_planning_detail_id','=','laying_planning_details.id')
            ->join('cutting_order_record_details','cutting_order_record_details.cutting_order_record_id','=','cutting_order_records.id')
            ->whereIn('cutting_order_record_details.user_id',$all_users_selected)
            ->where('cutting_order_records.cut', '>=', $start_datetime)
            ->where('cutting_order_records.cut', '<', $end_datetime)
            ->groupBy('gls.id')
            ->orderBy('gls.gl_number')
            ->get();


        $general_total_pcs = 0;
        $general_total_dozen = 0;

        foreach ($gl_list as $key_lp => $gl) {
            $gl->style = $this->getStyleByGL($gl->id);
            $total_pcs_per_gl = 0;

            $cor_list = CuttingOrderRecord::select(
                    'cutting_order_records.id as cor_id',
                    'cutting_order_records.serial_number as cor_serial_number',
                    'cutting_order_records.cut as cut_date',
                    'laying_planning_details.no_laying_sheet',
                    'cutting_order_records.laying_planning_detail_id',
                    'colors.color',
                )
                ->join('laying_planning_details','laying_planning_details.id','=','cutting_order_records.laying_planning_detail_id')
                ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
                ->join('cutting_order_record_details','cutting_order_record_details.cutting_order_record_id','=','cutting_order_records.id')
                ->join('colors','colors.id','=','laying_plannings.color_id')
                ->where('laying_plannings.gl_id', $gl->id)
                ->whereIn('cutting_order_record_details.user_id', $all_users_selected)
                ->where('cutting_order_records.cut', '>=', $start_datetime)
                ->where('cutting_order_records.cut', '<', $end_datetime)
                ->groupBy('cutting_order_records.id')
                ->get();

            
            foreach ($cor_list as $key_cor => $cor) {

                $cutting_order_record_detail = CuttingOrderRecordDetail::where('cutting_order_record_id', $cor->cor_id)->get();
                $cor_layer = $cutting_order_record_detail->sum('layer');
                $cor_total_ratio = 0;

                $ratio_per_size_in_summary = [];
                $cor_size_list = $cor->layingPlanningDetail->layingPlanningDetailSize;
                $cor_ratio = $cor_size_list->sum('ratio_per_size');

                $cor->cor_layer = $cor_layer;
                $cor->cor_ratio = $cor_ratio;
                $cor->cor_pcs = $cor_layer * $cor_ratio;

                $total_pcs_per_gl += $cor->cor_pcs;
            }

            $gl->quantity_cut_pcs = $total_pcs_per_gl;
            $gl->quantity_cut_dozen = number_format((float) ($total_pcs_per_gl / 12), 2, '.', '');

            $general_total_pcs += $gl->quantity_cut_pcs;
            $general_total_dozen += $gl->quantity_cut_dozen;
        }

        $data = [
            'start_date' => Carbon::parse($start_date)->format('d M Y'),
            'end_date' => Carbon::parse($end_date)->format('d M Y'),
            'general_total_pcs' => $general_total_pcs,
            'gl_list' => $gl_list,
            'general_total_dozen' => $general_total_dozen,
            'group_name_list' => $group_name_list,
        ];
        
        
        // return view('page.cutting-output-report.print', $data);
        $filename = 'Cutting Output Report.pdf';
        $pdf = PDF::loadview('page.cutting-output-report.print', $data)->setPaper('a4', 'portrait');
        return $pdf->stream($filename);
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

    public function getStyleByGL($gl_id)
    {
        $style_list = [];
        $gl = GL::find($gl_id);
        foreach ($gl->layingPlanning as $key => $laying_planning) {
            $style_list[] = $laying_planning->style->style;
        }
        $style_list = array_unique($style_list);
        $style_list = implode(' | ', $style_list);
        return $style_list;
    }
}