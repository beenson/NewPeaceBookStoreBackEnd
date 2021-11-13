<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }
    /**
     *  @OA\Post(
     *      path="/api/auth/login",
     *      summary="會員登入",
     *      tags={"Auth"},
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="信箱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="密碼",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="登入成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "token": "aabbcc"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=401, description="登入失敗",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "invalid credentials"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "bad request"
     *              }
     *          )
     *      })
     *  )
     */
    public function login()
    {
        if (!request()->has('email') || !request()->has('password')) {
            return response()->json(['status' => 0, 'message' => 'bad request'], 400);
        }
        $email = request()->get('email');
        $password = request()->get('password');
        $user = User::where('email', $email)->where('password', hash('sha512', $password))->first();
        if (!$user) {
            return response()->json(['status' => 0, 'message' => 'invalid credentials'], 401);
        }
        $jwt = JWTAuth::fromUser($user);
        return response()->json(['status' => 1, 'token' => $jwt]);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/register",
     *      summary="會員註冊",
     *      tags={"Auth"},
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="信箱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="密碼",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="名稱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sid",
     *          in="query",
     *          description="學號",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="註冊成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "token": "aabbcc"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=409, description="失敗(重複註冊)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "duplicate user"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "bad request"
     *              }
     *          )
     *      })
     *  )
     */
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
        $user->password = hash('sha512', $password);
        $user->sid = $sid;
        $user->save();
        $jwt = JWTAuth::fromUser($user);
        return response()->json(['status' => 1, 'token' => $jwt]);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/",
     *      summary="取得登入資訊",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "id": 3,
     *                  "name": "3",
     *                  "email": "3",
     *                  "role": 0,
     *                  "sid": "3",
     *                  "remember_token": null,
     *                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                  "updated_at": "2021-11-12T15:15:10.000000Z"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=401, description="失敗(Token錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "Unauthorized"
     *              }
     *          )
     *      })
     *  )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/logout",
     *      summary="登出",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      })
     *  )
     */
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['status' => 1]);
    }
}
