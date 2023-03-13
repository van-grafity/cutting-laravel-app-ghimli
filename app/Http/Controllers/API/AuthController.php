<?php

namespace App\Http\Controllers\API;

use App\Http\Traits\ApiHelpers;
use App\Http\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends BaseController
{
    use ApiHelpers, ApiResponser;
    
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;
   
            return $this->onSuccess($success, 'Login successfully');
        } 
        else{
            return $this->onError(401, 'Unauthorised');
        } 
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->onError(401, $validator->errors());     
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->onSuccess($success, 'User created successfully');
    }

    public function me()
    {
        return $this->onSuccess(auth()->user(), 'User fetched successfully');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->onSuccess(null, 'User logged out');
        
        // auth()->user()->tokens()->delete();

        // return [
        //     'message' => 'Tokens Revoked'
        // ];
    }

    public function index()
    {
        $users = User::all();
        return $this->onSuccess($users, 'Users fetched successfully');
    }
   
}
