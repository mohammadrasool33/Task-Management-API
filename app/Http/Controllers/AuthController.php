<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request){
        $credentials = $request->only('email', 'password');
        if(!Auth::attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        else{
            $user=Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;
            return response()->json(['token'=>$token],200);
        }
    }
    public function register(RegisterRequest $request){
        $validate=$request->validated();
        $user=User::create($validate);
        $token=$user->createToken($user->name)->plainTextToken;
        return response()->json(['token'=>$token],200);
    }
    public function logout(){
        Auth::logout();
        request()->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out'],200);
    }
}
