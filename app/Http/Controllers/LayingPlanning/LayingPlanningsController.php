<?php

namespace App\Http\Controllers\LayingPlanning;

use App\Http\Controllers\Controller;
use App\Models\LayingPlannings;
use Illuminate\Http\Request;

class LayingPlanningsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('page.layingPlanning.index');
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
