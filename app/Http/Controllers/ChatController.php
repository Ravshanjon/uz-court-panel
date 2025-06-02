<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;


use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\ChatMessageNotification;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'chat_id' => 'required|integer',
        ]);


        $user = auth()->user();

        $message = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $validated['message'],
            'chat_id' => $validated['chat_id'],
        ]);

        // Уведомляем администраторов
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new ChatMessageNotification($message, $user));
        }

        return response()->json(['status' => 'success', 'message' => 'Message sent and admin notified']);
    }
}

