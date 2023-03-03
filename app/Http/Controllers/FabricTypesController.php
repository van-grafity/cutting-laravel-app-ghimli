<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricType;

class FabricTypesController extends Controller
{
    public function index()
    {
        $fabricTypes = FabricType::all();
        return view('page.fabric-type.index', compact('fabricTypes'));
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

        return redirect('/fabric-type')->with('status', 'Data Fabric Consumption Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        $fabricType = FabricType::find($id);
        $fabricType->delete();
        // return redirect('/buyer')->with('status', 'Data Buyer Berhasil Dihapus!');
        return response()->json(['status' => 'Data FabricType Berhasil Dihapus!']);
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

        return redirect('/fabric-type')->with('status', 'Data Fabric Consumption Berhasil Diubah!');
    }

}
