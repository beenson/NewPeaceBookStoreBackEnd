<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    public function login()
    {
        $email = request()->get('email');
        $password = request()->get('password');
        // TODO: password encrypt
        $user = User::where('email', $email)->where('password', $password)->first();
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'invalid credentials'], 401);
        }
        $jwt = JWTAuth::fromUser($user);
        return response()->json(['status' => 1, 'token' => $jwt]);
    }

    public function register()
    {
        if (!request()->has('email') || !request()->has('password') || !request()->has('name') || !request()->has('sid')) {
            return response()->json(['status' => 0, 'message' => 'bad request'], 400);
        }
        $email = request()->get('email');
        $password = request()->get('password');
        $name = request()->get('name');
        $sid = request()->get('sid');
        if (!User::checkAvailible($email, $sid)) {
            return response()->json(['status' => 0, 'message' => 'duplicate user'], 409);
        }
        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = $password;
        $user->sid = $sid;
        $user->save();
        $jwt = JWTAuth::fromUser($user);
        return response()->json(['status' => 1, 'token' => $jwt]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['status' => 1]);
    }
}
