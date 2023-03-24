<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;

use Yajra\Datatables\Datatables;

class BuyerController extends Controller
{
    
    public function index()
    {
        $buyers = Buyer::all();
        return view('page.buyer.index', compact('buyers'));
    }

    public function dataBuyer()
    {
        $query = Buyer::get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_buyer('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_buyer('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function show($id){
        try {
            $data = Buyer::find($id);
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
            'name' => 'required',
            'address' => 'required',
            'code' => 'required',
        ]);

        $check_duplicate_code = Buyer::where('code', $request->code)->first();
        if($check_duplicate_code){
            return back()->withInput();
        }
        $buyer = Buyer::firstOrCreate([
            'name' => $request->name,
            'address' => $request->address,
            'shipment_address' => $request->shipment_address,
            'code' => $request->code,
        ]);
        $buyer->save();

        return redirect('/buyer')->with('status', 'Data Buyer Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        try {
            $buyer = Buyer::find($id);
            $buyer->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $buyer,
                'message'=> 'Data Buyer berhasil di hapus',
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
            'name' => 'required',
            'address' => 'required',
            'code' => 'required',
        ]);

        $data = Buyer::find($id);
        $data->name = $request->name;
        $data->address = $request->address;
        $data->shipment_address = $request->shipment_address;
        $data->code = $request->code;
        $data->save();

        return redirect('/buyer')->with('status', 'Data Buyer Berhasil Diubah!');
    }
}
