<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class SizesController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('page.size.index', compact('sizes'));
    }

    public function dataSize()
    {
        $query = DB::table('sizes')->get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_size('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_size('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function get_size_list()
    {
        $get_size = Size::all();
        return response()->json($get_size,200);
    }

    public function show($id){
        try {
            $data = Size::find($id);
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
            'size' => 'required',
        ]);

        $check_duplicate_code = Size::where('size', $request->size)->first();
        if($check_duplicate_code){
            return back()->withInput();
        }
        $size = Size::firstOrCreate([
            'size' => $request->size,
        ]);
        $size->save();

        return redirect('/size')->with('status', 'Data Size Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        try {
            $size = Size::find($id);
            $size->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $size,
                'message'=> 'Data Size berhasil di hapus',
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
            'size' => 'required',
        ]);

        $size = Size::find($id);
        $size->size = $request->size;
        $size->save();

        return redirect('/size')->with('status', 'Data Size Berhasil Diubah!');
    }

}
