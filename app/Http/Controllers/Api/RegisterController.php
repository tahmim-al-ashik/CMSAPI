<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends BaseController
{
    public function register(Request $request){

        $validator=Validator::make($request->all(),[
          
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password',

        
        ]);

        if($validator->fails())
        {
            return $this->sendError('validation Error', $validator->erros());
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
}
