<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groups;
use App\Models\CuttingGroup;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class CuttingGroupsController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('group.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Cutting Group',
            'page_title' => 'Cutting Group List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('page.cutting-group.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = CuttingGroup::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_group\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $group = CuttingGroup::firstOrCreate([
                'group' => $request->group,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new group (' . $group->group . ')',
                'data' => [
                    'group' => $group,
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
            $group = CuttingGroup::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get group (' . $group->group . ')',
                'data' => [
                    'group' => $group,
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
            $group = CuttingGroup::find($id);
            $group->group = $request->group;
            $group->description = $request->description;
            $group->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated group ('. $group->group .')',
                'data' => $group
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
            $group = CuttingGroup::find($id);
            $group->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $group,
                'message'=> 'Group '.$group->group.' successfully Deleted!',
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

    // ## Untuk proses migratisi data. ini di perlu di awal awal aja. kedepannya akan di hapus
    // todo : delete this function next. beserta tombol dan fungsi yang ada di cutting group index
    public function sync_old_data() {
        try {
            $groups = Groups::all();
            $cutting_group_data = [];
            foreach ($groups as $key => $group) {
                $cutting_group_data[] = [
                    'id' => $group->id,
                    'group' => $group->group_name,
                    'description' => $group->group_description,
                    'created_at' => $group->created_at,
                    'updated_at' => $group->updated_at,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
            }
            CuttingGroup::truncate();
            CuttingGroup::insert($cutting_group_data);
            
            $data_return = [
                'status' => 'success',
                'data'=> $cutting_group_data,
                'message' => 'Successfully synchronized ' . $groups->count() . ' groups.',
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
