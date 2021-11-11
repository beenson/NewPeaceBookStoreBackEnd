<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('login');
    }

    public function login()
    {
        $email = request()->get('email');
        $password = request()->get('password');
        $user = User::where('email', $email)->where('password', $password)->first();
        if (!$user) {
            return response()->json(['status' => 1, 'message' => 'invalid credentials'], 401);
        }
        $jwt = JWTAuth::fromUser($user);
        return response()->json(['status' => 0, 'token' => $jwt]);
    }

    public function register()
    {
        $email = request()->get('email');
        $password = request()->get('password');
        $name = request()->get('name');


    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 0]);
    }
}
