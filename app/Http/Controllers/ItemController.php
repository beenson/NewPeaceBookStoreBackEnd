<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\ItemPreview;
use App\Models\ItemTag;
use App\Models\Order;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
    }

    /**
     *  @OA\Get(
     *      path="/api/item/{id}",
     *      summary="取得商品資訊(顧客)",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{
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
     *                  }
     *              }
     *          )
     *      })
     *  )
     */
    public function getItem() {
        $id = request()->route('id');
        $item = Item::find($id);
        if ($item === null) {
            return response()->json(['status' => 0, 'message' => 'item not found'], 404);
        }
        // 存量不足
        /*if ($item->quantity <= 0) {
            return response()->json(['status' => 0, 'message' => 'item not enough'], 404);
        }*/
        return response()->json(['status' => 1, 'data' => $item]);
    }
    /**
     *  @OA\Get(
     *      path="/api/auth/items",
     *      summary="查詢自己的商品",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  },
     *                  {
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
    public function getAuthItems() {
        $user = auth()->user();
        return response()->json(['status' => 1, 'data' => $user->getItems()]);
    }
    /**
     *  @OA\Get(
     *      path="/api/user/{id}/items",
     *      summary="查詢他人的商品",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  },
     *                  {
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
    public function getUserItems() {
        $id = request()->route('id');
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        $user = auth()->user();
        // TODO: 移除已賣出的資料
        return response()->json(['status' => 1, 'data' => $user->getItems()]);
    }
    /**
     *  @OA\Post(
     *      path="/api/item/create",
     *      summary="新增商品",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="商品名稱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="商品簡介",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="ISBN",
     *          in="query",
     *          description="ISBN",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="category",
     *          in="query",
     *          description="分類",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          in="query",
     *          description="價格",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="quantity",
     *          in="query",
     *          description="存量",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="images",
     *          in="query",
     *          description="圖片(json array base64)",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="tags",
     *          in="query",
     *          description="標籤(json array base64)",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "error Input"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(未知的分類)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "category not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function createItem() {
        $user = auth()->user();
        $category = request()->get('category');
        $name = request()->get('name');
        $description = request()->get('description');
        $ISBN = request()->get('ISBN');
        $price = request()->get('price');
        $quantity = request()->get('quantity');
        $images = json_decode(request()->get('images'));
        $tags = json_decode(request()->get('tags'));
        if ($category === null || $name === null || $ISBN === null || $price === null || $quantity === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        if ($price <= 0 || $quantity <= 0) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        // TODO: 檢測Category存在, 設定複數標籤
        if (Category::find($category) === null) {
            return response()->json(['status' => 0, 'message' => 'category not found'], 400);
        }
        $item = new Item;
        $item->category = $category;
        $item->name = $name;
        $item->description = $description;
        $item->ISBN = $ISBN;
        $item->price = $price;
        $item->quantity = $quantity;
        $item->owner = $user->id;
        $item->save();
        foreach($images as $image) {
            $preview = new ItemPreview;
            $preview->item_id = $item->id;
            $preview->photo = $image;
            $preview->save();
        }
        foreach ($tags as $tagName) {
            $tag = Tag::getTagByName($tagName);
            if ($tag === null) {
                $tag = new Tag;
                $tag->name = $tagName;
                $tag->save();
            }
            $itemTag = new ItemTag;
            $itemTag->item_id = $item->id;
            $itemTag->tag_id = $tag->id;
            $itemTag->save();
        }
        return response()->json(['status' => 1, 'data' => $item]);
    }
    /**
     *  @OA\Post(
     *      path="/api/item/{id}/update",
     *      summary="修改商品 (擁有者&管理員可用)",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="商品名稱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="ISBN",
     *          in="query",
     *          description="ISBN",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          in="query",
     *          description="價格",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="quantity",
     *          in="query",
     *          description="存量",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "error Input"
     *              }
     *          )
     *      })
     *  )
     */
    public function updateItem() {
        $user = auth()->user();
        $id = request()->route('id');
        $item = Item::find($id);
        if ($item === null) {
            return response()->json(['status' => 0, 'message' => 'item not found'], 404);
        }
        if ($item->owner != $user->id && $user->role !== User::$ADMIN) {
            return response()->json(['status' => 0, 'message' => 'item not found'], 404);
        }
        $category = request()->get('category');
        $name = request()->get('name');
        $ISBN = request()->get('ISBN');
        $price = request()->get('price');
        $quantity = request()->get('quantity');
        if ($category === null || $name === null || $ISBN === null || $price === null || $quantity === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        // TODO: 檢測Category存在, 設定複數標籤
        $item->category = $category;
        $item->name = $name;
        $item->ISBN = $ISBN;
        $item->price = $price;
        $item->quantity = $quantity;
        $item->save();
        return response()->json(['status' => 1, 'data' => $item]);
    }
    /**
     *  @OA\Post(
     *      path="/api/item/{id}/delete",
     *      summary="刪除商品 (擁有者&管理員可用)",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(商品不存在或不具執行權限)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "item not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function deleteItem() {
        $user = auth()->user();
        $id = request()->route('id');
        $item = Item::find($id);
        if ($item === null) {
            return response()->json(['status' => 0, 'message' => 'item not found'], 404);
        }
        if ($item->owner != $user->id && $user->role !== User::$ADMIN) {
            return response()->json(['status' => 0, 'message' => 'item not found'], 404);
        }
        Item::destroy($id);
        return response()->json(['status' => 1]);
    }
    /**
     *  @OA\Get(
     *      path="/api/item/searchISBN",
     *      summary="以ISBN搜尋",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="ISBN",
     *          in="query",
     *          description="ISBN",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  },
     *                  {
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
    public function getItemsByISBN() {
        $ISBN = request()->get('ISBN');
        $items = Item::where('ISBN', $ISBN)->where('quantity', '>', 0)->orderBy('id', 'desc')->get();
        return response()->json(['status' => 1, 'data' => $items]);
    }
    /**
     *  @OA\Get(
     *      path="/api/item/search",
     *      summary="關鍵字搜尋",
     *      tags={"Item"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          description="關鍵字",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
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
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
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
     *                  },
     *                  {
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
    public function searchItems() {
        $keyword = request()->get('key');
        $items = Item::where('name', 'LIKE', "%$keyword%")
                    ->orWhere('ISBN', 'LIKE', "%$keyword%")
                    ->orderBy('id', 'desc')->get();
        return response()->json(['status' => 1, 'data' => $items]);
    }
}
