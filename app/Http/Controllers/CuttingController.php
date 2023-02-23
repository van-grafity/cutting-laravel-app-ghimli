<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cutting;

class CuttingController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datasCutting = cutting::all();
        return view('page.cutting.index', compact('datasCutting'));
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
        $request->validate([
            'job_number' => 'required',
            'style_number' => 'required',
            'table_number' => 'required',
            'next_bundling' => 'required',
            'color' => 'required',
            'size' => 'required',
        ]);

        $cutting = new Cutting;
        $cutting->job_number = $request->job_number;
        $cutting->style_number = $request->style_number;
        $cutting->table_number = $request->table_number;
        $cutting->next_bundling = $request->next_bundling;
        $cutting->color = $request->color;
        $cutting->size = $request->size;
        $cutting->save();

        return redirect('/cutting')->with('status', 'Data Cutting Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = cutting::where('id', $id)->first();
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return dd($th);
        }
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
        $request->validate([
            'job_number' => 'required',
            'style_number' => 'required',
            'table_number' => 'required',
            'next_bundling' => 'required',
            'color' => 'required',
            'size' => 'required',
        ]);

        $cutting = cutting::find($id);
        $cutting->job_number = $request->job_number;
        $cutting->style_number = $request->style_number;
        $cutting->table_number = $request->table_number;
        $cutting->next_bundling = $request->next_bundling;
        $cutting->color = $request->color;
        $cutting->size = $request->size;
        $cutting->save();

        return redirect('/cutting')->with('status', 'Data Cutting Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = cutting::findOrFail($id);
        $data->delete();
        return redirect()->route('cutting.index');
    }
}
