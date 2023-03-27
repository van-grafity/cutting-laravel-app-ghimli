<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gl;
use App\Models\Buyer;

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
        $data = Gl::find($id);
        try {
            $data = Gl::find($id);
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
            'gl_number' => 'required',
            'buyer_id' => 'required',
        ]);

        $check_duplicate_gl_number = Gl::where('gl_number', $request->gl_number)->first();
        if($check_duplicate_gl_number){
            return back()->withInput();
        }
        $gl = Gl::firstOrCreate([
            'gl_number' => $request->gl_number,
            'season' => $request->season,
            'size_order' => $request->size_order,
            'buyer_id' => $request->buyer_id,
        ]);
        $gl->save();

        return redirect('/gl')->with('status', 'Data Gl Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        try {
            $user = Gl::find($id);
            $user->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $user,
                'message'=> 'Data GL berhasil di hapus',
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

        $data = Gl::find($id);
        $data->gl_number = $request->gl_number;
        $data->season = $request->season;
        $data->size_order = $request->size_order;
        $data->buyer_id = $request->buyer_id;
        $data->save();

        return redirect('/gl')->with('status', 'Data Gl Berhasil Diubah!');
    }
}
