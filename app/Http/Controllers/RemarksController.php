<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remark;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RemarksController extends Controller
{
    public function index()
    {
        $remarks = Remark::all();
        return view('page.remark.index', compact('remarks'));
    }

    public function dataRemark()
    {
        $query = Remark::get();
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_remark('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_remark('.$data->id.')">Delete</a>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        // $rules = [
        //     'name' => 'required',
        // ];

        // $validator = Validator::make($request->all(), $rules, $messages = [
        //     'required' => 'The :attribute field is required.',
        // ]);

        // if ($validator->fails()) {
        //     return redirect('/remark')
        //                 ->withErrors($validator)
        //                 ->withInput();
        // }

        $request->validate([
            'name' => 'required',
        ]);

        $remark = Remark::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $remark->save();

        return redirect('/remark')->with('status', 'Remark Successfully Added!');
    }

    public function show($id){
        $data = Remark::find($id);
        try {
            $data = Remark::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $remark = Remark::find($id);
            $remark->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $remark,
                'message'=> 'Remark Deleted!',
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

        $remark = Remark::find($id);
        $remark->name = $request->name;
        $remark->description = $request->description;
        $remark->save();

        return redirect('/remark')->with('status', 'Remark Successfully Updated!');
    }

}
