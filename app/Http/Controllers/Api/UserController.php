<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
//use App\Mail\AuthMail;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
 /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            if($user->email_verified_at)
            {
            $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
//            $success['userId'] = $user->id;
            return response()->json($success, $this-> successStatus);
            }else
            {
                 return response()->json(['error'=>'Email Verification Pending'], 401);
            }
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
 
 /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
 }
 $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
//        $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
        $success['name'] =  $user->name;
        $success['email']=$user->email;
        event(new \Illuminate\Auth\Events\Registered($user));

 return response()->json(['success'=>$success], $this-> successStatus); 
    }
        public function updateDetails(Request $request) 
    { 
            $user = Auth::user(); 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => "required|email|unique:users,email,$user->id"
        ]);
        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
 }
        $user->name=$request->name;
        $user->email=$request->email;
        $user->update();

 return response()->json(['success'=>"updated successfully"], $this-> successStatus); 
    }
 
 /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function userDetails() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}
