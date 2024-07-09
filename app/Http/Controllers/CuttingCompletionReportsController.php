<?php

namespace App\Http\Controllers;

use App\Models\Gl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LayingPlanning;
use Illuminate\Support\Str;
use PDF;
class CuttingCompletionReportsController extends Controller
{
    //
    public function index()
    {
        $gls = Gl::with('GLCombine')->get();
        return view('page.cutting-order.cutting-completion-report', compact('gls'));
    }

    public function cuttingCompletionReport(Request $request)
    {
        $gl_id = $request->gl_number;
        $gl = GL::find($gl_id);

        $filename = 'Cutting Completion Report GL '. $gl->gl_number . '.pdf';
        $layingPlanning = LayingPlanning::with(['gl','gl.buyer', 'color', 'style','fabricCons','fabricType', 'layingPlanningDetail', 'layingPlanningDetail.layingPlanningDetailSize', 'layingPlanningDetail.cuttingOrderRecord', 'layingPlanningDetail.fabricRequisition', 'layingPlanningDetail.fabricRequisition.fabricIssue', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail'])
            ->whereHas('gl', function($query) use ($gl_id) {
                if ($gl_id != null) {
                    $query->where('id', $gl_id);
                }
            })
            ->orderBy('id', 'asc')
            ->get();

        if($layingPlanning->isEmpty()){
            return redirect()->route('cutting-completion-report.index')->with('error', 'Planning from GL '. $gl->gl_number .' was not found');
        }

        $buyer_list = [];
        $style_list = [];
        $fabric_type_list = [];
        $fabric_cons_list = [];
        foreach ($layingPlanning as $key => $lp) {
            $buyer_list[] = $lp->gl->buyer->name;
            $style_list[] = $lp->style->style;
            $fabric_type_list[] = $lp->fabricType->name;
            $fabric_cons_list[] = $lp->fabricCons->name;
        }

        // ## Data for Completion Header
        $completion_data = [];
        $total_mi_qty = array_sum(array_column($layingPlanning->toArray(), 'order_qty'));
        $completion_data['total_mi_qty'] = $total_mi_qty;
        $completion_data['gl_number'] = $gl->gl_number;

        // ## karena sebenarnya bisa bervariasi permasing masing laying planning. untuk sekarang data seperti fabric_po, style, buyer, fabric_type, fabric_cons akan mengambil dari laying planning pertama dulu saja
        $completion_data['fabric_po'] = $layingPlanning[0]->fabric_po;
        $completion_data['buyer'] = $layingPlanning[0]->buyer->name;
        $completion_data['style'] = $layingPlanning[0]->style->style;
        $completion_data['fabric_type'] = $layingPlanning[0]->fabricType->name;
        $completion_data['fabric_cons'] = $layingPlanning[0]->fabricCons->name;
        $completion_data['plan_date'] = $layingPlanning[0]->plan_date;
        $completion_data['delivery_date'] = $layingPlanning[0]->delivery_date;

        $fabric_consumption = [];

        foreach ($layingPlanning as $key_lp => $lp) {
            $cut_qty_per_size = [];
            $cut_qty_all_size = 0;

            $replacement_qty_per_size = [];
            $replacement_qty_all_size = 0;

            $diff_qty_per_size = [];
            $diff_qty_all_size = 0;

            foreach ($lp->layingPlanningSize as $key_lp_size => $lp_size) {
                $cut_qty_size = 0;
                $replacement_qty = 0;
                $diff_qty_size = 0;

                foreach ($lp->layingPlanningDetail as $lp_detail) {
                    if(!Str::contains(Str::lower($lp_detail->marker_code), 'repl')){
                        foreach($lp_detail->layingPlanningDetailSize as $lp_detail_size) {
                            if ($lp_detail_size->size_id == $lp_size->size_id) {
                                if(!$lp_detail->cuttingOrderRecord){
                                    $cut_qty_size += 0;
                                } else {
                                    if(!$lp_detail->cuttingOrderRecord->cut){
                                        $cut_qty_size += 0;
                                    } else {
                                        foreach ($lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail as $cor_detail)
                                        {
                                            $cut_qty_size += $cor_detail->layer * $lp_detail_size->ratio_per_size;
                                        }
                                    }
                                }

                            }
                        }

                    } else {
                        foreach($lp_detail->layingPlanningDetailSize as $lp_detail_size) {
                            if ($lp_detail_size->size_id == $lp_size->size_id) {
                                if(!$lp_detail->cuttingOrderRecord){
                                    $replacement_qty += 0;
                                } else {
                                    if(!$lp_detail->cuttingOrderRecord->cut){
                                        $replacement_qty += 0;
                                    } else {
                                        foreach ($lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail as $cor_detail)
                                        {
                                            $replacement_qty += $cor_detail->layer * $lp_detail_size->ratio_per_size;
                                        }
                                    }
                                }

                            }
                        }
                    }
                }

                $cut_qty_per_size[$key_lp_size] = $cut_qty_size;
                $replacement_qty_per_size[$key_lp_size] = $replacement_qty;
                $diff_qty_size = $cut_qty_size - $lp_size->quantity;
                $diff_qty_per_size[$key_lp_size] = ($diff_qty_size > 0) ? '+' . $diff_qty_size : $diff_qty_size;
            }

            $cut_qty_all_size = array_sum($cut_qty_per_size);
            $replacement_qty_all_size = array_sum($replacement_qty_per_size);
            $diff_qty_all_size = array_sum($diff_qty_per_size);

            $layingPlanning[$key_lp]->cut_qty_per_size = $cut_qty_per_size;
            $layingPlanning[$key_lp]->cut_qty_all_size = $cut_qty_all_size;

            $layingPlanning[$key_lp]->replacement_qty_per_size = $replacement_qty_per_size;
            $layingPlanning[$key_lp]->replacement_qty_all_size = $replacement_qty_all_size;

            $layingPlanning[$key_lp]->diff_qty_per_size = $diff_qty_per_size;
            $layingPlanning[$key_lp]->diff_qty_all_size = ($diff_qty_all_size > 0) ? '+' . $diff_qty_all_size : $diff_qty_all_size;

            $diff_percentage = round((($cut_qty_all_size / $lp->order_qty) * 100) , 1);
            $diff_percentage_color = $diff_percentage < 100 ? 'red' : ($diff_percentage > 100 ? 'blue' : '');
            $layingPlanning[$key_lp]->diff_percentage = $diff_percentage;
            $layingPlanning[$key_lp]->diff_percentage_color = $diff_percentage_color;

            $layingPlanning[$key_lp]->color_colspan = count($cut_qty_per_size);


            // ## calculate fabric consuption

            $fabric_request = 0; // ## total length by laying planning detail
            $fabric_received = 0; // ## total length by roll sticker in COR Detail
            $diff_request_and_received = 0; // ## selisih antara yang diminta (request) dan yang diterima (received)
            $actual_used = 0; // ## total length by marker length in laying planning detail di kali dengan layer di cor detail
            $diff_received_and_used = 0; // ##  selisih antara yang diterima (received) dengan yang digunakan (used)

            foreach ($lp->layingPlanningDetail as $key_lp_detail => $lp_detail) {
                $fabric_request += $lp_detail->total_length;
                if($lp_detail->cuttingOrderRecord) {
                    $fabric_received += $lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail->sum('yardage');
                    $actual_used += $lp_detail->cuttingOrderRecord->cuttingOrderRecordDetail->sum('layer') * $lp_detail->marker_length;
                }
            }

            $diff_request_and_received = round($fabric_received - $fabric_request, 3);
            $diff_request_and_received = ($diff_request_and_received > 0) ? '+' . $diff_request_and_received : $diff_request_and_received;
            $diff_received_and_used = round($fabric_received - $actual_used, 3);
            $diff_received_and_used = ($diff_received_and_used > 0) ? '+' . $diff_received_and_used : $diff_received_and_used;

            $fabric_consumption[$key_lp] = (object) [
                'color' => $lp->color->color,
                'fabric_request' => $lp->layingPlanningDetail->sum('total_length'),
                'fabric_received' => $fabric_received,
                'diff_request_and_received' => $diff_request_and_received,
                'actual_used' => $actual_used,
                'diff_received_and_used' => $diff_received_and_used,
            ];
        }

        $completion_data['total_output_qty'] = $layingPlanning->sum('cut_qty_all_size');
        $completion_data['total_replacement'] = $layingPlanning->sum('replacement_qty_all_size');
        $diff_output_mi_qty = $completion_data['total_output_qty'] - $total_mi_qty;
        $completion_data['diff_output_mi_qty'] = ($diff_output_mi_qty > 0) ? '+' . $diff_output_mi_qty : $diff_output_mi_qty;

        $laying_plannings = $layingPlanning->chunk(2);

        $data = [
            'laying_plannings' => $laying_plannings,
            'completion_data' => (object) $completion_data,
            'fabric_consumption' => $fabric_consumption,
        ];

        // return view('page.cutting-order.completion-report', $data);
        $pdf = PDF::loadview('page.cutting-order.completion-report', $data)->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
    }

}
