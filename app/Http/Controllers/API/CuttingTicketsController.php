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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        $data = CuttingTicket::with('bundleCuts.bundleStatus', 'size', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.gl', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.buyer', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.style', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.color', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.fabricType', 'cuttingOrderRecord.layingPlanningDetail.layingPlanning.fabricCons', 'cuttingOrderRecordDetail', 'cuttingOrderRecordDetail.color')->where('serial_number', $request->serial_number)->first();
        if ($data == null) return $this->onError(404, 'Cutting Ticket not found.');
        $data = collect(
            [
                'cutting_ticket' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Ticket retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
