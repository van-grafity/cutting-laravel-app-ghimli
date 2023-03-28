<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricType;

use Yajra\Datatables\Datatables;


class FabricTypesController extends Controller
{
    public function index()
    {
        $fabricTypes = FabricType::all();
        return view('page.fabric-type.index', compact('fabricTypes'));
    }

    public function dataFabrictype()
    {
        $query = Fabrictype::get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_fabricType('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricType('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function show($id){
        $data = FabricType::find($id);
        try {
            $data = FabricType::find($id);
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

        $fabricType = FabricType::firstOrCreate([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $fabricType->save();

        return redirect('/fabric-type')->with('success', 'Fabric Type '.$fabricType->name.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $fabricType = FabricType::find($id);
            $fabricType->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $fabricType,
                'message'=> 'Fabric Type '.$fabricType->name.' Successfully Deleted',
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

        $fabricType = FabricType::find($id);
        $fabricType->name = $request->name;
        $fabricType->description = $request->description;
        $fabricType->save();

        return redirect('/fabric-type')->with('success', 'Fabric Type '.$fabricType->name.' Successfully Updated!');
    }

}
