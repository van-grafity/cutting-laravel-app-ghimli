<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Style;
use App\Models\Gl;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class StylesController extends Controller
{
    public function index()
    {
        $styles = Style::all();
        $gls = Gl::all();
        return view('page.style.index', compact('styles','gls'));
    }

    public function dataStyle()
    {
        $query = Style::with('gl')->get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('gl_number', function($data){
                return $data->gl->gl_number;
            })
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_style('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_style('.$data->id.')">Delete</a>';
            })
            
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'style' => 'required',
        ]);

        $style = Style::firstOrCreate([
            'style' => $request->style,
            'description' => $request->style_desc,
            'gl_id' => $request->gl,
        ]);
        $style->save();

        return redirect('/style')->with('success', 'Style '.$style->style.' Successfully Added!');
    }

    public function show($id){
        $data = Style::find($id);
        try {
            $data = Style::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $style = Style::find($id);
            $style->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $style,
                'message'=> 'Style '.$style->style.' Successfully Deleted',
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
            'style' => 'required',
            'gl' => 'required',
        ]);

        $style = Style::find($id);
        $style->style = $request->style;
        $style->gl_id = $request->gl;
        $style->description = $request->style_desc;
        $style->save();

        return redirect('/style')->with('success', 'Style '.$style->style.' Successfully Updated!');
    }

}
