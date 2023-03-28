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
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_color('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_color('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function get_color_list()
    {
        $get_color = Color::all();
        return response()->json($get_color,200);
    }

    public function show($id){
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

        return redirect('/color')->with('success', 'Color '.$color->color.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $color = Color::find($id);
            $color->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $color,
                'message'=> 'Color '.$color->color.' successfully Deleted!',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
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

        return redirect('/color')->with('success', 'Color '.$color->color.' Successfully Updated!');
    }

}
