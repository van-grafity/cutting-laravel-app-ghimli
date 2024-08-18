<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricUsage;

use Yajra\Datatables\Datatables;

class FabricUsagesController extends Controller
{
    public function index()
    {
        $fabricUsages = FabricUsage::all();
        return view('page.fabric-usage.index', compact('fabricUsages'));
    }

    public function dataFabricUsage()
    {
        $query = FabricUsage::get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_fabricUsage('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_fabricUsage('.$data->id.')">Delete</a>';
            })
            ->addColumn('qty_consumed', function($data){
                return $data->quantity_consumed. " ydz";
            })
            ->make(true);
    }

    public function show($id){
        $data = FabricUsage::find($id);
        try {
            $data = FabricUsage::find($id);
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
            'portion' => 'required',
            'content' => 'required',
            'type' => 'required',
            'type_description' => 'required',
            'quantity_consumed' => 'required',
        ]);

        $check_duplicate_type = FabricUsage::where('type', $request->type)->first();
        if($check_duplicate_type){
            return back()->with('error', 'Type Name has been exist, Please input another Type Name.');
        }

        $fabricUsage = FabricUsage::firstOrCreate([
            'portion' => $request->portion,
            'content' => $request->content,
            'type' => $request->type,
            'type_description' => $request->type_description,
            'quantity_consumed' => $request->quantity_consumed,
        ]);
        $fabricUsage->save();

        return redirect('/fabric-usage')->with('success', 'Fabric Usage '.$fabricUsage->name.' Successfully Added!');
    }

    public function destroy($id)
    {
        try {
            $fabricUsage = FabricUsage::find($id);
            $fabricUsage->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $fabricUsage,
                'message'=> 'Fabric Usage '.$fabricUsage->name.' Successfully Deleted',
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
            'portion' => 'required',
            'content' => 'required',
            'type' => 'required',
            'type_description' => 'required',
            'quantity_consumed' => 'required',
        ]);

        $check_duplicate_type = FabricUsage::where('type', $request->type)->where('id','!=', $id)->first();
        if($check_duplicate_type){
            return back()->with('error', 'Type Name has been exist, Please input another Type Name.');
        }

        $fabricUsage = FabricUsage::find($id);
        $fabricUsage->portion = $request->portion;
        $fabricUsage->content = $request->content;
        $fabricUsage->type = $request->type;
        $fabricUsage->type_description = $request->type_description;
        $fabricUsage->quantity_consumed = $request->quantity_consumed;
        $fabricUsage->save();

        return redirect('/fabric-usage')->with('success', 'Fabric Usage '.$fabricUsage->name.' Successfully Updated!');
    }

}
