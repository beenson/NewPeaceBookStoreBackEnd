<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Tymon\JWTAuth\Facades\JWTAuth;
/** @OA\Info(title="NewPeace BookStore API", version="1.0") */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->except([]);
    }


    /**
     *  @OA\Get(
     *      path="/api/user",
     *      summary="使用者列表",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "name": "1",
     *                      "email": "1",
     *                      "role": 0,
     *                      "sid": "3",
     *                      "remember_token": null,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  },
     *                  {
     *                      "id": 3,
     *                      "name": "3",
     *                      "email": "3",
     *                      "role": 0,
     *                      "sid": "3",
     *                      "remember_token": null,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function users() {
        return response()->json(['status' => 1, 'data' => User::get()]);
    }

    /**
     *  @OA\Get(
     *      path="/api/user/{id}",
     *      summary="使用者資訊",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "name": "1",
     *                      "email": "1",
     *                      "role": 0,
     *                      "sid": "3",
     *                      "remember_token": null,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "user not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function user() {
        $id = request()->route('id');
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $user]);
    }

    /**
     *  @OA\Post(
     *      path="/api/user",
     *      summary="添加會員",
     *      tags={"User"},
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
     *      @OA\Response(response=200, description="新增成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "name": "1",
     *                      "email": "1",
     *                      "role": 0,
     *                      "sid": "3",
     *                      "remember_token": null,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=409, description="失敗(使用者重複)",content={
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
    public function createUser() {
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
        return response()->json(['status' => 1, 'data' => $user]);
    }
    /**
     *  @OA\Post(
     *      path="/api/user/{id}",
     *      summary="修改會員資訊",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="密碼",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="名稱",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="role",
     *          in="query",
     *          description="權限",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="修改成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "name": "1",
     *                      "email": "1",
     *                      "role": 0,
     *                      "sid": "3",
     *                      "remember_token": null,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(會員不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "user not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function updateUser() {
        $id = request()->route('id');
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        if (request()->has('password')) {
            $user->password = hash('sha512', request()->get('password'));
        }
        if (request()->has('name')) {
            $user->name = request()->get('name');
        }
        if (request()->has('role')) {
            $user->role = request()->get('role');
        }
        $user->save();
        return response()->json(['status' => 1, 'data' => $user]);
    }
}
