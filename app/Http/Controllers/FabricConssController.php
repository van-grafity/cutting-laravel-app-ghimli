<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricCons;

use Yajra\Datatables\Datatables;

class FabricConssController extends Controller
{
    public function index()
    {
        $fabricConss = FabricCons::all();
        return view('page.fabric-cons.index', compact('fabricConss'));
    }

    public function dataFabricCons()
    {
        $query = FabricCons::get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_fabricCons('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricCons('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function show($id){
        $data = FabricCons::find($id);
        try {
            $data = FabricCons::find($id);
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
            'description' => 'required',
        ]);

        $fabricCons = FabricCons::firstOrCreate([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $fabricCons->save();

        return redirect('/fabric-cons')->with('success', 'Fabric Consumption '.$fabricCons->name.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $fabricCons = FabricCons::find($id);
            $fabricCons->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $fabricCons,
                'message'=> 'Fabric Consumption '.$fabricCons->name.' Successfully Deleted',
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
            'description' => 'required',
        ]);

        $fabricCons = FabricCons::find($id);
        $fabricCons->name = $request->name;
        $fabricCons->description = $request->description;
        $fabricCons->save();

        return redirect('/fabric-cons')->with('success', 'Fabric Consumption '.$fabricCons->name.' Successfully Updated!');
    }

}
