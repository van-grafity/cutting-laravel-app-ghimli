<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CuttingOrderRecord;

// DB
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // labels
        $labels = [];
        $data = [];
        $cutting_orders = CuttingOrderRecord::with(['statusLayer', 'statusCut'])
            ->select(DB::raw('count(*) as total, MONTH(created_at) as month'), 'id_status_layer', 'id_status_cut')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();
        foreach ($cutting_orders as $cutting_order) {
            $labels[] = date("F", mktime(0, 0, 0, $cutting_order->month, 10));
            $data[] = $cutting_order->total;
        }
        $countStatusLayerCompleted = CuttingOrderRecord::with(['statusLayer', 'statusCut'])->whereHas('statusLayer', function ($query) {
            $query->where('name', 'completed');
        })->count();
        $countStatusLayerOverLayer = CuttingOrderRecord::with(['statusLayer', 'statusCut'])->whereHas('statusLayer', function ($query) {
            $query->where('name', 'over layer');
        })->count();
        $countStatusLayerNotCompleted = CuttingOrderRecord::with(['statusLayer', 'statusCut'])->whereHas('statusLayer', function ($query) {
            $query->where('name', 'not completed');
        })->count();

        $countStatusCutCompleted = CuttingOrderRecord::with(['statusLayer', 'statusCut'])->whereHas('statusCut', function ($query) {
            $query->where('name', 'sudah');
        })->count();
        $countStatusCutNotCompleted = CuttingOrderRecord::with(['statusLayer', 'statusCut'])->whereHas('statusCut', function ($query) {
            $query->where('name', 'belum');
        })->count();

        return view('home', compact('labels', 'data', 'countStatusLayerCompleted', 'countStatusLayerOverLayer', 'countStatusLayerNotCompleted', 'countStatusCutCompleted', 'countStatusCutNotCompleted'));
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
