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

        // dd($this->calculate_daily_cutting());

        return view('page.daily-cutting-report.index');
    }

    public function dataDailyCutting(Request $request) {

        $date_filter = $request->date ? $request->date : Carbon::now()->toDateString();

        return Datatables::of($this->calculate_daily_cutting($date_filter))
            ->addIndexColumn()
            ->addColumn('action', function($data) use ($date_filter){
                return '
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick=show_detail('.$data['id'].',"'.$date_filter.'")>Detail</a>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function dailyCuttingDetail(Request $request) {
        
        $date_filter = $request->filter_date ? $request->filter_date : Carbon::now()->toDateString();
        $layingPlanning = LayingPlanning::find($request->id);

        $params = (object)[
            'laying_planning' => $layingPlanning,
            'gl' => $layingPlanning->gl_id,
            'style' => $layingPlanning->style_id,
            'color' => $layingPlanning->color_id,
            'date' => $date_filter,
            // 'date' => '2023-04-17',
        ];
        $daily_detail = $this->get_daily_detail($params);
        $date_return = [
            'status' => 'success',
            'data'=> $daily_detail,
            'message'=> 'Successfully Getting Data Daily Detail',
        ];
        return response()->json($date_return, 200);
    }

    public function dailyCuttingReport(Request $request) {
        $date_filter = $request->date;
        // where('created_at','>=', Carbon::createFromFormat('Y-m-d', $date_filter)->startOfDay()->toDateTimeString())->where('created_at','<=', Carbon::createFromFormat('Y-m-d', $date_filter)->endOfDay()->toDateTimeString()
        $cuttingOrderRecord = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color')
        
        ->whereHas('cuttingOrderRecordDetail', function($query) use ($date_filter) {
            $query->whereDate('created_at', $date_filter);
        })
        ->get();
        
        $data = [];
        // $color = $cuttingOrderRecord->pluck('cuttingOrderRecordDetail')->flatten()->pluck('color')->flatten()->unique('id')->values()->all();
        $gl_datas = $cuttingOrderRecord->pluck('layingPlanningDetail')->flatten()->pluck('layingPlanning')->flatten()->pluck('gl')->flatten()->unique('id')->values()->sortBy('gl_number')->all();
        

        foreach ($gl_datas as $key => $value) {
            $layingPlannings = $value->layingPlanning;
            foreach ($layingPlannings as $keyLayingPlanning => $layingPlanning) {
                $data['laying_planning'][$keyLayingPlanning] = [
                    'gl_number' => $layingPlanning->gl->gl_number,
                    'buyer' => $layingPlanning->gl->buyer->name,
                    'style' => $layingPlanning->style->style,
                    'color' => $layingPlanning->color->color,
                    'order_qty' => $layingPlanning->order_qty,
                    'previous_balance' => '',
                    'total_qty_per_day' => $layingPlanning,
                    'completed' => '',
                    'accumulation' => '',
                    'laying_planning_detail' => $layingPlanning->layingPlanningDetail,
                ];
            }
            // ksort($data['laying_planning']);
        }

        // foreach ($cuttingOrderRecord as $key => $data_daily) {
        //     $data['laying_planning'][$key] = [
        //         'gl_number' => $data_daily->layingPlanningDetail->layingPlanning->gl->gl_number,
        //         'buyer' => $data_daily->layingPlanningDetail->layingPlanning->gl->buyer->name,
        //         'style' => $data_daily->layingPlanningDetail->layingPlanning->style->style,
        //         'color' => $data_daily->layingPlanningDetail->layingPlanning->color->color,
        //         'table_number' => $data_daily->layingPlanningDetail->table_number,
        //         'order_qty' => $data_daily->layingPlanningDetail->layingPlanning->order_qty,
        //         'total_qty_per_day' => $data_daily->cuttingOrderRecordDetail->sum('layer'),
        //         'accumulation' => $data_daily->cuttingOrderRecordDetail->sum('layer'),
        //         'completed' => round($data_daily->cuttingOrderRecordDetail->sum('layer') / $data_daily->layingPlanningDetail->layingPlanning->order_qty * 100) . '%',
        //     ];
        // }

        $filename = 'Daily Cutting Output Report';
        $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data', 'date_filter'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
        // return $data;
    }

    function calculate_daily_cutting($date_filter) {
        
        $date_today = $date_filter;
        $date_today_start = Carbon::createFromFormat('Y-m-d', $date_today)->startOfDay()->toDateTimeString();
        $date_today_end = Carbon::createFromFormat('Y-m-d', $date_today)->endOfDay()->toDateTimeString();

        $data_result = [];
        $gls = Gl::get();
        foreach ($gls as $key => $gl) {
            $data_gl[$key] = [
                'gl_number' => $gl->gl_number,
                'buyer' => $gl->buyer->name,
                'laying_planning' => $gl->layingPlanning,
            ];
            $layingPlannings = $gl->layingPlanning;

            foreach ($layingPlannings as $keyLayingPlanning => $layingPlanning) {

                $data_layingPlanning[$keyLayingPlanning] = [
                    'style' => $layingPlanning->style->style,
                    'color' => $layingPlanning->color->color,
                    'mi_qty' => $layingPlanning->order_qty,
                ];
                $layingPlanningDetails = $layingPlanning->layingPlanningDetail;
                $size_list = $layingPlanning->layingPlanningSize->pluck('size.size')->toArray();

                // ## $total_actual_all_table_all_size adalah Total dari seluruh cutting table (laying planning detail) dalam laying planning yang sama. Ini untuk semua size.
                $total_actual_all_table_all_size = 0;
                $prev_total_actual_all_table_all_size = 0;

                // ## $total_actual_all_table_per_size adalah Total dari seluruh cutting table (laying planning detail) dalam laying planning yang sama. dan di rinci untuk tiap size nya.
                $total_actual_all_table_per_size = [];

                foreach ($layingPlanningDetails as $key => $layingPlanningDetail) {
                    
                    // ## Skip cutting table (laying planning detail) kalau COR nya belum dibuat. artinya tidak ada yang perlu dihitung
                    if(!$layingPlanningDetail->cuttingOrderRecord){ continue; }

                    // ## perulangan untuk setiap cutting table (laying planning detail)
                    $cutting_table = $layingPlanningDetail->cuttingOrderRecord->cuttingOrderRecordDetail
                                    ->where('created_at','>=', $date_today_start)
                                    ->where('created_at','<=', $date_today_end);
                    $layingPlanningDetailSizes = $layingPlanningDetail->layingPlanningDetailsize;
                    
                    // ## $actual_cutting_layer Data from cutting table (record using android)
                    $actual_cutting_layer = $cutting_table->sum('layer');

                    $previous_actual_cutting = $layingPlanningDetail->cuttingOrderRecord->cuttingOrderRecordDetail
                                    ->where('created_at','<=', $date_today_start)->sum('layer');


                    $actual_all_size = []; 
                    $total_actuala_all_size = 0;
                    $prev_total_actuala_all_size = 0;

                    foreach ($layingPlanningDetailSizes as $key => $layingPlanningDetailSize) {

                        // ## jumlah layer yang ada di cutting table di kali dengan rasio per size
                        $actual_each_size = $actual_cutting_layer * $layingPlanningDetailSize->ratio_per_size;
                        $prev_actual_each_size = $previous_actual_cutting * $layingPlanningDetailSize->ratio_per_size;

                        // ## hasil perkalian ($actual_each_size). di jumlahkan semua. sehingga $total_actuala_all_size adalah total pcs baju dari semua size
                        $total_actuala_all_size += $actual_each_size;
                        $prev_total_actuala_all_size += $prev_actual_each_size;
                        
                        // ## melakukan penyimpanan untuk total pcs per size dalam bentuk array di dalam variable $sum_each_size dengan format 
                        /*
                            [
                                ['size1' => jumlah_size1],
                                ['size2' => jumlah_size2],
                                dst
                            ] 
                        */
                        $sum_each_size[$layingPlanningDetailSize->size->size] = $actual_each_size;
                    }

                    $actual_size_each_cor[] = $sum_each_size;
                    $total_actual_all_table_all_size += $total_actuala_all_size;
                    $prev_total_actual_all_table_all_size += $prev_total_actuala_all_size;
                }

                foreach ($size_list as $key => $size) {
                    // dd(array_map(fn ($item) => $item[$size], $actual_size_each_cor), $size);
                    $total_actual_all_table_per_size[$size] = array_sum(
                        array_map(fn ($item) => array_key_exists($size,$item) ? $item[$size] : 0, $actual_size_each_cor)
                    );
                }
                // dd($total_actual_all_table_per_size, $total_actual_all_table_all_size);

                $previous_balance = $layingPlanning->order_qty - $prev_total_actual_all_table_all_size;
                $accumulation = $total_actual_all_table_all_size + $prev_total_actual_all_table_all_size;
                $completed = round($accumulation / $layingPlanning->order_qty * 100) . '%';

                $data_layingPlanning[$keyLayingPlanning]['id'] = $layingPlanning->id;
                $data_layingPlanning[$keyLayingPlanning]['gl_number'] = $gl->gl_number;
                $data_layingPlanning[$keyLayingPlanning]['buyer'] = $gl->buyer->name;
                $data_layingPlanning[$keyLayingPlanning]['previous_balance'] = $previous_balance;
                $data_layingPlanning[$keyLayingPlanning]['total_qty_per_day'] = $total_actual_all_table_all_size;
                $data_layingPlanning[$keyLayingPlanning]['accumulation'] = $accumulation;
                $data_layingPlanning[$keyLayingPlanning]['completed'] = $completed;
                
            }
            
        }
        return $data_layingPlanning;
    }

    function get_daily_detail($params) {
        
        $laying_planning = $params->laying_planning;
        $filter_gl = $params->gl;
        $filter_style = $params->style;
        $filter_color = $params->color;
        $filter_date = $params->date;
        
        $date_today_start = Carbon::createFromFormat('Y-m-d', $filter_date)->startOfDay()->toDateTimeString();
        $date_today_end = Carbon::createFromFormat('Y-m-d', $filter_date)->endOfDay()->toDateTimeString();

        $layingPlanningDetail = $laying_planning->layingPlanningDetail;
        $daily_detail_all_operator = [];

        foreach ($layingPlanningDetail as $key => $detail) {
            $sum_ratio_all_size = $detail->layingPlanningDetailSize->sum('ratio_per_size');
            $cor = $detail->cuttingOrderRecord;

            if(!$cor) {
                continue;
            }

            $layer_per_operator = DB::table('cutting_order_record_details as cord')
                    ->join('cutting_order_records as cor', 'cor.id', '=', 'cord.cutting_order_record_id')
                    ->select('operator', DB::raw('sum(layer) as total_layer'))
                    ->where('cor.id', $cor->id)
                    ->where('cord.created_at','>=', $date_today_start)
                    ->where('cord.created_at','<=', $date_today_end)
                    ->groupBy('operator')
                    ->get();

            foreach ($layer_per_operator as $operator) {
                $daily_detail_operator['operator'] = $operator->operator;
                $daily_detail_operator['total_qty'] = $operator->total_layer * $sum_ratio_all_size;
                $daily_detail_all_operator[] = $daily_detail_operator;
            }
        }
                            
        $result = $daily_detail_all_operator;
        return $result;
    }

    public function print_total_size_ratio($layingPlanningDetail) {
        $get_size_ratio = LayingPlanningDetailSize::where('laying_planning_detail_id', $layingPlanningDetail->id)->get();
        $total_size_ratio = 0;
        foreach( $get_size_ratio as $key => $size ) {
            $total_size_ratio += $size->ratio_per_size;
        }
        return $total_size_ratio;
    }
}
