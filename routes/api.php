<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
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
Route::group(['prefix' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('/orders', [OrderController::class, 'authOrders']);
});

/**
 * 管理使用者
 * @OA\Tag(
 *     name="User",
 * )
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'users']);
    Route::post('/', [UserController::class, 'createUser']);
    Route::get('/{id}', [UserController::class, 'user']);
    Route::post('/{id}', [UserController::class, 'updateUser']);

    Route::get('/{id}/orders', [OrderController::class, 'userOrders']);
});
