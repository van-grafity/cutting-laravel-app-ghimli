<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\CuttingOrderRecordDetail;
use App\Http\Traits\ApiHelpers;
use App\Http\Controllers\API\BaseController as BaseController;

class CuttingOrdersController extends BaseController
{
    use ApiHelpers;

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        $data = CuttingOrderRecord::with(['layingPlanningDetail'])->get();
        $data = collect(
            [
                'cuttingOrderRecord' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    public function show($id)
    {
        $data = CuttingOrderRecord::with(['layingPlanningDetail'])->find($id);
        $data = collect(
            [
                'cuttingOrderRecord' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');
    }

    // store cuttingorderrecorddetail by cuttingorderrecord
    public function store(Request $request)
    {
        $input = $request->all();
        $data = CuttingOrderRecordDetail::create($input);
        $data = collect(
            [
                'cuttingOrderRecordDetail' => $data
            ]
        );
        return $this->onSuccess($data, 'Cutting Order Record Detail created successfully.');
    }
}