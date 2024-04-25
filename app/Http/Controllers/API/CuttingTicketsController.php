<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Traits\ApiHelpers;

use App\Models\CuttingTicket;

class CuttingTicketsController extends BaseController
{

    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CuttingTicket::with('cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')->latest()->get();
        $data = collect(
            [
                'cutting_ticket' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Ticket retrieved successfully.');
    }

    public function show(Request $request)
    {
        $data = CuttingTicket::with('size', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.gl', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.buyer', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.style', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.color', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.fabricType', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.fabricCons', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')->where('serial_number', $request->serial_number)->first();
        if ($data == null) return $this->onError(404, 'Cutting Ticket not found.');
        $data = collect(
            [
                'cutting_ticket' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Ticket retrieved successfully.');
    }

}
