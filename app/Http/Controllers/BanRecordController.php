<?php

namespace App\Http\Controllers;

use App\Models\BanRecord;
use App\Models\User;
use Illuminate\Http\Request;

class BanRecordController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->except(['getMyBanRecords']);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/banRecords",
     *      summary="個人違規紀錄",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "reason": "123456",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  },
     *                  {
     *                      "id": 2,
     *                      "user_id": 2,
     *                      "reason": "hacker",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getMyBanRecords() {
        $user = auth()->user();
        return response()->json(['status' => 1, 'data' => $user->getBanRecords() ], 200);
    }

    /**
     *  @OA\Get(
     *      path="/api/ban_record",
     *      summary="近三十天的違規紀錄",
     *      tags={"BanRecord"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "reason": "123456",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  },
     *                  {
     *                      "id": 2,
     *                      "user_id": 2,
     *                      "reason": "hacker",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getBanRecords() {
        $data = BanRecord::where('create_at', '>=', time() - 30 * 24 * 60 * 60)->orderBy('id', 'DESC')->get();
        return response()->json(['status' => 1, 'data' => $data ], 200);
    }

    /**
     *  @OA\Get(
     *      path="/api/user/{id}/banRecords",
     *      summary="指定使用者的違規紀錄",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data":{{
     *                      "id": 1,
     *                      "user_id": 1,
     *                      "reason": "123456",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  },
     *                  {
     *                      "id": 2,
     *                      "user_id": 2,
     *                      "reason": "hacker",
     *                      "duration": "2021-11-12 22:14:56",
     *                      "created_at": null,
     *                      "updated_at": null,
     *                  }}
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
    public function getBanRecordsByUser() {
        $id = request()->route('id');
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $user->getBanRecords() ], 200);
    }

    /**
     *  @OA\Post(
     *      path="/api/user/{id}/ban",
     *      summary="封鎖指定使用者",
     *      tags={"User"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
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
    public function banUser() {
        $id = request()->route('id');
        $reason = request()->get('reason');
        $duration = request()->get('duration');
        if ($reason === null || $duration === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        $user = User::find($id);
        if ($user === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        // TODO: 檢測duration timestamp
        $record = new BanRecord;
        $record->user_id = $user->id;
        $record->reason = $reason;
        $record->duration = $duration;
        $record->save();
        return response()->json(['status' => 1], 200);
    }

    /**
     *  @OA\Post(
     *      path="/api/ban_record/{id}/update",
     *      summary="修改封鎖紀錄",
     *      tags={"BanRecord"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(紀錄不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "record not found"
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
    public function updateRecord() {
        $id = request()->route('id');
        $reason = request()->get('reason');
        $duration = request()->get('duration');
        if ($reason === null || $duration === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        $record = BanRecord::find($id);
        if ($record === null) {
            return response()->json(['status' => 0, 'message' => 'record not found'], 404);
        }
        // TODO: 檢測duration timestamp
        $record->reason = $reason;
        $record->duration = $duration;
        $record->save();
        return response()->json(['status' => 1], 200);
    }

    /**
     *  @OA\Post(
     *      path="/api/ban_record/{id}/delete",
     *      summary="刪除封鎖紀錄",
     *      tags={"BanRecord"},
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
    public function deleteRecord() {
        $id = request()->route('id');
        BanRecord::destroy($id);
        return response()->json(['status' => 1], 200);
    }
}
