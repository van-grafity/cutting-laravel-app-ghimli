<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class ColorsController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('page.color.index', compact('colors'));
    }

    public function dataColor()
    {
        $query = DB::table('colors')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '<a href="/color/'.$data->id.'/edit" class="btn btn-warning btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm" id="delete" data-id="'.$data->id.'">Delete</button>';
            })
            ->make(true);
    }

    public function show($id){
        $data = Color::find($id);
        try {
            $data = Color::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'color' => 'required',
            'color_code' => 'required',
        ]);

        $check_duplicate_code = Color::where('color_code', $request->color_code)->first();
        if($check_duplicate_code){
            return back()->withInput();
        }
        $color = Color::firstOrCreate([
            'color' => $request->color,
            'color_code' => $request->color_code,
        ]);
        $color->save();

        return redirect('/color')->with('status', 'Data Color Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        $color = Color::find($id);
        $color->delete();
        // return redirect('/buyer')->with('status', 'Data Buyer Berhasil Dihapus!');
        return response()->json(['status' => 'Data Color Berhasil Dihapus!']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'color' => 'required',
            'color_code' => 'required',
        ]);

        $color = Color::find($id);
        $color->color = $request->color;
        $color->color_code = $request->color_code;
        $color->save();

        return redirect('/color')->with('status', 'Data Color Berhasil Diubah!');
    }

}
