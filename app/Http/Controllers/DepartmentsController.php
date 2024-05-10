<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('department.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Department',
            'page_title' => 'Department List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('page.department.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Department::query();

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm mt-1" onclick="show_modal_edit(\'modal_department\', '.$row->id.')">Edit</a>
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
            $department = Department::firstOrCreate([
                'department' => $request->department,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new department (' . $department->department . ')',
                'data' => [
                    'department' => $department,
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
            $department = Department::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get department (' . $department->department . ')',
                'data' => [
                    'department' => $department,
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
            $department = Department::find($id);
            $department->department = $request->department;
            $department->description = $request->description;
            $department->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated department ('. $department->department .')',
                'data' => $department
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
            $department = Department::find($id);
            $department->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $department,
                'message'=> 'Department '.$department->department.' successfully Deleted!',
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
