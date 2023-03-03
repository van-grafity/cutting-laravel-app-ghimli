<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricCons;

class FabricConssController extends Controller
{
    public function index()
    {
        $fabricConss = FabricCons::all();
        return view('page.fabric-cons.index', compact('fabricConss'));
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

        return redirect('/fabric-cons')->with('status', 'Data Fabric Consumption Berhasil Ditambahkan!');
    }

    public function destroy($id)
    {
        $fabricCons = FabricCons::find($id);
        $fabricCons->delete();
        // return redirect('/buyer')->with('status', 'Data Buyer Berhasil Dihapus!');
        return response()->json(['status' => 'Data FabricCons Berhasil Dihapus!']);
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

        return redirect('/fabric-cons')->with('status', 'Data Fabric Consumption Berhasil Diubah!');
    }

}
