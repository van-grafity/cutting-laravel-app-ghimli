<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingOrderRecord;
use App\Models\BundleCut;
use App\Models\BundleStatus;
use App\Models\CuttingTicket;
use App\Models\Gl;
use App\Models\LayingPlanning;
use Illuminate\Support\Arr;
use PDF;
use DB;

class BundleCutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    
    public function cut_piece_stock() {
        $gls = Gl::select('id', 'gl_number')->get();
        return view('page.bundle-cut.cut-piece-stock', compact('gls'));
    }

    public function cut_piece_stock_report() {
        return "Cut Piece Stock Report";
    }
    
    public function cut_piece_stock_detail() {
        return view('page.bundle-cut.cut-piece-stock-detail');
    }
    
    public function cut_piece_stock_detail_data() {
        // $query = DB::table('laying_plannings')
        // ->join('laying_planning_details', 'laying_plannings.id', '=', 'laying_planning_details.laying_planning_id')
        // ->join('cutting_order_records', 'laying_planning_details.id', '=', 'cutting_order_records.laying_planning_detail_id')
        // ->join('cutting_order_record_details', 'cutting_order_records.id', '=', 'cutting_order_record_details.cutting_order_record_id')
        // ->join('cutting_tickets', 'cutting_order_records.id', '=', 'cutting_tickets.cutting_order_record_id')
        // ->join('bundle_cuts', 'cutting_tickets.id', '=', 'bundle_cuts.ticket_id')
        // ->join('bundle_statuses', 'bundle_cuts.status_id', '=', 'bundle_statuses.id')
        // ->get();
        // return $query;

        $query = LayingPlanning::with('gl', 'layingPlanningDetail', 'layingPlanningDetail.cuttingOrderRecord', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail.cuttingTicket', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail.cuttingTicket.bundleCuts', 'layingPlanningDetail.cuttingOrderRecord.cuttingOrderRecordDetail.cuttingTicket.bundleCuts.bundleStatus')
        // where gl 63788-00 dummy
        // ->where('gl_number', 'like', '%' . '63788-00' . '%')
        ->whereHas('gl', function($q) {
            $q->where('gl_number', 'like', '%' . '63788-00' . '%');
        })
        ->get();
        return $query;
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
