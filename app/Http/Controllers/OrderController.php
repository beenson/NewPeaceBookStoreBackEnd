<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
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
     *                          "updated_at": null,
     *
     *                          "orderItems": {{
     *                              "id": 1,
     *                              "order_id": 1,
     *                              "item_id": 2,
     *                              "quantity": 10,
     *                              "price": 10,
     *                              "item": {
     *                                  "id": 2,
     *                                  "owner": {
     *                                      "id": 1,
     *                                      "name": "1",
     *                                      "email": "1",
     *                                      "role": 0,
     *                                      "sid": "3",
     *                                      "major": 1,
     *                                      "remember_token": null,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "category": {
     *                                      "id": 1,
     *                                      "name": "category-1",
     *                                      "is_department": true,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "name": "Book Name2",
     *                                  "ISBN": "3333-1111-2222-1234",
     *                                  "price": 800,
     *                                  "quantity": 2,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                  "images": {{
     *                                      "id": 1,
     *                                      "item_id": 2,
     *                                      "photo": "(Blob)"
     *                                  }},
     *                                  "tags": {{
     *                                      "id": 1,
     *                                      "tag_id": 1,
     *                                      "item_id": 2,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                      "tag": {
     *                                          "id": 1,
     *                                          "name": "tag-1",
     *                                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                      }
     *                                  }}
     *                              }
     *                          }}
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
     *      summary="指定訂單詳細紀錄",
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
     *                      "updated_at": null,
     *                      "orderItems": {{
     *                          "id": 1,
     *                          "order_id": 1,
     *                          "item_id": 2,
     *                          "quantity": 10,
     *                          "price": 10,
     *                          "item": {
     *                              "id": 2,
     *                              "owner": {
     *                                  "id": 1,
     *                                  "name": "1",
     *                                  "email": "1",
     *                                  "role": 0,
     *                                  "sid": "3",
     *                                  "major": 1,
     *                                  "remember_token": null,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z"
     *                              },
     *                              "category": {
     *                                  "id": 1,
     *                                  "name": "category-1",
     *                                  "is_department": true,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z"
     *                              },
     *                              "name": "Book Name2",
     *                              "ISBN": "3333-1111-2222-1234",
     *                              "price": 800,
     *                              "quantity": 2,
     *                              "created_at": "2021-11-12T15:15:10.000000Z",
     *                              "updated_at": "2021-11-12T15:15:10.000000Z",
     *                              "images": {{
     *                                  "id": 1,
     *                                  "item_id": 2,
     *                                  "photo": "(Blob)"
     *                              }},
     *                              "tags": {{
     *                                  "id": 1,
     *                                  "tag_id": 1,
     *                                  "item_id": 2,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                  "tag": {
     *                                      "id": 1,
     *                                      "name": "tag-1",
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  }
     *                              }}
     *                          }
     *                      }}
     *                  },
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
     *      path="/api/admin/user/{id}/orders",
     *      summary="會員訂單紀錄",
     *      tags={"Admin"},
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
     *                          "updated_at": null,
     *
     *                          "orderItems": {{
     *                              "id": 1,
     *                              "order_id": 1,
     *                              "item_id": 2,
     *                              "quantity": 10,
     *                              "price": 10,
     *                              "item": {
     *                                  "id": 2,
     *                                  "owner": {
     *                                      "id": 1,
     *                                      "name": "1",
     *                                      "email": "1",
     *                                      "role": 0,
     *                                      "sid": "3",
     *                                      "major": 1,
     *                                      "remember_token": null,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "category": {
     *                                      "id": 1,
     *                                      "name": "category-1",
     *                                      "is_department": true,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "name": "Book Name2",
     *                                  "ISBN": "3333-1111-2222-1234",
     *                                  "price": 800,
     *                                  "quantity": 2,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                  "images": {{
     *                                      "id": 1,
     *                                      "item_id": 2,
     *                                      "photo": "(Blob)"
     *                                  }},
     *                                  "tags": {{
     *                                      "id": 1,
     *                                      "tag_id": 1,
     *                                      "item_id": 2,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                      "tag": {
     *                                          "id": 1,
     *                                          "name": "tag-1",
     *                                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                      }
     *                                  }}
     *                              }
     *                          }}
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
     *      path="/api/admin/user/{id}/order/{oid}",
     *      summary="會員指定訂單詳細紀錄",
     *      tags={"Admin"},
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
     *                      "updated_at": null,
     *                      "orderItems": {{
     *                          "id": 1,
     *                          "order_id": 1,
     *                          "item_id": 2,
     *                          "quantity": 10,
     *                          "price": 10,
     *                          "item": {
     *                              "id": 2,
     *                              "owner": {
     *                                  "id": 1,
     *                                  "name": "1",
     *                                  "email": "1",
     *                                  "role": 0,
     *                                  "sid": "3",
     *                                  "major": 1,
     *                                  "remember_token": null,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z"
     *                              },
     *                              "category": {
     *                                  "id": 1,
     *                                  "name": "category-1",
     *                                  "is_department": true,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z"
     *                              },
     *                              "name": "Book Name2",
     *                              "ISBN": "3333-1111-2222-1234",
     *                              "price": 800,
     *                              "quantity": 2,
     *                              "created_at": "2021-11-12T15:15:10.000000Z",
     *                              "updated_at": "2021-11-12T15:15:10.000000Z",
     *                              "images": {{
     *                                  "id": 1,
     *                                  "item_id": 2,
     *                                  "photo": "(Blob)"
     *                              }},
     *                              "tags": {{
     *                                  "id": 1,
     *                                  "tag_id": 1,
     *                                  "item_id": 2,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                  "tag": {
     *                                      "id": 1,
     *                                      "name": "tag-1",
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  }
     *                              }}
     *                          }
     *                      }}
     *                  },
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
     *      path="/api/auth/merchant/orders",
     *      summary="商家取得訂單",
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
     *                          "updated_at": null,
     *
     *                          "orderItems": {{
     *                              "id": 1,
     *                              "order_id": 1,
     *                              "item_id": 2,
     *                              "quantity": 10,
     *                              "price": 10,
     *                              "item": {
     *                                  "id": 2,
     *                                  "owner": {
     *                                      "id": 1,
     *                                      "name": "1",
     *                                      "email": "1",
     *                                      "role": 0,
     *                                      "sid": "3",
     *                                      "major": 1,
     *                                      "remember_token": null,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "category": {
     *                                      "id": 1,
     *                                      "name": "category-1",
     *                                      "is_department": true,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                  },
     *                                  "name": "Book Name2",
     *                                  "ISBN": "3333-1111-2222-1234",
     *                                  "price": 800,
     *                                  "quantity": 2,
     *                                  "created_at": "2021-11-12T15:15:10.000000Z",
     *                                  "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                  "images": {{
     *                                      "id": 1,
     *                                      "item_id": 2,
     *                                      "photo": "(Blob)"
     *                                  }},
     *                                  "tags": {{
     *                                      "id": 1,
     *                                      "tag_id": 1,
     *                                      "item_id": 2,
     *                                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                                      "updated_at": "2021-11-12T15:15:10.000000Z",
     *                                      "tag": {
     *                                          "id": 1,
     *                                          "name": "tag-1",
     *                                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                                      }
     *                                  }}
     *                              }
     *                          }}
     *                      }
     *                  }
     *              }
     *          )
     *      })
     *  )
     */
    public function getAuthMerchantOrders() {
        $user = auth()->user();
        $data = Order::getMerchantAllOrders($user->id);
        //$todo_orders = Order::getMerchantOrders($user->id);
        //$done_orders = Order::getMerchantOrders($user->id, false);
        //return response()->json(['status' => 1, 'todo' => $todo_orders, 'done' => $done_orders]);
        return response()->json(['status' => 1, 'data' => $data]);
    }
    /**
     *  @OA\Get(
     *      path="/api/auth/merchant/order/{oid}",
     *      summary="商家取得指定訂單詳細資訊",
     *      tags={"Order"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "order": {
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "status": 1,
     *                      "total_price": 100,
     *                      "created_at": null,
     *                      "updated_at": null
     *                  },
     *                  "items": {{
     *                      "id": 2,
     *                      "owner": {
     *                          "id": 1,
     *                          "name": "1",
     *                          "email": "1",
     *                          "role": 0,
     *                          "sid": "3",
     *                          "major": 1,
     *                          "remember_token": null,
     *                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                      },
     *                      "category": {
     *                          "id": 1,
     *                          "name": "category-1",
     *                          "is_department": true,
     *                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                      },
     *                      "name": "Book Name2",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 800,
     *                      "quantity": 2,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z",
     *                      "images": {{
     *                          "id": 1,
     *                          "item_id": 2,
     *                          "photo": "(Blob)"
     *                      }},
     *                      "tags": {{
     *                          "id": 1,
     *                          "tag_id": 1,
     *                          "item_id": 2,
     *                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                          "updated_at": "2021-11-12T15:15:10.000000Z",
     *                          "tag": {
     *                              "id": 1,
     *                              "name": "tag-1",
     *                              "created_at": "2021-11-12T15:15:10.000000Z",
     *                              "updated_at": "2021-11-12T15:15:10.000000Z"
     *                          }
     *                      }}
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getAuthMerchantOrder() {
        $user = auth()->user();
        $oid = request()->route('oid');
        $order = Order::where('merchant_id', $user->id)->where('id', $oid)->get()->first();
        return response()->json(['status' => 1, 'order' => $order]);
    }
    /**
     *  @OA\Post(
     *      path="/api/auth/merchant/order/{oid}/payment/complete",
     *      summary="商家手動標示訂單已付款(賣家或管理員可操作)",
     *      tags={"Order"},
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
        if ($order->merchant_id !== $user->id && $user->role != User::$ADMIN) {
            return response()->json(['status' => 0, 'message' => 'order not belongs this merchant'], 401);
        }
        $payment = $order->getOrderPayment();
        /*if ($payment === null) {
            return response()->json(['status' => 0, 'message' => 'orderPayment not found'], 404);
        }*/
        if ($payment !== null) {
            $payment->status = 1;
            $payment->save();
        }
        $order->status = 1;
        $order->save();
        return response()->json(['status' => 1]);
    }
    /**
     *  @OA\Post(
     *      path="/api/auth/merchant/order/{oid}/complete",
     *      summary="商家標示訂單已完成(賣家或管理員可操作)",
     *      tags={"Order"},
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
        if ($order->merchant_id !== $user->id && $user->role != User::$ADMIN) {
            return response()->json(['status' => 0, 'message' => 'order not belongs this merchant'], 401);
        }
        $order->status = 2;
        $order->save();
        return response()->json(['status' => 1]);
    }
    /**
     * items = [ // string (json array)
     *      itemId: 1,
     *      quantity: 10
     * ]
     */
    public function createOrder() {
        $user = auth()->user();
        $merchatId = request()->get('merchantId');
        $orderItems = [];
        $totalPrice = 0;
        $items = json_decode(request()->get('items'));
        foreach($items as $value) {
            $id = $value->itemId;
            $quantity = $value->quantity;
            $item = Item::find($id);
            if ($item === null) {
                return response()->json(['status' => 0, 'message' => 'item not found'], 404);
            }
            if ($quantity > $item->quantity) {
                return response()->json(['status' => 0, 'message' => 'item not enough'], 401);
            }
            $totalPrice += $item->price * $quantity;
        }
        $order = new Order;
        $order->merchat_id = $merchatId;
        $order->user_id = $user->id;
        $order->totalPrice = $totalPrice;
        $order->save();
        foreach($items as $value) {
            $id = $value->id;
            $quantity = $value->quantity;
            $item = Item::find($id);
            $item->quantity -= $quantity;
            $item->save();
            $orderItem = new OrderItem;
            $orderItem->item_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->quantity = $quantity;
            $orderItem->price = $item->price * $quantity;
            $orderItem->save();
            array_push($orderItems, $orderItem);
        }
        $order = Order::find($order->id);
        return response()->json(['status' => 1, 'data' =>$order]);
    }
}
