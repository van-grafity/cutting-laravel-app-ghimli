<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;

class BuyerController extends Controller
{
    
    public function index()
    {
        $buyers = Buyer::all();
        return view('page.buyer.index', compact('buyers'));
    }

    public function show($id){
        $data = Buyer::find($id);
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
        $data = Buyer::find($id);
        $data->delete();
        // return redirect('/buyer')->with('status', 'Data Buyer Berhasil Dihapus!');
        return response()->json(['status' => 'Data Buyer Berhasil Dihapus!']);
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
