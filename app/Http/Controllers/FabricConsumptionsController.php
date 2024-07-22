<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gl;
use App\Models\Buyer;
use App\Models\LayingPlanning;
use PDF;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class FabricConsumptionsController extends Controller
{
    public function index()
    {
        $gls = GL::all();
        $buyers = BUYER::all();
        $data = [
            'title' => 'Fabric Consumption Report',
            'page_title' => 'Fabric Consumption Report',
            'gls' => $gls,
            'buyers' => $buyers,
        ];
        return view('page.fabric-consumption.index', $data);
    }

    public function print(Request $request)
    {
        $gl_ids = null;
        if($request->gl_number && !is_array($request->gl_number)){
            $gl_ids = explode(',', $request->gl_number);
        } else{
            $gl_ids = $request->gl_number;
        }

        $laying_planning =  LayingPlanning::with(['gl']);

        if($request->buyer){
            $laying_planning->where('buyer_id', $request->buyer);
        }
        if($gl_ids){
            $laying_planning->whereIn('gl_id', $gl_ids);
        }

        $laying_planning = $laying_planning->get();

        $data = [];
        foreach ($laying_planning as $lp){
            $planning_consumption = $lp->layingPlanningDetail->sum('total_length');
            $actual_consumption = $this->getActualConsumption($lp);
            $balance = number_format(($actual_consumption - $planning_consumption),2);
            $sign = ($balance > 0) ? '+' : '';

            $completion_planning_consumption = $lp->layingPlanningDetail->sum('total_length');
            $completion_balance = $completion_planning_consumption == 0 ? 0 : round(($actual_consumption / $completion_planning_consumption) * 100, 2);

            $data[] = [
                'gl_number'=> $lp->gl->gl_number,
                'color'=> $lp->color->color,
                'planning_consumption' => number_format($lp->layingPlanningDetail->sum('total_length'),2) . ' Yds',
                'balance' => $sign . $balance . ' Yds',
                'actual_consumption' => number_format($this->getActualConsumption($lp),2) . ' Yds',
                'completion' => $completion_balance . ' %',
                'replacement' => 0,
            ];
        }
        $filename = 'Fabric Consumption Report.pdf';
        $pdf = PDF::loadview('page.fabric-consumption.fabric-consumption-report', compact('data'));
        return $pdf->stream($filename);
    }

    public function print_preview()
    {
        $gl_ids = null;
        if(request()->gl_number && !is_array(request()->gl_number)) {
            $gl_ids = explode(',', request()->gl_number);
        } else {
            $gl_ids = request()->gl_number;
        }

        $query = LayingPlanning::with(['gl']);
        if (request()->buyer) {
            $query->where('buyer_id', request()->buyer);
        }
        if ($gl_ids) {
            $query->whereIn('gl_id', $gl_ids);
        }

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('gl_number', function($row){
                return $row->gl->gl_number;
            })
            ->addColumn('color', function($row){
                return $row->color->color;
            })
            ->addColumn('planning_consumption', function($row){
                return number_format($row->layingPlanningDetail->sum('total_length'),2) . ' Yds';
            })
            ->addColumn('actual_consumption', function($row){
                return number_format($this->getActualConsumption($row),2) . ' Yds';
            })
            ->addColumn('balance', function($row) {
                $planning_consumption = $row->layingPlanningDetail->sum('total_length');
                $actual_consumption = $this->getActualConsumption($row);
                $balance = number_format(($actual_consumption - $planning_consumption),2);

                // ## for a clearer status, whether the amount is excess or deficiency. give a sign
                $sign = ($balance > 0) ? '+' : '';
                return $sign . $balance . ' Yds';
            })
            ->addColumn('completion', function($row) {
                $planning_consumption = $row->layingPlanningDetail->sum('total_length');
                if($planning_consumption == 0) { return 0; }

                $actual_consumption = $this->getActualConsumption($row);
                $balance = round(($actual_consumption / $planning_consumption) * 100,2);
                return $balance . ' %';
            })
            ->addColumn('replacement', function($row) {
                return '0';
            })
            ->toJson();
    }

    private function getActualConsumption(LayingPlanning $laying_planning)
    {
        $total_actual_consumption = 0;
        $laying_planning_detail = $laying_planning->layingPlanningDetail;
        foreach ($laying_planning_detail as $key => $lp_detail) {
            if(Str::contains(Str::lower($lp_detail->marker_code), 'repl')) { continue; } // ## replacement not count
            if(!$lp_detail->CuttingOrderRecord) { continue ;} // ## skip when have no cutting order record

            $total_actual_layer = $lp_detail->CuttingOrderRecord->CuttingOrderRecordDetail->sum('layer');
            $total_actual_consumption += $total_actual_layer * $lp_detail->marker_length;
        }
        return $total_actual_consumption;
    }

}
