<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;



use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PersonalAccessTokensController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('token.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Personal Access Token',
            'page_title' => 'Token List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('page.token.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable(Request $request)
    {
        $query = PersonalAccessToken::with('tokenable');

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = '
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm mt-1" onclick="show_modal_delete('.$row->id.')">Delete</a>
                ';

                return $action_button;
            })
            ->addColumn('user', function($row){
                $user = $row->tokenable ?  $row->tokenable->name ?? 'Name not Available' : 'User Not Found';
                return $user;
            })
            ->addColumn('last_used_at', function($row){
                $readable_datetime = '-';
                if($row->last_used_at) {
                    $readable_datetime = $row->last_used_at->format('d F Y, H:i');
                }
                return $readable_datetime;
            })
            ->addColumn('expires_at', function($row){
                $readable_datetime = '*';
                if($row->expires_at) {
                    $readable_datetime = $row->expires_at->format('d F Y, H:i');
                }
                return $readable_datetime;
            })
            ->addColumn('created_at', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $readable_datetime = $readable_datetime->format('d F Y, H:i');
                return $readable_datetime;
            })
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            $user = User::find($request->user);
            $token =  $user->createToken($request->token_name, ['*'],  now()->addHours($request->expires_in))->plainTextToken; 

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully Create Token for User '. $user->name,
                'data' => [
                    'token' => $token,
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
            $token = PersonalAccessToken::find($id);
            $token->delete();
            $data_return = [
                'status' => 'success',
                'message'=> 'Token '.$token->name.' successfully Deleted!',
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
     * Revoke token by user and token name (optional).
     */
    public function revoke_token(Request $request)
    {
        try {
            $count_token = 0;

            $user_revoke = $request->input('user_revoke');
            $token_name_revoke = $request->input('token_name_revoke');

            if ($user_revoke) {
                $user = User::find($user_revoke);

                if ($user) {
                    $query = $user->tokens();

                    if ($token_name_revoke) {
                        $query->where('name', $token_name_revoke);
                    }

                    $count_token = $query->delete();
                }
            }

            $data_return = [
                'status' => 'success',
                'message'=> 'Successfully revoke '. $count_token .' Token',
                'data' => $request->has('user_revoke'),
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
