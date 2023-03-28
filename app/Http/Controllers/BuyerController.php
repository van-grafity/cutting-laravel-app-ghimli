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
            return back()->with('error', 'Buyer Code has been exist, Please input another code');;
        }
        $buyer = Buyer::create([
            'name' => $request->name,
            'address' => $request->address,
            'shipment_address' => $request->shipment_address,
            'code' => $request->code,
        ]);
        $buyer->save();

        return redirect('/buyer')->with('success', 'Buyer '.$buyer->name.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $buyer = Buyer::find($id);
            $buyer->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $buyer,
                'message'=> 'Buyer '.$buyer->name.' Successfully Deleted',
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

        $buyer = Buyer::find($id);
        $buyer->name = $request->name;
        $buyer->address = $request->address;
        $buyer->shipment_address = $request->shipment_address;
        $buyer->code = $request->code;
        $buyer->save();

        return redirect('/buyer')->with('success', 'Buyer '.$buyer->name.' Successfully Updated!');
    }
}
