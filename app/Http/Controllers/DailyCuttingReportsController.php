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
        $cuttingOrderRecord = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanningDetailSize.size', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color')
        
        ->whereHas('cuttingOrderRecordDetail', function($query) use ($date_filter) {
            $query->whereDate('created_at', $date_filter);
        })
        ->get();

        $cuttingOrderRecordPrevious = CuttingOrderRecord::with('layingPlanningDetail', 'layingPlanningDetail.layingPlanning', 'layingPlanningDetail.layingPlanningDetailSize.size', 'CuttingOrderRecordDetail', 'CuttingOrderRecordDetail.color')
        ->whereHas('cuttingOrderRecordDetail', function($query) use ($date_filter) {
            $query->whereDate('created_at', Carbon::parse($date_filter)->subDays(1));
        })
        ->get();

        $group_ids = Groups::get();
        
        $data = [];
        // $color = $cuttingOrderRecord->pluck('cuttingOrderRecordDetail')->flatten()->pluck('color')->flatten()->unique('id')->values()->all();
        $gl_datas = $cuttingOrderRecord->pluck('layingPlanningDetail')->flatten()->pluck('layingPlanning')->flatten()->pluck('gl')->flatten()->unique('id')->values()->sortBy('gl_number')->all();
        $cor_datas = $cuttingOrderRecord->pluck('layingPlanningDetail')->flatten()->pluck('layingPlanning')->flatten()->pluck('cuttingOrderRecord')->flatten()->unique('id')->values()->all();
        $cuttingOrderRecordDetails = $cuttingOrderRecord->pluck('cuttingOrderRecordDetail')->flatten()->unique('id')->values()->all();
        $cuttingOrderRecordDetailsPrevious = $cuttingOrderRecordPrevious->pluck('cuttingOrderRecordDetail')->flatten()->unique('id')->values()->all();

        foreach ($cuttingOrderRecordDetails as $key => $value) {
            $data['cutting_order_record_detail'][$key] = $value;
            $name = $value->operator;
            $user = User::where('name', $name)->first();
            $data['cutting_order_record_detail'][$key]['user'] = $user;
            $group = UserGroups::where('user_id', $user->id)->first();
            if ($group) {
                $group = Groups::where('id', $group->group_id)->first();
            } else {
                $group = Groups::where('id', 1)->first();
            }
            $data['cutting_order_record_detail'][$key]['user_group'] = $group;
        }

        foreach ($cuttingOrderRecordDetailsPrevious as $key => $value) {
            $data['cutting_order_record_detail_previous'][$key] = $value;
        }
        
        foreach ($gl_datas as $key => $value) {
            $layingPlannings = $value->layingPlanning;
            foreach ($layingPlannings as $keyLayingPlanning => $layingPlanning) {
                $data['laying_planning'][$keyLayingPlanning] = $layingPlanning;
            }
        }

        foreach ($cor_datas as $key => $value) {
            $data['cutting_order_record'][$key] = $value;
        }

        // cutting_order_record_detail.cutting_order_record_id == cutting_order_record.id (get user.id)
        // $user_ids = $data['cutting_order_record_detail']->pluck('user')->flatten()->pluck('id')->flatten()->unique('id')->values()->all();
        // $users = User::whereIn('id', $user_ids)->get();
        // $data['users'] = $users;

        // cutting order record -> total ratio
        
        $data = [
            'cutting_order_record' => $cuttingOrderRecord,
            'cutting_order_record_detail' => $data['cutting_order_record_detail'] ?? [],
            'cutting_order_record_detail_previous' => $data['cutting_order_record_detail_previous'] ?? [],
            'laying_planning' => $data['laying_planning'] ?? [],
            'group' => $group_ids ?? [],
        ];
        
        // SELECT * FROM `cutting_order_records` WHERE id IN (SELECT cutting_order_record_id FROM `cutting_order_record_details` WHERE created_at LIKE '2021-08-31%')
        
        $filename = 'Daily Cutting Output Report';
        $pdf = PDF::loadview('page.daily-cutting-report.print', compact('data', 'date_filter'))->setPaper('a4', 'landscape');
        return $pdf->stream($filename);
        // return $data;
    }
}