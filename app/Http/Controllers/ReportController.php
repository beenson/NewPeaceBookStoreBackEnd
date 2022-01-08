<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([]);
        $this->middleware('admin')->except(['submitReport']);
    }

    /**
     *  @OA\Post(
     *      path="/api/user/{id}/report",
     *      summary="檢舉使用者",
     *      tags={"Report"},
     *      @OA\Parameter(
     *          name="reason",
     *          in="query",
     *          description="檢舉類型",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="detail",
     *          in="query",
     *          description="詳細描述",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response=200, description="舉報成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "bad request"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(被舉報者不存在)",content={
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
    public function submitReport() {
        $user = auth()->user();
        $victimId = request()->route('id');
        $reason = request()->get('reason');
        $detail = request()->get('detail');
        if ($victimId === null || $reason === null || $detail === null) {
            return response()->json(['status' => 0, 'message' => 'bad request'], 400);
        }
        $victim = User::find($victimId);
        if ($victim === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        $report = new Report;
        $report->reporter = $user->id;
        $report->victim = $victim->id;
        $report->reason = $reason;
        $report->detail = $detail;
        $report->save();
        return response()->json(['status' => 1], 200);
    }

    /**
     *  @OA\Get(
     *      path="/api/report/unresolves",
     *      summary="取得未處理的檢舉紀錄",
     *      tags={"Report"},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {{
     *                      "id": 1,
     *                      "reporter": 1,
     *                      "victim": 2,
     *                      "reason": 0,
     *                      "detail": "report detail",
     *                      "status": 0,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getWaintingResolveReports() {
        $reports = Report::getWaitingResolveReports();
        return response()->json(['status' => 1, 'data' => $reports], 200);
    }

    /**
     *  @OA\Get(
     *      path="/api/report/resolves",
     *      summary="取得已處理的檢舉紀錄",
     *      tags={"Report"},
     *      @OA\Response(response=200, description="成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {{
     *                      "id": 1,
     *                      "reporter": 1,
     *                      "victim": 2,
     *                      "reason": 0,
     *                      "detail": "report detail",
     *                      "status": 1,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }}
     *              }
     *          )
     *      })
     *  )
     */
    public function getResolvedReports() {
        $reports = Report::getResolvedReports();
        return response()->json(['status' => 1, 'data' => $reports], 200);
    }
    /**
     *  @OA\Post(
     *      path="/api/report/{id}/resolve",
     *      summary="處理檢舉",
     *      tags={"Report"},
     *      @OA\Parameter(
     *          name="time",
     *          in="query",
     *          description="懲罰時長(毫秒, 不懲罰=0)",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(response=200, description="處理成功",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 1,
     *                  "data": {
     *                      "id": 1,
     *                      "reporter": 1,
     *                      "victim": 2,
     *                      "reason": 0,
     *                      "detail": "report detail",
     *                      "status": 1,
     *                      "created_at": "2021-11-12T15:15:10.000000Z",
     *                      "updated_at": "2021-11-12T15:15:10.000000Z"
     *                  }
     *              }
     *          )
     *      }),
     *      @OA\Response(response=400, description="失敗(請求格式錯誤)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "bad request"
     *              }
     *          )
     *      }),
     *      @OA\Response(response=404, description="失敗(舉報紀錄不存在)",content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              example={
     *                  "status": 0,
     *                  "message": "report not found"
     *              }
     *          )
     *      })
     *  )
     */
    public function resolveReport() {
        $id = request()->route('id');
        $time = request()->get('time');
        if ($id === null || $time === null) {
            return response()->json(['status' => 0, 'message' => 'bad request'], 400);
        }
        $report = Report::find($id);
        if ($report === null) {
            return response()->json(['status' => 0, 'message' => 'report not found'], 404);
        }
        $report->resolve($time);
        return response()->json(['status' => 1, 'data' => $report], 200);
    }
}
