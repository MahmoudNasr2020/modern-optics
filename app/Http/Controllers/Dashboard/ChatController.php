<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\Branch;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ─── Online ping (called every 60s from JS) ───────────────────────────────
    // Stores a timestamp in cache so other users can know we're online.

    public function ping()
    {
        cache()->put('chat_online_' . auth()->id(), time(), now()->addMinutes(4));
        return response()->json(['ok' => true]);
    }

    // ─── Main chat page ───────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $me       = auth()->user();
        $branches = Branch::where('is_active', true)->orderBy('is_main', 'desc')->get();

        // Mark self as online
        cache()->put('chat_online_' . $me->id, time(), now()->addMinutes(4));

        $usersQuery = User::where('id', '!=', $me->id)
            ->where('is_active', true)
            ->with('branch');

        if ($request->branch_id) {
            $usersQuery->where('branch_id', $request->branch_id);
        }

        $users = $usersQuery->orderBy('first_name')->get()
            ->map(function ($user) use ($me) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $me->id)
                    ->where('is_read', false)
                    ->count();

                $user->last_message = Message::betweenUsers($me->id, $user->id)
                    ->latest()
                    ->first();

                return $user;
            })->sortByDesc(function ($user) {
                return $user->last_message
                    ? $user->last_message->created_at->timestamp
                    : ($user->unread_count > 0 ? PHP_INT_MAX : 0);
            })->values();

        $chatWith = null;
        if ($request->with) {
            $chatWith = User::find($request->with);
            if ($chatWith) {
                Message::where('sender_id', $chatWith->id)
                    ->where('receiver_id', $me->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true, 'read_at' => now()]);
            }
        }

        return view('dashboard.pages.chat.index', compact('users', 'branches', 'chatWith', 'me'));
    }

    // ─── Get users list (AJAX for branch filter) ──────────────────────────────

    public function getUsers(Request $request)
    {
        $me = auth()->user();

        $usersQuery = User::where('id', '!=', $me->id)
            ->where('is_active', true)
            ->with('branch');

        if ($request->branch_id) {
            $usersQuery->where('branch_id', $request->branch_id);
        }

        $users = $usersQuery->orderBy('first_name')->get()
            ->map(function ($user) use ($me) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $me->id)
                    ->where('is_read', false)
                    ->count();

                $user->last_message = Message::betweenUsers($me->id, $user->id)
                    ->latest()
                    ->first();

                return $user;
            })->sortByDesc(function ($user) {
                return $user->last_message
                    ? $user->last_message->created_at->timestamp
                    : ($user->unread_count > 0 ? PHP_INT_MAX : 0);
            })->values();

        return response()->json($users->map(function ($user) use ($me) {
            // Use cache-based online status (set via /chat/ping every 60s)
            $onlineTs = cache()->get('chat_online_' . $user->id);
            $now      = time();
            $isOnline = $onlineTs && ($now - $onlineTs) <= 180;   // active in last 3 min
            $isAway   = !$isOnline && $onlineTs && ($now - $onlineTs) <= 3600; // last hour

            $lastBody = null;
            if ($user->last_message) {
                $lastBody = $user->last_message->body
                    ?: ($user->last_message->attachment ? '📷 صورة' : '');
            }

            return [
                'id'             => $user->id,
                'name'           => $user->full_name,
                'first_name'     => $user->first_name ?? '',
                'last_name'      => $user->last_name  ?? '',
                'avatar'         => $user->image_path,
                'branch'         => optional($user->branch)->name,
                'online'         => $isOnline,
                'away'           => $isAway,
                'unread_count'   => $user->unread_count,
                'last_msg_body'  => $lastBody,
                'last_msg_mine'  => $user->last_message
                    ? ($user->last_message->sender_id === $me->id) : false,
                'last_msg_time'  => $user->last_message
                    ? $user->last_message->created_at->format('H:i') : null,
            ];
        }));
    }

    // ─── Get messages between me and another user (AJAX polling) ─────────────

    public function getMessages(Request $request, $userId)
    {
        $me      = auth()->user();
        $partner = User::findOrFail($userId);

        // Mark their messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $afterId = $request->after_id ?? 0;

        $messages = Message::betweenUsers($me->id, $userId)
            ->where('id', '>', $afterId)
            ->with(['sender:id,first_name,last_name,image'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($me) {
                return [
                    'id'         => $msg->id,
                    'body'       => $msg->body,
                    'attachment' => $msg->attachment
                        ? asset('storage/uploads/chat/' . $msg->attachment)
                        : null,
                    'is_mine'    => $msg->sender_id === $me->id,
                    'is_read'    => (bool) $msg->is_read,
                    'sender'     => $msg->sender->first_name . ' ' . $msg->sender->last_name,
                    'avatar'     => $msg->sender->image_path,
                    'created_at' => $msg->created_at->format('H:i'),
                    'date'       => $msg->created_at->format('Y-m-d'),
                    'time_ago'   => $msg->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'messages'       => $messages,
            'partner_name'   => $partner->full_name,
            'partner_avatar' => $partner->image_path,
        ]);
    }

    // ─── Send a message ───────────────────────────────────────────────────────

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'nullable|string|max:2000',
            // Use mimes instead of image rule — more permissive with FormData uploads
            'attachment'  => 'nullable|mimes:jpg,jpeg,png,gif,webp,bmp|max:10240',
        ]);

        $body    = trim($request->body ?? '');
        $hasFile = $request->hasFile('attachment');

        if (!$body && !$hasFile) {
            return response()->json(['error' => 'الرسالة أو الصورة مطلوبة'], 422);
        }

        $me = auth()->user();

        if ($request->receiver_id == $me->id) {
            return response()->json(['error' => 'لا يمكنك إرسال رسالة لنفسك'], 422);
        }

        $attachmentPath = null;
        if ($hasFile) {
            $file     = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . strtolower($file->getClientOriginalExtension());
            $file->storeAs('uploads/chat', $filename, 'public');
            $attachmentPath = $filename;
        }

        $message = Message::create([
            'sender_id'   => $me->id,
            'receiver_id' => $request->receiver_id,
            'body'        => $body,
            'attachment'  => $attachmentPath,
            'is_read'     => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id'         => $message->id,
                'body'       => $message->body,
                'attachment' => $attachmentPath
                    ? asset('storage/uploads/chat/' . $attachmentPath)
                    : null,
                'is_mine'    => true,
                'is_read'    => false,
                'sender'     => $me->full_name,
                'avatar'     => $me->image_path,
                'created_at' => $message->created_at->format('H:i'),
                'date'       => $message->created_at->format('Y-m-d'),
                'time_ago'   => $message->created_at->diffForHumans(),
            ],
        ]);
    }

    // ─── Read status for sent messages (for blue ticks) ──────────────────────

    public function readStatus($userId)
    {
        $me     = auth()->user();
        $readIds = Message::where('sender_id', $me->id)
            ->where('receiver_id', $userId)
            ->where('is_read', true)
            ->orderBy('id', 'desc')
            ->take(200)
            ->pluck('id')
            ->toArray();

        return response()->json(['read_ids' => $readIds]);
    }

    // ─── Total unread count (for navbar badge) ────────────────────────────────

    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    // ─── Mark conversation as read ────────────────────────────────────────────

    public function markRead($userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
