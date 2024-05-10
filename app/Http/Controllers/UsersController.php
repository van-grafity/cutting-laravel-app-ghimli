<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;


// !! nanti model ini di hapus
use App\Models\Groups;



use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        $departments = Department::all();
        return view('page.user.index', compact('users','roles','departments'));
    }


    /**
     * Show Datatable Data.
     */
    public function dtable(Request $request)
    {
        if(auth()->user()->can('developer-menu')){
            
            $query = User::withTrashed();

        } else {
            
            $query = User::withTrashed()->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['developer','admin']);
            });
        }
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "";
                if(!$row->deleted_at){
                    $action_button .= "
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-primary' onclick='show_modal_edit(\"modal_user\", $row->id)' >Edit</a>
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-info'  onclick='show_modal_reset_password($row->id)'>Reset Password</a>    
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-danger'  onclick='show_modal_delete($row->id)'>Delete</a>
                    ";
                } else {
                    $action_button .= "
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 bg-orange' onclick='show_modal_restore($row->id)' >Restore</a>
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-danger' onclick='show_modal_delete_permanent($row->id)'>Delete Permanently  <i class='fas fa-exclamation-triangle'></i></a>
                    ";
                }
                return $action_button;
                
            })
            ->addColumn('department', function($row){
                return $row->department ? $row->department->department : '-';
            })
            ->addColumn('role', function($row){
                $roles = $row->getRoleTitles();
                
                $result = implode(' | ', $roles->toArray());
                $cleanedString = preg_replace('/[ |\|]/', '', $result);
                $result = (strlen($cleanedString) > 0) ? $result : '-';
                return $result;
            })
            ->filter(function ($query) {
                if (request()->has('data_status')) {
                    if (request('data_status') == 1) {
                        $query->where('deleted_at', null);
                    }
                    if (request('data_status') == 2) {
                        $query->where('deleted_at', '!=', null);
                    }
                }
            }, true)
            ->addColumn('created_date', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $readable_datetime = $readable_datetime->format('d F Y, H:i');
                return $readable_datetime;
            })
            ->toJson();
    }

    public function show($id){
        try {
            $user = User::find($id);
            $user->role = $user->roles[0]->name;

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get user ' . $user->user,
                'data' => [
                    'user' => $user,
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
            ]);

            $check_duplicate_code = User::where('email', $request->email)->first();
            if($check_duplicate_code){
                return back()->with('error', 'Email already exists, please choose another');
            }

            $user = User::firstOrCreate([
                'name' => $request->name,
                'email' => $request->email,
                'department_id' => $request->department,
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            $user->save();
            if($request->role != null){
                $user->assignRole($request->role);
            }

            return redirect('/user-management')->with('success', 'User '.$user->name.' Successfully Added!');
            
        } catch (\Throwable $th){
            return redirect('/user-management')->with('error', $th->getMessage());
        }

        try {
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    public function destroy($id)
    {
        try {
            $user = User::withTrashed()->find($id);
            $data_return = [
                'status' => 'success',
                'data'=> $user,
            ];

            if($user->deleted_at) {
                $user->forceDelete();
                $data_return['message'] = 'Successfully Permanetly Delete user ' . $user->name . ' !';
            } else {
                $user->delete();
                $data_return['message'] = 'Successfully Delete user ' . $user->name . ' !';
            }

            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    public function restore(string $id)
    {
        try {
            User::withTrashed()
                ->where('id', $id)
                ->restore();

            $user = User::find($id);
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully Restore User ' . $user->name,
                'data' => $user
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department;
        $user->save();
        
        // update role
        $user->syncRoles($request->role);
        
        return redirect('/user-management')->with('success', 'User '.$user->name.' Successfully Updated!');
    }

    public function reset_password(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->password = Hash::make("123456789");
            $user->save();
            $date_return = [
                    'status' => 'success',
                    'data'=> $user,
                    'message'=> 'Password '.$user->name.' has been Reset',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function profile(Request $request)
    {
        $user = User::with('roles')->find(Auth::user()->id);
        return view('page.user.profile', compact('user'));
    }

    public function profile_change_password(Request $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            if(!Hash::check($request->old_password, $user->password)) {
                $date_return = [
                    'status' => 'failed',
                    'message'=> 'Incorrect old Password!',
                ];
                return response()->json($date_return, 200);
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            $date_return = [
                'status' => 'success',
                'message'=> 'Password Changed Successfully!',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }






    // !! nanti semua tentang group ini di hapus
    public function cutting_group()
    {
        return view('page.user.cutting_group');
    }

    public function dataGroup()
    {
        $query = Groups::all();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="showModalCuttingGroup(false,'.$data->id.')">Edit</a>
                <form action="'.route('delete-group', $data->id).'" method="post">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <input type="hidden" name="_method" value="DELETE">
                    <button href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="confirmDelete(this)">Delete</button>
                </form>';
            })
            ->make(true);
    }

    public function edit_group($id)
    {
        try {
            $data = Groups::find($id);
            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function store_group(Request $request)
    {
        try {
            $request->validate([
                'group_name' => 'required',
                'group_description' => 'required',
            ]);

            $check_duplicate_code = Groups::where('group_name', $request->group_name)->first();
            if($check_duplicate_code){
                return back()->with('error', 'Group Name already exists, please choose another');
            }

            $group = Groups::firstOrCreate([
                'group_name' => $request->group_name,
                'group_description' => $request->group_description,
            ]);
            $group->save();
            
            return redirect('/user-cutting-group')->with('success', 'Group '.$group->group_name.' Successfully Added!');
            
        } catch (\Throwable $th){
            return redirect('/user-cutting-group')->with('error', $th->getMessage());
        }

        try {
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    public function update_group(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required',
            'group_description' => 'required',
        ]);

        $group = Groups::find($id);
        $group->group_name = $request->group_name;
        $group->group_description = $request->group_description;
        $group->save();
        
        return redirect('/user-cutting-group')->with('success', 'Group '.$group->group_name.' Successfully Updated!');
    }

    public function delete_group($id)
    {
        try {
            $group = Groups::find($id);
            $group->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $group,
                'message'=> 'Group '.$group->group_name.' Deleted',
            ];
            return response()->json($date_return, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    // !! ===== batas hapus
}
