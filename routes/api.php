<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BanRecordController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, authorization");
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      in="header",
 *      name="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * ),
 */

/**
 * @OA\Tag(
 *     name="Auth",
 * )
 * @OA\Tag(
 *     name="User",
 * )
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @OA\Tag(
 *     name="Auth",
 * )
 */
/**
 * 管理使用者
 * @OA\Tag(
 *     name="User",
 * )
 */

/**
 * 訂單
 * @OA\Tag(
 *     name="Order",
 * )
 */
/**
 * 違規紀錄管理
 * @OA\Tag(
 *     name="BanRecord",
 * )
 */
/**
 * 標籤
 * @OA\Tag(
 *     name="Tag",
 * )
 */
/**
 * 分類
 * @OA\Tag(
 *     name="Category",
 * )
 */
/**
 * 商品
 * @OA\Tag(
 *     name="Item",
 * )
 */
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'me']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/bindPhone', [AuthController::class, 'bindPhone']);
    Route::post('/verifyPhone', [AuthController::class, 'verifyPhone']);
    Route::post('/editProfile', [AuthController::class, 'editProfile']);

    Route::get('/orders', [OrderController::class, 'authOrders']);
    Route::get('/order/{id}', [OrderController::class, 'authOrder']);

    Route::get('/banRecords', [BanRecordController::class, 'getMyBanRecords']);
    Route::get('/items', [ItemController::class, 'getAuthItems']);
    Route::get('/marchant/manage', [OrderController::class, 'getAuthMerchantOrders']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'users']);
    Route::post('/', [UserController::class, 'createUser']);
    Route::get('/{id}', [UserController::class, 'user']);
    Route::post('/{id}', [UserController::class, 'updateUser']);

    Route::get('/{id}/orders', [OrderController::class, 'userOrders']);
    Route::get('/{id}/order/{oid}', [OrderController::class, 'userOrder']);
    Route::get('/{id}/banRecords', [BanRecordController::class, 'getBanRecordsByUser']);
    Route::post('/{id}/ban', [BanRecordController::class, 'banUser']);
    Route::get('/{id}/items', [ItemController::class, 'getUserItems']);
});

Route::group(['prefix' => 'ban_record'], function () {
    Route::get('/', [BanRecordController::class, 'getBanRecords']);
    Route::post('/{id}/update', [BanRecordController::class, 'updateRecord']);
    Route::post('/{id}/delete', [BanRecordController::class, 'deleteRecord']);
});

Route::group(['prefix' => 'tag'], function () {
    Route::get('/search', [TagController::class, 'searchTags']);
    Route::post('/create', [TagController::class, 'createTag']);
    Route::get('/{id}/items', [TagController::class, 'getRelationItems']);
    Route::post('/{id}/delete', [TagController::class, 'deleteTag']);
});

Route::group(['prefix' => 'category'], function () {
    Route::get('/list', [CategoryController::class, 'categorys']);
    Route::post('/create', [CategoryController::class, 'createCategory']);
    Route::get('/{id}/items', [CategoryController::class, 'getCategoryItems']);
    Route::post('/{id}/delete', [CategoryController::class, 'deleteCategory']);
});

Route::group(['prefix' => 'item'], function () {
    Route::get('/search', [ItemController::class, 'searchItems']);
    Route::get('/searchISBN', [ItemController::class, 'getItemsByISBN']);
    Route::post('/create', [ItemController::class, 'createItem']);
    Route::post('/{id}', [ItemController::class, 'getItem']);
    Route::post('/{id}/update', [ItemController::class, 'updateItem']);
    Route::post('/{id}/delete', [ItemController::class, 'deleteItem']);
});

