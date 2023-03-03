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
        $gl = DB::table('gls')->get();
        $buyer = DB::table('buyers')->get();
        $style = DB::table('styles')->get();
        $color = DB::table('colors')->get();
        $fabricType = DB::table('fabric_types')->get();
        $size = DB::table('sizes')->get();
        return view('page.layingPlanning.add', compact('gl', 'buyer', 'style', 'color', 'fabricType', 'size'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'gl' => 'required',
            'buyer' => 'required',
            'style' => 'required',
            'color' => 'required',
            'fabric_type' => 'required',
            'laying_planning_date' => 'required',
            'laying_planning_size' => 'required',
            'laying_planning_qty' => 'required',
            'laying_planning_remark' => 'required',
        ]);

        LayingPlanning::create($request->all());

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
