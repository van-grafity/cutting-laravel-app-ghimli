<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClothRoll;

class ClothRollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ClothRoll::all();
        return view('page.clothroll.index', compact('data'));
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
            'po_id' => 'required',
            'fabric_type' => 'required',
            'color' => 'required',
            'roll_no' => 'required',
            'width' => 'required',
            'length' => 'required',
            'weight' => 'required',
            'batch_no' => 'required',
        ]);

        $data = new ClothRoll;
        $data->po_id = $request->po_id;
        $data->fabric_type = $request->fabric_type;
        $data->color = $request->color;
        $data->roll_no = $request->roll_no;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->weight = $request->weight;
        $data->batch_no = $request->batch_no;
        $data->save();
        return redirect('/clothroll')->with('status', 'Data Cloth Roll Berhasil Ditambahkan!');
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
            $data = ClothRoll::where('id', $id)->first();
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
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
            'po_id' => 'required',
            'fabric_type' => 'required',
            'color' => 'required',
            'roll_no' => 'required',
            'width' => 'required',
            'length' => 'required',
            'weight' => 'required',
            'batch_no' => 'required',
        ]);

        $data = ClothRoll::find($id);
        $data->po_id = $request->po_id;
        $data->fabric_type = $request->fabric_type;
        $data->color = $request->color;
        $data->roll_no = $request->roll_no;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->weight = $request->weight;
        $data->batch_no = $request->batch_no;
        $data->save();
        return redirect('/clothroll')->with('status', 'Data Cloth Roll Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ClothRoll::find($id);
        $data->delete();
        return redirect('/clothroll')->with('status', 'Data Cloth Roll Berhasil Dihapus!');
    }
}
