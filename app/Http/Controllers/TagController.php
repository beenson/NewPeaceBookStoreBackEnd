<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['createTag', 'deleteTag']);
        $this->middleware('admin')->only(['deleteTag', 'updateTag']);
    }

    /**
     *  @OA\Get(
     *      path="/api/tag/search",
     *      summary="搜尋標籤",
     *      tags={"Tag"},
     *      @OA\Parameter(
     *          name="text",
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
     *                      "name": "tag-1",
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  },
     *                  {
     *                      "id": 2,
     *                      "name": "tag-2",
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function searchTags() {
        $text = request()->input('text');
        $tags = Tag::where('name', 'LIKE', "%$text%")->get();
        return response()->json(['status' => 1, 'data' => $tags]);
    }

    /**
     *  @OA\Get(
     *      path="/api/tag/{id}/items",
     *      summary="取得標籤相關商品",
     *      tags={"Tag"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
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
     *                  },
     *                  {
     *                      "id": 2,
     *                      "owner": {
     *                          "id": 2,
     *                          "name": "222",
     *                          "email": "1",
     *                          "role": 0,
     *                          "sid": "3",
     *                          "major": 1,
     *                          "remember_token": null,
     *                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                      },
     *                      "category": {
     *                          "id": 2,
     *                          "name": "category-2",
     *                          "is_department": true,
     *                          "created_at": "2021-11-12T15:15:10.000000Z",
     *                          "updated_at": "2021-11-12T15:15:10.000000Z"
     *                      },
     *                      "name": "Book Name3",
     *                      "ISBN": "5555-1111-2222-1234",
     *                      "price": 400,
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
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getRelationItems() {
        $id = request()->route('id');
        $tag = Tag::find($id);
        if ($tag === null) {
            return response()->json(['status' => 0, 'message' => 'tag not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $tag->getItems()]);
    }

    /**
     *  @OA\Post(
     *      path="/api/tag/create",
     *      summary="新增標籤",
     *      tags={"Tag"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="標籤名稱",
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
     *                      "name": "tag-1",
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=409, description="失敗(名稱重複)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "duplicate tag name"
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
    public function createTag() {
        $name = request()->input('name');
        if ($name === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        if (Tag::checkDuplicateName($name)) {
            return response()->json(['status' => 0, 'message' => 'duplicate tag name'], 409);
        }
        $tag = new Tag;
        $tag->name = $name;
        $tag->save();
        return response()->json(['status' => 1, 'data' => $tag]);
    }


    /**
     *  @OA\Post(
     *      path="/api/tag/{id}/update",
     *      summary="修改標籤",
     *      tags={"Tag"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="標籤名稱",
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
     *                      "name": "tag-1",
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=409, description="失敗(名稱重複)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "duplicate tag name"
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
     *      @OA\Response(response=404, description="失敗(標籤不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "tag not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function updateTag() {
        $id = request()->route('id');
        $tag = Tag::find($id);
        $name = request()->input('name');
        if ($name === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        if ($tag === null) {
            return response()->json(['status' => 0, 'message' => 'tag not found'], 404);
        }
        if (Tag::checkDuplicateName($name)) {
            return response()->json(['status' => 0, 'message' => 'duplicate tag name'], 409);
        }
        $tag->name = $name;
        $tag->save();
        return response()->json(['status' => 1, 'data' => $tag]);
    }
    /**
     *  @OA\Post(
     *      path="/api/tag/{id}/delete",
     *      summary="刪除標籤",
     *      tags={"Tag"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(標籤不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "tag not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function deleteTag() {
        $id = request()->route('id');
        $count = Tag::destroy($id);
        if ($count === 0) {
            return response()->json(['status' => 0, 'message' => 'tag not found'], 404);
        }
        return response()->json(['status' => 1]);
    }
}
