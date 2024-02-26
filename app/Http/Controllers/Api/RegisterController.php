<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
//here as BaseController used as alias and register controller extends base controller
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;

class RegisterController extends BaseController
{


    // function for registration
    public function register(Request $request){

        $validator = Validator::make($request->all(), [

            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password',
        ]);

        if($validator->fails())
        {
            return $this->sendError('validation Error', $validator->errors());
        }
        $password = bcrypt($request->password);

        $user = User::create([
            'name'=> $request->name,
            'email'=>$request->email,
            'password'=>$password,


        ]);

        $success['token'] = $user->createToken('RestApi')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'user registerd successful');
    }



    //  function for login
    public function login(Request $request){
        $validator = Validator::make($request->all(), [

            'email'=>'required|email|max:255',
            'password'=>'required|min:6'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user= Auth::user();
            $success['token']= $user->createToken('RestApi')->plainTextToken;
            $success['name']= $user->name;

            return $this->sendResponse ($success,'user logged in successfully');

        }
        else{

            return $this->sendError('Unauthorized', ['error'=>'Unrauthorized']);
        }
    }


    //logout function

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([],'User logged out');
    }

}
