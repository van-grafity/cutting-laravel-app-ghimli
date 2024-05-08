<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class FetchSelectController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a fetch select option.
     */
    public function index()
    {
        try {
            $fetch_list = [
                'fetch-select.user' => [
                    'title' => 'Get User for select2',
                    'description' => 'Ambil semua user yang ada untuk list di select2 form',
                    'url' => route('fetch-select.user'),
                ],
            ];
            $data_return = [
                'status' => 'success',
                'data'=> $fetch_list,
                'message'=> 'Fetch List',
            ];
            return response()->json($data_return);

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }

    public function select_user()
    {
        try {
            $id = request()->id;
            if($id) {
                $user = User::
                    select('id','users.name as text')
                    ->orderBy('users.name','ASC')
                    ->find($id);

                if($user) {
                    $data_return = [
                        'status' => 'success',
                        'data'=> [
                            'items' => $user,
                        ],
                        'message'=> 'User Found',
                    ];
                } else {
                    $data_return = [
                        'status' => 'error',
                        'data'=> [],
                        'message'=> 'User Not Found',
                    ];
                }
                return response()->json($data_return);
                
            } else {
                $search_query = request()->search;
                $user_list = User::
                    when($search_query, static function ($query, $search_query) {
                        $query->where('name','LIKE','%'.$search_query.'%');

                    })
                    ->orderBy('users.name','ASC')
                    ->select('id','users.name as text')
                    ->get();
                
                $data_return = [
                    'status' => 'success',
                    'data'=> [
                        'items' => $user_list,
                    ],
                    'message'=> 'User List',
                ];
                return response()->json($data_return);
            }

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }

    public function select_user_multiple()
    {
        try {
            $users_id = request()->id;
            $users_id = explode(',', $users_id);
            $user = User::
                select('id','users.name as text')
                ->orderBy('users.name','ASC')
                ->whereIn('id',$users_id)
                ->get();

            if($user) {
                $data_return = [
                    'status' => 'success',
                    'data'=> [
                        'items' => $user,
                    ],
                    'message'=> 'Users Found',
                ];
            } else {
                $data_return = [
                    'status' => 'error',
                    'data'=> [],
                    'message'=> 'Users Not Found',
                ];
            }
            return response()->json($data_return);
                

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }
}
