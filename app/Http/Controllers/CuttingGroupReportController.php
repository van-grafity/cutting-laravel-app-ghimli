<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Models\User;
use App\Models\Groups;
use App\Models\UserGroups;
use App\Models\Gl;
use Carbon\Carbon;

use PDF;

class CuttingGroupReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Groups::orderBy('id', 'asc')->get();
        return view('page.cutting-group-report.index', compact('group'));
    }
    
    public function print(Request $request)
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

        $pdf = PDF::loadView('page.cutting-group-report.print', compact('group','date_start', 'date_end','cutting_summary','all_size_in_summary','general_total_pcs','general_total_dozen'))->setPaper('a4', 'landscape');
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

}
