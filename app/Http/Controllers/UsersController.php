<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('page.user.index', compact('users','roles'));
    }

    public function dataUser()
    {
        $query = User::get();
            return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="edit_user('.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="delete_user('.$data->id.')">Delete</a>';
            })
            ->addColumn('role', function($data){
                return $data->roles->isNotEmpty() ? $data->roles[0]->name : 'Not Assigned';
            })
            ->make(true);
    }

    public function get_user_list()
    {
        $get_user = User::all();
        return response()->json($get_user,200);
    }

    public function show($id){
        try {
            $data = User::with('roles')->find($id);
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
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $check_duplicate_code = User::where('name', $request->name)->first();
            if($check_duplicate_code){
                return back()->withInput();
            }
            $user = User::firstOrCreate([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            $user->save();
            $user->assignRole($request->role);

            return redirect('/user-management')->with('status', 'Data User Berhasil Ditambahkan!');
        
        } catch (\Exception $ex){
            $this->error($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            $date_return = [
                'status' => 'success',
                'data'=> $user,
                'message'=> 'Data User berhasil di hapus',
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
            'email' => 'required',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $user->syncRoles($request->role);
        
        return redirect('/user-management')->with('status', 'Data User Berhasil Diubah!');
    }

}
