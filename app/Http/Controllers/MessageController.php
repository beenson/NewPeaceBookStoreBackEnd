<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['checkUser']);
    }


    public function postMessage() {
        $from = request()->get('from');
        $to = request()->get('to');
        $msg = request()->get('message');
        $fromUser = User::find($from);
        $toUser = User::find($to);
        if ($fromUser === null || $toUser === null || $msg === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        $message = new Message;
        $message->message = $msg;
        $message->from_user = $fromUser->id;
        $message->to_user = $toUser->id;
        $message->save();
        return response()->json(['status' => 1]);
    }

    public function getMessage() {
        $from = request()->get('from');
        $to = request()->get('to');
        $user = User::find($from);
        $targetUser = User::find($to);
        if ($targetUser === null) {
            return response()->json(['status' => 0, 'message' => 'user not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $user->getMessages($targetUser)]);
    }

    public function messageList() {
        $userId = request()->get('userId');
        $user = User::find($userId);
        $targets = [];
        $messages = $user->getAllMessages();
        foreach ($messages as $message) {
            if ($user->id != $message->from_user) {
                if (!in_array($message->from_user, $targets)) {
                    array_push($targets, $message->from_user);
                }
            }
            if ($user->id != $message->to_user) {
                if (!in_array($message->to_user, $targets)) {
                    array_push($targets, $message->to_user);
                }
            }
        }
        return response()->json(['status' => 1, 'data' => $targets]);
    }

    // return user.id, messageList
    public function checkUser() {
        $user = auth()->user();
        $targets = [];
        $messages = $user->getAllMessages();
        foreach ($messages as $message) {
            if ($user->id != $message->from_user) {
                if (!in_array($message->from_user, $targets)) {
                    array_push($targets, $message->from_user);
                }
            }
            if ($user->id != $message->to_user) {
                if (!in_array($message->to_user, $targets)) {
                    array_push($targets, $message->to_user);
                }
            }
        }
        return response()->json(['status' => 1, 'id' => $user->id, 'list' => $targets]);
    }
}
