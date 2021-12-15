<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['deleteTag']);
        $this->middleware('admin')->only(['deleteTag']);
    }

    /**
     *  @OA\Get(
     *      path="/api/category/list",
     *      summary="分類列表",
     *      tags={"Category"},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "name": "category-1",
     *                      "is_department": true,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  },
     *                  {
     *                      "id": 2,
     *                      "name": "category-2",
     *                      "is_department": false,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function categorys() {
        return response()->json(['status' => 1, 'data' => Category::get()]);
    }

    /**
     *  @OA\Get(
     *      path="/api/category/{id}/items",
     *      summary="取得分類商品",
     *      tags={"Category"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "owner": 1,
     *                      "category": 1,
     *                      "name": "Book Name",
     *                      "ISBN": "3333-1111-2222-1234",
     *                      "price": 100,
     *                      "quantity": 1,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  },
     *                  {
     *                      "id": 2,
     *                      "owner": 2,
     *                      "category": 2,
     *                      "name": "Book Name2",
     *                      "ISBN": "7777-1666-5552-4321",
     *                      "price": 800,
     *                      "quantity": 2,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getCategoryItems() {
        $id = request()->route('id');
        $category = Category::find($id);
        if ($category === null) {
            return response()->json(['status' => 0, 'message' => 'category not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $category->getItems()]);
    }

    /**
     *  @OA\Post(
     *      path="/api/category/create",
     *      summary="新增分類",
     *      tags={"Category"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="分類名稱",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="is_department",
     *          in="query",
     *          description="是否為科系分類",
     *          required=true,
     *          @OA\Schema(
     *              type="boolean"
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
     *                      "is_department": true,
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
     *                  "message": "duplicate category name"
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
    public function createCategory() {
        $name = request()->get('name');
        $is_department = request()->get('is_department');
        if ($name === null || $is_department === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        if (Category::checkDuplicateName($name)) {
            return response()->json(['status' => 0, 'message' => 'duplicate category name'], 409);
        }
        $category = new Category;
        $category->name = $name;
        $category->is_department = $is_department;
        $category->save();
        return response()->json(['status' => 1, 'data' => $category]);
    }

    /**
     *  @OA\Post(
     *      path="/api/category/{id}/delete",
     *      summary="刪除分類",
     *      tags={"Category"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(分類不存在)",content={
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
    public function deleteCategory() {
        $id = request()->route('id');
        $count = Category::destroy($id);
        if ($count === 0) {
            return response()->json(['status' => 0, 'message' => 'category not found'], 404);
        }
        return response()->json(['status' => 1]);
    }
}
