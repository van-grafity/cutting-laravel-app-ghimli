<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Traits\ApiHelpers;
use App\Models\BundleCut;
use App\Models\BundleStatus;
use App\Models\CuttingTicket;

class BundleCutsController extends Controller
{

    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "test";
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
        $input_bundle_cut = $request->all();
        $cutting_ticket = CuttingTicket::where('serial_number', $input_bundle_cut['serial_number'])->first();
        if ($cutting_ticket == null) return $this->onError(404, 'Cutting Ticket not found.');
        $bundle_cut = new BundleCut;
        $bundle_cut->ticket_id = $cutting_ticket->id;
        $bundle_status = BundleStatus::where('status', $input_bundle_cut['status'])->first();
        if ($bundle_status == null) return $this->onError(404, 'Bundle Status not found.');
        $bundle_cut->status_id = $bundle_status->id;
        $bundle_cut->remarks = $input_bundle_cut['remarks'];
        $bundle_cut->save();

        $data = BundleCut::where('bundle_cuts.id', $bundle_cut->id)->with('ticket', 'status')->first();
        $data = collect(
            [
                'bundle_cut' => $data
            ]
        );
        return $this->onSuccess($data, 'Bundle Cut created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
