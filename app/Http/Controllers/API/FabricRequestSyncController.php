<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Traits\ApiHelpers;
use App\Models\GL;
use App\Models\LayingPlanning;
use App\Models\FabricRequisition;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use Carbon\Carbon;

class FabricRequestSyncController extends Controller
{

    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'status' => 'success'
        ];
        return $this->onSuccess($data, 'Fabric Request API.');
    }

    public function getFabricRequest(Request $request)
    {
        if ($request->has('start_date') == null) return $this->onError(404, 'Attribute start_date not found!');
        if ($request->has('end_date') == null) return $this->onError(404, 'Attribute end_date not found!');
        
        $start_date =  Carbon::parse($request->start_date)->format('Y-m-d 00:00:00');
        $end_date =  Carbon::parse($request->end_date)->format('Y-m-d 23:59:59');
        
        try {
            if($request->gl_number) {
                $gl = GL::where('gl_number', 'LIKE', "%{$request->gl_number}%")->first();
            } else {
                $gl = null;
            }
            
            $fabric_requests = FabricRequisition::
                join('laying_planning_details', 'laying_planning_details.id','=','fabric_requisitions.laying_planning_detail_id')
                ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
                ->join('gls','gls.id','=','laying_plannings.gl_id')
                ->join('colors','colors.id','=','laying_plannings.color_id')
                ->where(function($query) use ($gl) {
                    if($gl) {
                        $query->where('gls.id', $gl->id);
                    }
                })
                ->where('fabric_requisitions.created_at', '>=', $start_date)
                ->where('fabric_requisitions.created_at', '<=', $end_date)
                ->select(
                    'fabric_requisitions.id as fbr_id',
                    'fabric_requisitions.serial_number as fbr_serial_number',
                    'fabric_requisitions.status_print as fbr_status_print',
                    'fabric_requisitions.remark as fbr_remark',
                    'fabric_requisitions.created_at as fbr_created_at',
                    'fabric_requisitions.updated_at as fbr_updated_at',
                    'laying_planning_details.id as laying_planning_detail_id',
                    'gls.gl_number',
                    'colors.color',
                    'laying_planning_details.table_number',
                    'laying_planning_details.total_length as qty_required',
                )
                ->get();
            
        } catch (\Throwable $th) {
            return $this->onError(500, $th->getMessage());
        }

        $data_return = [
            'fabric_requests' => $fabric_requests
        ];
        return $this->onSuccess($data_return, 'Success Retrieving ' . count($fabric_requests) . ' Fabric Requests');
    }
    
}
