<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// !! next di hapus sih. masih diperlukan karena untuk migrasi data di awal aja
use App\Models\Groups;
use App\Models\UserGroups;

use App\Models\CuttingGroup;
use App\Models\CuttingGroupUser;

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
        $query = CuttingGroup::query();

        // ## penambahan logika sorting agar mampu sort string as number
        $orderData = request()->input('order');

        //##  Cek apakah ada data order dan memenuhi kondisi yang dibutuhkan
        if (!empty($orderData) && isset($orderData[0]['column'], $orderData[0]['dir'])) {
            $orderIndex = $orderData[0]['column'];
            $dir = $orderData[0]['dir'];

            // ## Pengurutan berdasarkan kolom yang diurutkan, dalam hal ini roll_number berada di index 1
            if ($orderIndex == 1) {
                $query->orderByRaw("CAST(SUBSTRING(cutting_groups.group,7) AS UNSIGNED) $dir");
            }
        }

        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = '
                    <a href="javascript:void(0);" class="btn btn-info btn-sm mt-1" onclick="manage_members('.$row->id.')">Manage Member</a>
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm mt-1" onclick="show_modal_edit(\'modal_group\', '.$row->id.')">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm mt-1" onclick="show_modal_delete('.$row->id.')">Delete</a>
                ';

                return $action_button;
            })
            ->addColumn('members', function($row){
                $names = $row->users->pluck('name')->sort()->map(function($name) {
                    return "<span class='badge bg-navy mt-1 p-2'>$name</span>";
                })->implode(' ');
                return $names;
            })
            ->filterColumn('members', function($query, $keyword) {
                $query->whereHas('users', function($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->toJson();
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

    /**
     * Display Members in the Group.
     */
    public function show_members(string $group_id)
    {
        try {
            $group = CuttingGroup::find($group_id);
            $users = $group->users;

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get users (' . $group->group . ')',
                'data' => [
                    'users' => $users,
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
     * Update Members in the Group.
     */
    public function update_members(Request $request, string $group_id)
    {
        try {
            $members = $request->members;

            $cutting_group = CuttingGroup::find($group_id);
            $delete_exist_data = CuttingGroupUser::where('cutting_group_id',$group_id)->delete();
            $updated_members = [];
            foreach ($members as $key => $user_id) {
                $updated_members[] = CuttingGroupUser::firstOrCreate([
                    'cutting_group_id' => $group_id,
                    'user_id' => $user_id,
                ]);
            }

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully update member for ' . $cutting_group->group,
                'data' => [
                    'updated_members' => $updated_members,
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



    // ## Untuk proses migratisi data. ini di perlu di awal awal aja. kedepannya akan di hapus
    // todo : delete this function next. beserta tombol dan fungsi yang ada di cutting group index
    public function sync_old_data() {
        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            CuttingGroupUser::truncate();
            CuttingGroup::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');


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
            CuttingGroup::insert($cutting_group_data);
            
            $user_groups = UserGroups::all();
            $cutting_group_user_data = [];
            foreach ($user_groups as $key => $user_group) {
                $cutting_group_user_data[] = [
                    'id' => $user_group->id,
                    'user_id' => $user_group->user_id,
                    'cutting_group_id' => $user_group->group_id,
                    'created_at' => $user_group->created_at,
                    'updated_at' => $user_group->updated_at,
                ];
            }

            CuttingGroupUser::insert($cutting_group_user_data);
            
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
