<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage() {
        $user = auth()->user();
        $target = request()->get('chatUserId');
        $msg = request()->get('message');
        if ($target === null || $msg === null) {
            return response()->json(['status' => 0, 'message' => 'error Input'], 400);
        }
        $targetUser = User::find($target);
        if ($targetUser === null) {
            return response()->json(['status' => 0, 'message' => 'chatroom not found'], 404);
        }
        $message = new Message;
        $message->message = $msg;
        $message->from_user = $user->id;
        $message->to_user = $targetUser->id;
        $message->save();
        // TODO: 主動通知
        return response()->json(['status' => 1]);
    }

    public function getMessage() {
        $user = auth()->user();
        $target = request()->get('chatUserId');
        $targetUser = User::find($target);
        if ($targetUser === null) {
            return response()->json(['status' => 0, 'message' => 'chatroom not found'], 404);
        }
        return response()->json(['status' => 1, 'data' => $user->getMessages($targetUser)]);
    }
}
