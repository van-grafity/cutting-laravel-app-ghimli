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

    //  $data = CuttingOrderRecord::with(['layingPlanningDetail'])->get();
    //     $data = collect(
    //         [
    //             'cuttingOrderRecord' => $data
    //         ]
    //     );
    //     return $this->onSuccess($data, 'Cutting Order Record retrieved successfully.');

    public function signin(Request $request)
    {
        // collect user
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user();
            $token_name = $request->token_name ? $request->token_name : 'android_app'; 
            $token_expired_hours = $request->token_expired_hours ? $request->token_expired_hours : 1; 
            $success['token'] =  $authUser->createToken($token_name, ['*'],  now()->addHours($token_expired_hours))->plainTextToken; 
            $success['name'] =  $authUser->name;
            $success['email'] =  $authUser->email;

            $user = json_encode(array(
                'token' => $success['token'],
                'user' => array_merge($authUser->toArray(), ['role' => Auth::user()->roles()->first()])
                // 'role' => Auth::user()->roles()->first()
            ));
            $user = json_decode($user, true);
            return $this->onSuccess($user, 'User logged in successfully');
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
        $success['token'] =  $user->createToken('android_app')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->onSuccess($success, 'You have successfully registered');
    }

    public function me()
    {
        $user = Auth::user();
        $user = json_encode(array('users' => $user));
        $user = json_decode($user, true);
        return $this->onSuccess($user, 'User fetched successfully');
        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->onSuccess(null, 'User logged out');
    }

    public function index()
    {
        $data = User::all();
        $data = json_encode(array('users' => $data));
        $data = json_decode($data, true);
        return $this->onSuccess($data, 'User fetched successfully');
    }
   
}
