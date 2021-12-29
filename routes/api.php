<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BanRecordController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, authorization");*/
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
 * 檢舉紀錄
 * @OA\Tag(
 *     name="Report",
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
    Route::post('/recommendedItems', [AuthController::class, 'getItemsByMajor']);

    Route::get('/orders', [OrderController::class, 'authOrders']);
    Route::get('/order/{id}', [OrderController::class, 'authOrder']);
    Route::post('/order/{id}/comment', [AuthController::class, 'postComment']);

    Route::get('/comments', [AuthController::class, 'getAuthComments']);

    Route::get('/marchant/orders', [OrderController::class, 'getAuthMerchantOrders']);
    Route::get('/marchant/order/{oid}', [OrderController::class, 'getAuthMerchantOrder']);
    Route::post('/marchant/order/{oid}/complete', [OrderController::class, 'completeMerchantOrder']);
    Route::post('/marchant/order/{oid}/payment/complete', [OrderController::class, 'completeMerchantOrderPayment']);

    Route::get('/banRecords', [BanRecordController::class, 'getMyBanRecords']);
    Route::get('/items', [ItemController::class, 'getAuthItems']);
    Route::post('/createOrder', [OrderController::class, 'createOrder']);
});

Route::group(['prefix' => 'admin/user'], function () {
    Route::get('/', [UserController::class, 'users']);
    Route::post('/', [UserController::class, 'createUser']);
    Route::post('/{id}', [UserController::class, 'updateUser']);

    Route::get('/{id}/orders', [OrderController::class, 'userOrders']);
    Route::get('/{id}/order/{oid}', [OrderController::class, 'userOrder']);
    Route::get('/{id}/banRecords', [BanRecordController::class, 'getBanRecordsByUser']);
    Route::post('/{id}/ban', [BanRecordController::class, 'banUser']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/{id}', [UserController::class, 'user']);
    Route::get('/{id}/items', [ItemController::class, 'getUserItems']);
    // Route::get('/{id}/comments', [UserController::class, 'userComments']);
    Route::get('/{id}/merchant_comments', [UserController::class, 'userMerchantComments']);
    Route::get('/{id}/report', [ReportController::class, 'submitReport']);
});

Route::group(['prefix' => '/admin/ban_record'], function () {
    Route::get('/', [BanRecordController::class, 'getBanRecords']);
    Route::post('/{id}/update', [BanRecordController::class, 'updateRecord']);
    Route::post('/{id}/delete', [BanRecordController::class, 'deleteRecord']);
});

Route::group(['prefix' => 'report'], function () {
    Route::get('/unresolves', [ReportController::class, 'getWaintingResolveReports']);
    Route::get('/resolves', [ReportController::class, 'getResolvedReports']);
    Route::get('/{id}/resolve', [ReportController::class, 'resolveReport']);
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
    Route::post('/{id}/update', [CategoryController::class, 'updateCategory']);
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
Route::group(['prefix' => 'chatroom'], function () {
    Route::post('/checkUser', [MessageController::class, 'checkUser']);
    Route::post('/getMessage', [MessageController::class, 'getMessage']);
    Route::post('/postMessage', [MessageController::class, 'postMessage']);
});

Route::post('/upload', function () {
    if (request()->hasFile('photo')) {
        return 1;
    }
    return 0;
});
