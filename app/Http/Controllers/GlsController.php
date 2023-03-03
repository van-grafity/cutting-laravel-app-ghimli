<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gl;
use App\Models\Buyer;

class GlsController extends Controller
{
    
    public function index()
    {
        $gls = Gl::all();
        $buyers = Buyer::all();
        return view('page.gl.index', compact('gls','buyers'));
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
        $data = Gl::find($id);
        $data->delete();
        // return redirect('/gl')->with('status', 'Data Gl Berhasil Dihapus!');
        return response()->json(['status' => 'Data Gl Berhasil Dihapus!']);
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
