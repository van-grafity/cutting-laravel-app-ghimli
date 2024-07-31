<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CuttingTable;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class CuttingTablesController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('cutting-table.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Cutting Table',
            'page_title' => 'Cutting Table List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('page.cutting-table.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = CuttingTable::query();

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm mt-1" onclick="show_modal_edit(\'modal_cutting_table\', '.$row->id.')">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm mt-1" onclick="show_modal_delete('.$row->id.')">Delete</a>
                ';

                return $action_button;
            })
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $cutting_table = CuttingTable::firstOrCreate([
                'number' => $request->number,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new Cutting Table number ' . $cutting_table->number,
                'data' => [
                    'cutting_table' => $cutting_table,
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cutting_table = CuttingTable::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get Cutting Table number' . $cutting_table->number,
                'data' => [
                    'cutting_table' => $cutting_table,
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $cutting_table = CuttingTable::find($id);
            $cutting_table->number = $request->number;
            $cutting_table->description = $request->description;
            $cutting_table->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated Cutting Table number '. $cutting_table->number,
                'data' => [
                    'cutting_table' => $cutting_table
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cutting_table = CuttingTable::find($id);
            $cutting_table->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $cutting_table,
                'message'=> 'Cutting Table Number '.$cutting_table->number.' successfully Deleted!',
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }
}
