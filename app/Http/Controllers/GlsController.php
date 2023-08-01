<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gl;
use App\Models\Buyer;
use App\Models\Style;
use App\Models\GlCombine;

use Yajra\Datatables\Datatables;

class GlsController extends Controller
{
    
    public function index()
    {
        $gls = Gl::all();
        $buyers = Buyer::all();
        return view('page.gl.index', compact('gls','buyers'));
    }

    public function dataGl()
    {
        $query = Gl::with('buyer')->get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('buyer', function($data) {
                return $data->buyer->name;
            })
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_gl('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_gl('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function show($id){
        $data = Gl::with('buyer', 'style', 'glCombine')->find($id);
        try {
            $date_return = [
                'status' => 'success',
                'data'=> $data,
                'message'=> 'Gl '.$data->gl_number.' successfully get',
            ];
            return response()->json($date_return, 200);
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
            'gl_number' => 'required',
            'buyer_id' => 'required',
        ]);

        $check_duplicate_gl_number = Gl::where('gl_number', $request->gl_number)->first();
        if($check_duplicate_gl_number){
            return back()->with('error', 'GL Number has been exist, Please input another GL Number');
        }
        $gl = Gl::firstOrCreate([
            'gl_number' => $request->gl_number,
            'season' => $request->season,
            'size_order' => $request->size_order,
            'buyer_id' => $request->buyer_id,
        ]);
        $gl->save();

        $styles = $request->style;
        $styles_desc = $request->style_desc;
        foreach ($styles as $key => $style) {
            if($style && $styles_desc[$key]){
                $style = [
                    'style' => $style,
                    'description' => $styles_desc[$key],
                    'gl_id' => $gl->id,
                ];
                $insertStyle = Style::create($style);
            }
        }

        $gl_combine_name_json = $request->gl_combine_name_json;
        if($gl_combine_name_json){
            $gl_combine_name_json = json_decode($gl_combine_name_json);
            foreach ($gl_combine_name_json as $key => $gl_combine_name) {
                $gl_combine = [
                    'id_gl' => $gl->id,
                    'name' => $gl_combine_name,
                ];
                $insertGlCombine = GlCombine::create($gl_combine);
            }
        }
        return redirect('/gl')->with('success', 'Gl '.$gl->gl_number.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $gl = Gl::find($id);
            $gl->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $gl,
                'message'=> 'Gl '.$gl->gl_number.' successfully Deleted',
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
            'gl_number' => 'required',
            'buyer_id' => 'required',
        ]);
        
        $gl = Gl::find($id);
        
        $gl->update([
            'gl_number' => $request->gl_number,
            'season' => $request->season,
            'size_order' => $request->size_order,
            'buyer_id' => $request->buyer_id,
        ]);
        $styles = Style::where('gl_id', $id)->get();
        $styles = $request->style;
        $styles_desc = $request->style_desc;
        $style_ids = $request->style_id;
        foreach ($styles as $key => $style) {
            if($style && $styles_desc[$key]){
                if($style_ids[$key]){
                    $style = [
                        'style' => $style,
                        'description' => $styles_desc[$key],
                        'gl_id' => $gl->id,
                    ];
                    $updateStyle = Style::where('id', $style_ids[$key])->update($style);

                }else{
                    $style = [
                        'style' => $style,
                        'description' => $styles_desc[$key],
                        'gl_id' => $gl->id,
                    ];
                    $insertStyle = Style::create($style);
                }
            }
        }

        $gl_combine_name_json = $request->gl_combine_name_json;
        if($gl_combine_name_json){
            $gl_combine_name_json = json_decode($gl_combine_name_json);
            foreach ($gl_combine_name_json as $key => $gl_combine_name) {
                $gl_combine = [
                    'id_gl' => $gl->id,
                    'name' => $gl_combine_name,
                ];
                $insertGlCombine = GlCombine::create($gl_combine);
            }
        }

        if($request->gl_combine_id){
            $delete = GlCombine::where('id_gl', $gl->id)->whereNotIn('id', $request->gl_combine_id)->delete();
        }else{
            $delete = GlCombine::where('id_gl', $gl->id)->delete();
        }
        
        $deleteStyle = Style::where('gl_id', $gl->id)->whereNotIn('id', $style_ids)->delete();
        return redirect('/gl')->with('success', 'Gl '.$gl->gl_number.' Successfully Updated!');
    }
        

    public function detail(Request $request, $gl_id) {

        $gls = Gl::find($gl_id);
        return view('page.gl.detail', compact('gls'));
    }
}
