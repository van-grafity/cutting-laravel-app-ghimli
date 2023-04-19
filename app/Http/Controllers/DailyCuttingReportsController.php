<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LayingPlanning;
use App\Models\Gl;

use Carbon\Carbon;


class DailyCuttingReportsController extends Controller
{
    public function index()
    {
        // $data = LayingPlanning::get();
        $date_today = Carbon::now()->toDateTimeString();
        // $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'layingPlanningDetail'])->get();
        
        $data_result = [];
        $gls = Gl::get();
        foreach ($gls as $key => $gl) {
            $data_gl = [
                'gl_number' => $gl->gl_number,
                'buyer' => $gl->buyer->name,
            ];
            $layingPlannings = $gl->layingPlanning;
                foreach ($layingPlannings as $key => $layingPlanning) {
                    $data_layingPlanning = [
                    'style' => $layingPlanning->style->style,
                    'color' => $layingPlanning->color->color,
                    'mi_qty' => $layingPlanning->order_qty,
                ];
                $layingPlanningDetails = $layingPlanning->layingPlanningDetail;
                $size_list = $layingPlanning->layingPlanningSize->pluck('size.size')->toArray();

                // ## $total_actual_all_table_all_size adalah Total dari seluruh cutting table (laying planning detail) dalam laying planning yang sama. Ini untuk semua size.
                $total_actual_all_table_all_size = 0;

                // ## $total_actual_all_table_per_size adalah Total dari seluruh cutting table (laying planning detail) dalam laying planning yang sama. dan di rinci untuk tiap size nya.
                $total_actual_all_table_per_size = [];

                foreach ($layingPlanningDetails as $key => $layingPlanningDetail) {

                    $cutting_table = $layingPlanningDetail->cuttingOrderRecord->cuttingOrderRecordDetail;
                    $layingPlanningDetailSizes = $layingPlanningDetail->layingPlanningDetailsize;
                    
                    // ## $actual_cutting_layer Data from cutting table (record using android)
                    $actual_cutting_layer = $cutting_table->sum('layer');

                    $actual_all_size = []; 
                    $total_actual_size = 0;

                    foreach ($layingPlanningDetailSizes as $key => $layingPlanningDetailSize) {

                        // ## jumlah layer yang ada di cutting table di kali dengan rasio per size
                        $actual_each_size = $actual_cutting_layer * $layingPlanningDetailSize->ratio_per_size;

                        // ## hasil perkalian ($actual_each_size). di jumlahkan semua. sehingga $total_actual_size adalah total pcs baju dari semua size
                        $total_actual_size += $actual_each_size;
                        
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
                    $total_actual_all_table_all_size += $total_actual_size;
                }

                foreach ($size_list as $key => $size) {
                    $total_actual_all_table_per_size[$size] = array_sum(array_map(fn ($item) => $item[$size], $actual_size_each_cor));
                }
                dd($total_actual_all_table_per_size, $total_actual_all_table_all_size);
            }
            
        }

        return view('page.daily-cutting-report.index');
    }
}
