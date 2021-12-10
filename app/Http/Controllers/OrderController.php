<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->only(['userOrders', 'userOrder']);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/orders",
     *      summary="訂單紀錄",
     *      tags={"Order"},
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
     *      path="/api/auth/order/{oid}",
     *      summary="指定訂單紀錄",
     *      tags={"Order"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "status": 1,
     *                      "total_price": 100,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(訂單不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "order not found"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(訂單不屬於此會員)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "order not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function authOrder() {
        $user = auth()->user();
        $oid = request()->route('id');
        $order = Order::find($oid);
        if ($order === null) {
            return response()->json(['status' => 0, 'message' => 'order not found'], 404);
        }
        if ($order->user_id !== $user->id) {
            return response()->json(['status' => 0, 'message' => 'order not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $order]);
    }
    /**
     *  @OA\Get(
     *      path="/api/user/{id}/orders",
     *      summary="會員訂單紀錄",
     *      tags={"Order"},
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

    /**
     *  @OA\Get(
     *      path="/api/user/{id}/order/{oid}",
     *      summary="會員指定訂單紀錄",
     *      tags={"Order"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "status": 1,
     *                      "total_price": 100,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(訂單不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "order not found"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=403, description="失敗(訂單不屬於此會員)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "order not belongs this user"
     *              }
     *          )
     *      })
     *  )
     */
    public function userOrder() {
        $uid = request()->route('id');
        $oid = request()->route('oid');
        $order = Order::find($oid);
        if ($order === null) {
            return response()->json(['status' => 0, 'message' => 'order not found'], 404);
        }
        if ($order->user_id !== $uid) {
            return response()->json(['status' => 0, 'message' => 'order not belongs this user'], 401);
        }
        return response()->json(['status' => 1, 'data' => $order]);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/marchant/manage",
     *      summary="商家取得訂單",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "todo": {{
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "status": 1,
     *                      "total_price": 100,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  }},
     *                  "done": {{
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "status": 1,
     *                      "total_price": 100,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getAuthMerchantOrders() {
        $user = auth()->user();
        $todo_orders = Order::getMerchantOrders($user->id);
        $done_orders = Order::getMerchantOrders($user->id, false);
        return response()->json(['status' => 1, 'todo' => $todo_orders, 'done' => $done_orders]);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/marchant/manage/{oid}/payment/complete",
     *      summary="商家手動標示訂單已付款",
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
    public function completeMerchantOrderPayment() {
        $user = auth()->user();
        $oid = request()->route('oid');
        $order = Order::find($oid);
        if ($order === null) {
            return response()->json(['status' => 0, 'message' => 'order not found'], 404);
        }
        if ($order->merchant_id !== $user->id) {
            return response()->json(['status' => 0, 'message' => 'order not belongs this merchant'], 401);
        }
        $payment = $order->getOrderPayment();
        if ($payment === null) {
            return response()->json(['status' => 0, 'message' => 'orderPayment not found'], 404);
        }
        $payment->status = 1;
        $payment->save();
        return response()->json(['status' => 1]);
    }

    /**
     *  @OA\Post(
     *      path="/api/auth/marchant/manage/{oid}/complete",
     *      summary="商家標示訂單已完成",
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
    public function completeMerchantOrder() {
        $user = auth()->user();
        $oid = request()->route('oid');
        $order = Order::find($oid);
        if ($order === null) {
            return response()->json(['status' => 0, 'message' => 'order not found'], 404);
        }
        if ($order->merchant_id !== $user->id) {
            return response()->json(['status' => 0, 'message' => 'order not belongs this merchant'], 401);
        }
        $order->status = 2;
        return response()->json(['status' => 1]);
    }
}
