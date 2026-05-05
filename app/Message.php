<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'body', 'attachment', 'is_read', 'read_at'];

    protected $dates = ['read_at', 'created_at', 'updated_at'];

    protected $casts = ['is_read' => 'boolean'];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetweenUsers($query, $userA, $userB)
    {
        return $query->where(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userA)->where('receiver_id', $userB);
        })->orWhere(function ($q) use ($userA, $userB) {
            $q->where('sender_id', $userB)->where('receiver_id', $userA);
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get a summary of conversations for a user.
     * Returns the latest message per conversation partner, with unread count.
     */
    public static function getConversationList($userId)
    {
        // All user IDs this user has talked to
        $partnerIds = self::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->selectRaw('
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as partner_id
            ', [$userId])
            ->distinct()
            ->pluck('partner_id')
            ->unique()
            ->values();

        $conversations = [];

        foreach ($partnerIds as $partnerId) {
            $lastMsg = self::betweenUsers($userId, $partnerId)
                ->latest()
                ->first();

            $unread = self::where('sender_id', $partnerId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'partner_id' => $partnerId,
                'last_message' => $lastMsg,
                'unread_count' => $unread,
            ];
        }

        // Sort: conversations with unread first, then by latest message
        usort($conversations, function ($a, $b) {
            if ($a['unread_count'] !== $b['unread_count']) {
                return $b['unread_count'] <=> $a['unread_count'];
            }
            $aTime = $a['last_message'] ? $a['last_message']->created_at->timestamp : 0;
            $bTime = $b['last_message'] ? $b['last_message']->created_at->timestamp : 0;
            return $bTime <=> $aTime;
        });

        return $conversations;
    }
}
