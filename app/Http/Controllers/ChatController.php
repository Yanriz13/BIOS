<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\ChatNotification;
use App\Models\Task;
use App\Models\User;

class ChatController extends Controller
{
    public function taskRoom($taskId)
    {
        $task = Task::with('users')->findOrFail($taskId);

        $messages = ChatMessage::with(['fromUser', 'replyTo.fromUser'])
            ->where('task_id', $taskId)
            ->where(function ($query) {
                $query->where('group', true)
                    ->orWhere('from_user_id', auth()->id())
                    ->orWhere('to_user_id', auth()->id());
            })
            ->orderBy('created_at')
            ->get();

        ChatNotification::where('user_id', auth()->id())
            ->where('task_id', $taskId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'room' => [
                'type' => 'task',
                'id' => $task->id,
                'title' => $task->title,
            ],
        ]);
    }

    public function globalRoom()
    {
        $messages = ChatMessage::with(['fromUser', 'replyTo.fromUser'])
            ->where('group', true)
            ->whereNull('task_id')
            ->orderBy('created_at')
            ->get();

        ChatNotification::where('user_id', auth()->id())
            ->whereNull('task_id')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'room' => [
                'type' => 'global',
                'title' => 'Global Staff Chat',
            ],
        ]);
    }

    public function userRoom($userId)
    {
        $user = User::findOrFail($userId);

        $messages = ChatMessage::with(['fromUser', 'replyTo.fromUser'])
            ->where(function ($query) use ($userId) {
                $query->where('from_user_id', auth()->id())
                    ->where('to_user_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('from_user_id', $userId)
                    ->where('to_user_id', auth()->id());
            })
            ->orderBy('created_at')
            ->get();

        $messageIds = ChatMessage::where('from_user_id', $userId)
            ->where('to_user_id', auth()->id())
            ->pluck('id');

        ChatNotification::where('user_id', auth()->id())
            ->whereIn('chat_message_id', $messageIds)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'room' => [
                'type' => 'user',
                'id' => $user->id,
                'title' => $user->name,
            ],
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'room_type' => 'required|in:task,user,global',
            'task_id' => 'nullable|exists:tasks,id',
            'to_user_id' => 'nullable|exists:users,id',
            'reply_to_id' => 'nullable|exists:chat_messages,id',
        ]);

        $roomType = $request->room_type;
        $taskId = $request->task_id;
        $toUserId = $request->to_user_id;

        if ($roomType === 'task' && ! $taskId) {
            return response()->json(['success' => false, 'message' => 'Task chat requires a task id'], 422);
        }

        if ($roomType === 'user' && ! $toUserId) {
            return response()->json(['success' => false, 'message' => 'Direct chat requires a user id'], 422);
        }

        $isGroup = $roomType !== 'user';

        $message = ChatMessage::create([
            'task_id' => $taskId,
            'from_user_id' => auth()->id(),
            'to_user_id' => $toUserId,
            'group' => $isGroup,
            'reply_to_id' => $request->reply_to_id,
            'body' => $request->body,
        ]);

        $recipients = collect();

        if ($isGroup) {
            if ($roomType === 'task') {
                $task = Task::findOrFail($taskId);
                $recipients = $task->users->pluck('id');
            } else {
                $recipients = User::where('role', 'staff')->pluck('id');
            }
        } else {
            $recipients = collect([$toUserId]);
        }

        $recipients = $recipients->filter(function ($userId) {
            return $userId !== auth()->id();
        });

        foreach ($recipients as $recipientId) {
            ChatNotification::create([
                'chat_message_id' => $message->id,
                'user_id' => $recipientId,
                'task_id' => $taskId,
                'is_read' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function notifications()
    {
        $count = ChatNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $items = ChatNotification::with(['message.fromUser'])
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'count' => $count,
            'items' => $items,
        ]);
    }
}
