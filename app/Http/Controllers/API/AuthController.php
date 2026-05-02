<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){
            $valideUser = Validator::make(
                $request->all(),
                [
                    'name'=>'required',
                    'email'=>'required|email|unique:users,email',
                    'password'=>'required'
                ]
            );


        if($valideUser->fails()){
              return response()->json([
                'status'=>false,
                'message'=> 'Validation error',
                'errors'=> $valideUser->errors()->all()
              ],401);
        }    


      $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>$request->password
      ]);

      return response()->json([
                'status'=>true,
                'message'=> 'User created Successfully',
                'user'=> $user
              ],200);


    }

    public function login(Request $request){
         $valideUser = Validator::make(
                $request->all(),
                [
                   
                    'email'=>'required|email',
                    'password'=>'required'
                ]
            );

               if($valideUser->fails()){
              return response()->json([
                'status'=>false,
                'message'=> 'Authentication Fails',
                'errors'=> $valideUser->errors()->all()
              ],404);
        }    


        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
                 $authUser = Auth::user();
        return response()->json([
                'status'=>true,
                'message'=> 'User login Successfully',
               'token'=>  $authUser->createToken("API Token")->plainTextToken,
               'token_type'=>'bearer'
              ],200);
        }else{
             return response()->json([
                'status'=>false,
                'message'=> 'Email & password not matched',
               
              ],401);
        }

    }

    public function logout(Request $request){
         $user = $request->user();
         $user->tokens()->delete();


         return response()->json([
                'status'=>true,
                'message'=> 'User logout Successfully',
              
              ],200);
    }
    
}
