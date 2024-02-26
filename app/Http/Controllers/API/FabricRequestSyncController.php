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
        $data_input = $request->all();
        $gl = GL::where('gl_number', $data_input['gl_number'])->first();
        if ($gl == null) return $this->onError(404, 'GL Not Found!');
        try {
            // ## Get Laying Planning from GL
            $laying_plannings = LayingPlanning::where('gl_id',$gl->id)->get();
            foreach ($laying_plannings as $key => $laying_planning) {
                $fabric_requests = FabricRequisition::
                    join('laying_planning_details', 'laying_planning_details.id','=','fabric_requisitions.laying_planning_detail_id')
                    ->join('laying_plannings','laying_plannings.id','=','laying_planning_details.laying_planning_id')
                    ->join('gls','gls.id','=','laying_plannings.gl_id')
                    ->join('colors','colors.id','=','laying_plannings.color_id')
                    ->where('laying_planning_details.laying_planning_id', $laying_planning->id)
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

                $laying_planning->fabric_requests = $fabric_requests;
            }
            
        } catch (\Throwable $th) {
            return $this->onError(500, $th->getMessage());
        }

        $data_return = [
            'laying_plannings' => $laying_plannings
        ];
        return $this->onSuccess($data_return, 'Success Retrieving Fabric Request of GL ' . $gl->gl_number);
    }
    
}
