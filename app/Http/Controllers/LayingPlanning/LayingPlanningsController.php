<?php

namespace App\Http\Controllers\LayingPlanning;

use App\Http\Controllers\Controller;
use App\Models\LayingPlanning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayingPlanningsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LayingPlanning::with(['gl', 'style', 'buyer', 'color', 'fabricType'])->get();
        return view('page.layingPlanning.index', compact('data'));
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

    public function layingCreate()
    {
        $gls = DB::table('gls')->get();
        $buyers = DB::table('buyers')->get();
        $styles = DB::table('styles')->get();
        $colors = DB::table('colors')->get();
        $fabricTypes = DB::table('fabric_types')->get();
        $fabricCons = DB::table('fabric_cons')->get();
        $sizes = DB::table('sizes')->get();
        return view('page.layingPlanning.add', compact('gls', 'buyers', 'styles', 'colors', 'fabricTypes', 'fabricCons', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'gl' => 'required',
        //     'buyer' => 'required',
        //     'style' => 'required',
        //     'color' => 'required',
        //     'fabric_type' => 'required',
        //     'laying_planning_date' => 'required',
        //     'laying_planning_size' => 'required',
        //     'laying_planning_qty' => 'required',
        // ]);

        $layingData = [
            'gl_id' => $request->gl,
            'style_id' => $request->style,
            'buyer_id' => $request->buyer,
            'color_id' => $request->color,
            'quantity' => $request->order_qty,
            'delivery_date' => $request->delivery_date,
            'plan_date' => $request->plan_date,
            'fabric_po' => $request->fabric_po,
            'fabric_cons_id' => $request->fabric_cons,
            'fabric_type_id' => $request->fabric_type,
            'fabric_cons_qty' => $request->fabric_cons_qty,
        ];
        
        $insertLayingData = LayingPlanning::create($layingData);

        return redirect()->route('laying-planning.index')
            ->with('success', 'Laying Planning created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LayingPlannings  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function show(LayingPlannings $layingPlannings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LayingPlannings  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function edit(LayingPlannings $layingPlannings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LayingPlannings  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LayingPlannings $layingPlannings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LayingPlannings  $layingPlannings
     * @return \Illuminate\Http\Response
     */
    public function destroy(LayingPlannings $layingPlannings)
    {
        //
    }
}
