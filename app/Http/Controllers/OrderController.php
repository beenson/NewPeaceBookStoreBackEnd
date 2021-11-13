<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->only(['userOrders']);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/orders",
     *      summary="訂單紀錄",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      {
     *                          "id": 1,
     *                          "user_id": 1,
     *                          "status": 1,
     *                          "total_price": 100,
     *                          "created_at": null,
     *                          "updated_at": null
     *                      }
     *                  }
     *              }
     *          )
     *      })
     *  )
     */
    public function authOrders() {
        $user = auth()->user();
        return response()->json(['status' => 1, 'data' => $user->getOrders()]);
    }
    /**
     *  @OA\Get(
     *      path="/api/user/{id}/orders",
     *      summary="會員訂單紀錄",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      {
     *                          "id": 1,
     *                          "user_id": 1,
     *                          "status": 1,
     *                          "total_price": 100,
     *                          "created_at": null,
     *                          "updated_at": null
     *                      }
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
    public function userOrders() {
        $id = request()->route('id');
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $user->getOrders()]);
    }
}
