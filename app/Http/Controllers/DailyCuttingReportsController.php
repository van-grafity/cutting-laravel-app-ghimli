<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LayingPlanning;
use App\Models\Gl;
use App\Models\CuttingOrderRecordDetail;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;


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
            ->escapeColumns([])
            ->make(true);
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
                    $total_actual_all_table_per_size[$size] = array_sum(array_map(fn ($item) => $item[$size], $actual_size_each_cor));
                }
                // dd($total_actual_all_table_per_size, $total_actual_all_table_all_size);
                $data_layingPlanning[$keyLayingPlanning]['prev_total_qty_per_day'] = $prev_total_actual_all_table_all_size;
                $data_layingPlanning[$keyLayingPlanning]['total_qty_per_day'] = $total_actual_all_table_all_size;
                $data_layingPlanning[$keyLayingPlanning]['gl_number'] = $gl->gl_number;
                $data_layingPlanning[$keyLayingPlanning]['buyer'] = $gl->buyer->name;
                
            }
            
        }
        return $data_layingPlanning;
    }
}
