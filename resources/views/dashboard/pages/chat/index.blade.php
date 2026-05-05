@extends('dashboard.layouts.master')
@section('title', '| الرسائل الداخلية')

@section('styles')
<style>
/* ══════════════════════════════════════════════════
   CHAT v3 — Premium Redesign
══════════════════════════════════════════════════ */
.chat-wrap {
    display: flex;
    height: calc(100vh - 108px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 50px rgba(0,0,0,.15);
    margin: 0 0 24px;
    background: #e8edf5;
}

/* ── SIDEBAR ─────────────────────────────────── */
.chat-sidebar {
    width: 315px; min-width: 260px;
    background: #fff;
    border-right: 1px solid #e4e9f2;
    display: flex; flex-direction: column; flex-shrink: 0;
}
.chat-sidebar-header {
    padding: 18px 16px 14px;
    background: linear-gradient(150deg, #1e1b4b 0%, #312e81 100%);
    flex-shrink: 0;
}
.chat-sidebar-header h3 {
    margin: 0 0 12px; font-size: 15px; font-weight: 800;
    color: #fff; display: flex; align-items: center; gap: 8px;
}
.chat-search { position: relative; }
.chat-search input {
    width: 100%; box-sizing: border-box;
    padding: 9px 36px 9px 13px; border: none;
    border-radius: 10px; background: rgba(255,255,255,.14);
    color: #fff; font-size: 13px; outline: none; transition: background .2s;
}
.chat-search input::placeholder { color: rgba(255,255,255,.55); }
.chat-search input:focus        { background: rgba(255,255,255,.24); }
.chat-search i {
    position: absolute; right: 11px; top: 50%;
    transform: translateY(-50%); color: rgba(255,255,255,.55); font-size: 13px;
}
.chat-branch-bar {
    padding: 9px 12px; border-bottom: 1px solid #eef2f8;
    background: #f8faff; flex-shrink: 0;
}
.chat-branch-bar select {
    width: 100%; padding: 7px 10px; border: 1.5px solid #e2e8f0;
    border-radius: 8px; font-size: 12px; color: #475569;
    background: #fff; outline: none; cursor: pointer;
}
.chat-branch-bar select:focus { border-color: #6366f1; }

.chat-users-list { flex: 1; overflow-y: auto; padding: 6px 0; }
.chat-users-list::-webkit-scrollbar { width: 3px; }
.chat-users-list::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

.chat-user-item {
    display: flex; align-items: center; gap: 11px;
    padding: 10px 15px; cursor: pointer; transition: background .15s;
    border-left: 3px solid transparent; position: relative;
}
.chat-user-item:hover  { background: #f5f7ff; }
.chat-user-item.active { background: #eeefff; border-left-color: #6366f1; }
.chat-user-item.active .chat-user-name { color: #4338ca; font-weight: 800; }

.chat-avatar-wrap { position: relative; flex-shrink: 0; }
.chat-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    object-fit: cover; border: 2.5px solid #e8ecf5; display: block;
}
.chat-user-item.active .chat-avatar { border-color: #6366f1; }
.chat-online-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 12px; height: 12px; border-radius: 50%;
    border: 2.5px solid #fff; background: #d1d5db;
}
.chat-online-dot.online { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.2); }
.chat-online-dot.away   { background: #f59e0b; }

.chat-user-info { flex: 1; min-width: 0; }
.chat-user-name {
    font-size: 13.5px; font-weight: 700; color: #1e293b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.chat-user-branch {
    font-size: 11px; color: #94a3b8;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px;
}
.chat-user-preview {
    font-size: 11.5px; color: #64748b;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;
}
.chat-user-meta {
    display: flex; flex-direction: column;
    align-items: flex-end; gap: 4px; flex-shrink: 0;
}
.chat-user-time { font-size: 10.5px; color: #94a3b8; }
.chat-unread-badge {
    background: #ef4444; color: #fff;
    font-size: 10px; font-weight: 800;
    padding: 2px 7px; border-radius: 20px;
    min-width: 20px; text-align: center;
    box-shadow: 0 2px 6px rgba(239,68,68,.35);
}

/* ── MAIN PANEL ──────────────────────────────── */
.chat-main {
    flex: 1; display: flex; flex-direction: column;
    min-width: 0; background: #f1f4fb; position: relative;
}
.chat-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: #94a3b8; gap: 14px; padding: 40px;
}
.chat-empty-icon {
    width: 90px; height: 90px;
    background: linear-gradient(135deg,#e0e7ff,#f5f3ff);
    border-radius: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; color: #a5b4fc;
}
.chat-empty h4 { font-size: 18px; font-weight: 700; color: #475569; margin: 0; }
.chat-empty p  { font-size: 13px; margin: 0; text-align: center; line-height: 1.6; }

#chatPanel {
    display: flex; flex-direction: column; flex: 1; min-height: 0;
}

/* Header */
.chat-header {
    padding: 13px 20px; background: #fff;
    border-bottom: 1px solid #e8ecf5;
    display: flex; align-items: center; gap: 13px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05); flex-shrink: 0;
}
.chat-header-avatar-wrap { position: relative; flex-shrink: 0; }
.chat-header-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    object-fit: cover; border: 2.5px solid #e0e7ff; display: block;
}
.chat-header-online-dot {
    position: absolute; bottom: 2px; right: 2px;
    width: 13px; height: 13px; border-radius: 50%;
    border: 2.5px solid #fff; background: #d1d5db;
}
.chat-header-online-dot.online { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.2); }
.chat-header-online-dot.away   { background: #f59e0b; }
.chat-header-info { flex: 1; min-width: 0; }
.chat-header-name   { font-size: 15.5px; font-weight: 800; color: #1e293b; }
.chat-header-status { font-size: 12px; color: #94a3b8; margin-top: 1px; }
.chat-header-branch {
    font-size: 11.5px; background: #eff0ff; color: #4338ca;
    padding: 4px 12px; border-radius: 20px; font-weight: 700; flex-shrink: 0;
}

/* Messages area */
.chat-messages {
    flex: 1; overflow-y: auto;
    padding: 20px 22px;
    display: flex; flex-direction: column; gap: 2px;
    scroll-behavior: smooth;
}
.chat-messages::-webkit-scrollbar { width: 5px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: #c8cfe0; border-radius: 4px; }

.chat-date-divider { text-align: center; margin: 14px 0 10px; }
.chat-date-divider span {
    background: #dde2ef; color: #64748b;
    font-size: 11px; font-weight: 700;
    padding: 4px 16px; border-radius: 20px; letter-spacing: .3px;
}

/* Message rows */
.chat-msg-row {
    display: flex; align-items: flex-end; gap: 8px;
    margin-bottom: 3px;
}
.chat-msg-row.mine { flex-direction: row-reverse; }

/* Group: reduce gap for consecutive same-sender messages */
.chat-msg-row + .chat-msg-row:not(.mine):not(:has(+ .chat-msg-row.mine)) { margin-bottom: 1px; }

.chat-msg-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0; align-self: flex-end;
    border: 1.5px solid #e2e8f0;
}
.chat-msg-avatar-spacer { width: 30px; flex-shrink: 0; }

/* ★ THE KEY FIX: wrap limits max-width, bubble sizes to content */
.chat-bubble-wrap {
    max-width: 64%;
    display: flex; flex-direction: column; align-items: flex-start;
}
.chat-msg-row.mine .chat-bubble-wrap { align-items: flex-end; }

.chat-bubble {
    border-radius: 20px; overflow: hidden;
    font-size: 14.5px; line-height: 1.6;
    word-break: break-word; max-width: 100%;
    position: relative;
}
.chat-bubble-text {
    padding: 10px 16px;
    white-space: pre-wrap;
    min-width: 60px;
}
/* Received */
.chat-msg-row:not(.mine) .chat-bubble {
    background: #ffffff;
    box-shadow: 0 1px 6px rgba(0,0,0,.08);
    border-bottom-left-radius: 5px;
    border-left: 3px solid #e0e7ff;
}
.chat-msg-row:not(.mine) .chat-bubble .chat-bubble-text { color: #1e293b; }

/* Sent */
.chat-msg-row.mine .chat-bubble {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    box-shadow: 0 3px 16px rgba(79,70,229,.38);
    border-bottom-right-radius: 5px;
}
.chat-msg-row.mine .chat-bubble .chat-bubble-text { color: #fff; }

/* Image in bubble */
.chat-img-container img {
    display: block; max-width: 260px; max-height: 240px;
    width: auto; height: auto; cursor: zoom-in; transition: opacity .2s;
}
.chat-img-container img:hover { opacity: .88; }
.chat-img-container + .chat-bubble-text {
    border-top: 1px solid rgba(255,255,255,.2); padding-top: 8px;
}
.chat-msg-row:not(.mine) .chat-img-container + .chat-bubble-text {
    border-top-color: rgba(0,0,0,.07);
}
.chat-bubble.img-only {
    background: transparent !important;
    box-shadow: none !important;
    border: none !important;
}
.chat-bubble.img-only img {
    border-radius: 16px;
    box-shadow: 0 3px 16px rgba(0,0,0,.16);
}

/* Timestamp + ticks */
.chat-msg-time {
    font-size: 10.5px; color: #94a3b8;
    margin-top: 4px; padding: 0 4px;
    display: flex; align-items: center; gap: 3px;
}
.chat-msg-row.mine .chat-msg-time { flex-direction: row-reverse; color: #9b96c8; }

/* Read receipt ticks */
.msg-tick { display: inline-flex; align-items: center; font-size: 13px; }
.msg-tick.read   i { color: #22c55e; filter: drop-shadow(0 0 3px rgba(34,197,94,.5)); }
.msg-tick.unread i { color: #94a3b8; }

/* Loading */
.chat-loading {
    display: flex; align-items: center; justify-content: center;
    flex: 1; color: #94a3b8; font-size: 13px; gap: 8px; padding: 40px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; display: inline-block; }

/* ── New message floating button ── */
.chat-msgs-wrap {
    flex: 1; min-height: 0; position: relative;
    display: flex; flex-direction: column;
}
#newMsgBtn {
    position: absolute; bottom: 14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg,#22c55e,#16a34a);
    color: #fff; border: none; border-radius: 50px;
    padding: 8px 20px; font-size: 13px; font-weight: 700;
    cursor: pointer; display: none; align-items: center; gap: 7px;
    box-shadow: 0 4px 18px rgba(34,197,94,.45);
    animation: popIn .22s cubic-bezier(.34,1.56,.64,1);
    z-index: 20; white-space: nowrap;
}
#newMsgBtn:hover { filter: brightness(1.08); transform: translateX(-50%) scale(1.04); }
@keyframes popIn {
    from { opacity:0; transform: translateX(-50%) scale(.8) translateY(10px); }
    to   { opacity:1; transform: translateX(-50%) scale(1) translateY(0); }
}
#newMsgBadge {
    background: rgba(255,255,255,.28); border-radius: 20px;
    padding: 1px 8px; font-size: 11px; font-weight: 800;
    display: none;
}

/* ── INPUT AREA ────────────────────────────── */
.chat-input-area {
    padding: 10px 16px 14px; background: #fff;
    border-top: 1px solid #e8ecf5; flex-shrink: 0;
    position: relative;
}
#filePreviewArea {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 12px; margin-bottom: 8px;
    background: #f0f3ff; border-radius: 12px;
    border: 1.5px dashed #818cf8;
}
#filePreviewImg {
    width: 58px; height: 58px; object-fit: cover;
    border-radius: 9px; border: 2px solid #6366f1; flex-shrink: 0;
}
.fp-info { flex: 1; min-width: 0; }
.fp-label { font-size: 11px; font-weight: 700; color: #4338ca; }
.fp-name  { font-size: 11px; color: #6366f1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fp-rm {
    background: none; border: none; color: #ef4444;
    font-size: 20px; cursor: pointer; padding: 2px 6px;
    border-radius: 6px; transition: background .15s; flex-shrink: 0; line-height: 1;
}
.fp-rm:hover { background: #fee2e2; }

/* Emoji picker */
#emojiPicker {
    position: absolute; bottom: calc(100% + 6px); left: 16px;
    width: 308px; background: #fff; border-radius: 18px;
    box-shadow: 0 14px 44px rgba(0,0,0,.18);
    border: 1px solid #e2e8f0; z-index: 1000; overflow: hidden;
}
.emoji-tabs {
    display: flex; border-bottom: 1px solid #f0f4f8;
    padding: 6px 8px 0; background: #f8faff;
    overflow-x: auto;
}
.emoji-tabs::-webkit-scrollbar { display: none; }
.emoji-tab {
    flex-shrink: 0; background: none; border: none;
    padding: 6px 10px; font-size: 18px; cursor: pointer;
    border-radius: 8px 8px 0 0; transition: background .15s;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
}
.emoji-tab:hover  { background: #eff0ff; }
.emoji-tab.active { border-bottom-color: #6366f1; background: #eff0ff; }
.emoji-grid {
    display: grid; grid-template-columns: repeat(9,1fr);
    gap: 1px; padding: 8px 6px; max-height: 185px; overflow-y: auto;
}
.emoji-grid::-webkit-scrollbar { width: 3px; }
.emoji-grid::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
.emoji-btn {
    background: none; border: none; font-size: 21px;
    cursor: pointer; padding: 4px; border-radius: 8px;
    transition: background .1s, transform .1s; text-align: center; line-height: 1;
}
.emoji-btn:hover { background: #eff0ff; transform: scale(1.2); }

/* Input row */
.chat-input-row { display: flex; align-items: flex-end; gap: 9px; }
.chat-tool-btn {
    width: 40px; height: 40px; flex-shrink: 0; border: none;
    background: #f0f3ff; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .15s, transform .15s;
}
.chat-tool-btn:hover { background: #e0e7ff; transform: scale(1.08); }
.chat-input-wrap { flex: 1; }
.chat-input {
    width: 100%; box-sizing: border-box;
    min-height: 44px; max-height: 120px;
    padding: 11px 18px; border: 2px solid #e2e8f0;
    border-radius: 24px; font-size: 14px; color: #1e293b;
    background: #f8faff; resize: none; outline: none;
    font-family: inherit; line-height: 1.45; overflow-y: auto;
    transition: border-color .2s, background .2s; display: block;
}
.chat-input:focus    { border-color: #6366f1; background: #fff; }
.chat-input:disabled { opacity: .5; cursor: not-allowed; }
.chat-send-btn {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 50%; border: none;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; font-size: 19px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
    box-shadow: 0 4px 16px rgba(99,102,241,.42);
}
.chat-send-btn:hover    { transform: scale(1.1); box-shadow: 0 6px 22px rgba(99,102,241,.55); }
.chat-send-btn:disabled { opacity: .45; cursor: not-allowed; transform: none; box-shadow: none; }

/* Lightbox */
#imgLightbox {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.9); z-index: 999999;
    display: flex; align-items: center; justify-content: center;
    cursor: zoom-out; animation: lbFade .2s ease;
}
@keyframes lbFade { from { opacity:0; } to { opacity:1; } }
#imgLightbox img {
    max-width: 92vw; max-height: 92vh;
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(0,0,0,.55);
    animation: lbZoom .22s ease;
}
@keyframes lbZoom { from { transform: scale(.9); } to { transform: scale(1); } }
.lb-close {
    position: absolute; top: 16px; right: 20px;
    background: rgba(255,255,255,.12); border: none; color: #fff;
    font-size: 24px; cursor: pointer; width: 46px; height: 46px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    transition: background .2s;
}
.lb-close:hover { background: rgba(255,255,255,.28); }

@media (max-width: 768px) {
    .chat-sidebar      { width: 230px; min-width: 190px; }
    .chat-bubble-wrap  { max-width: 82%; }
    #emojiPicker       { width: 265px; }
}
</style>
@endsection

@section('content')
<div style="padding:0 20px 20px;">

{{-- Page title --}}
<div style="padding:16px 0 12px;display:flex;align-items:center;justify-content:space-between;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:23px;color:#fff;box-shadow:0 6px 18px rgba(79,70,229,.38);">
            <i class="bi bi-chat-dots-fill"></i>
        </div>
        <div>
            <h2 style="margin:0;font-size:20px;font-weight:900;color:#1e293b;">الرسائل الداخلية</h2>
            <p style="margin:0;font-size:12px;color:#94a3b8;">Internal Team Chat</p>
        </div>
    </div>
    <span style="background:#eff0ff;color:#4338ca;padding:5px 15px;border-radius:20px;font-size:12px;font-weight:700;">
        <i class="bi bi-people-fill"></i> {{ $users->count() }} موظف
    </span>
</div>

{{-- Chat Layout --}}
<div class="chat-wrap">

    {{-- ═══ SIDEBAR ═══ --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h3><i class="bi bi-chat-square-dots-fill"></i> المحادثات</h3>
            <div class="chat-search">
                <input type="text" id="userSearch" placeholder="ابحث باسم الموظف…" autocomplete="off">
                <i class="bi bi-search"></i>
            </div>
        </div>

        @if($branches->count() > 1)
        <div class="chat-branch-bar">
            <select id="branchFilter">
                <option value="">🏢 كل الفروع</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->is_main ? '⭐ ' : '' }}{{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="chat-users-list" id="usersList">
            @forelse($users as $user)
            @php
                $onlineTs = cache()->get('chat_online_' . $user->id);
                $now      = time();
                $isOnline = $onlineTs && ($now - $onlineTs) <= 180;
                $isAway   = !$isOnline && $onlineTs && ($now - $onlineTs) <= 3600;
                $dot      = $isOnline ? 'online' : ($isAway ? 'away' : '');
                $initials = strtoupper(substr($user->first_name??'U',0,1).substr($user->last_name??'',0,1));
                $lastPreview = $user->last_message
                    ? ($user->last_message->body ?: ($user->last_message->attachment ? '📷 صورة' : ''))
                    : '';
            @endphp
            <div class="chat-user-item {{ $chatWith && $chatWith->id == $user->id ? 'active' : '' }}"
                 data-user-id="{{ $user->id }}"
                 data-name="{{ strtolower($user->full_name) }}"
                 data-online="{{ $isOnline ? '1' : '0' }}"
                 data-away="{{ $isAway ? '1' : '0' }}"
                 onclick="openChat({{ $user->id }},'{{ addslashes($user->full_name) }}','{{ $user->image_path }}','{{ $user->branch ? addslashes($user->branch->name) : '' }}')">

                <div class="chat-avatar-wrap">
                    <img src="{{ $user->image_path }}" class="chat-avatar" alt="{{ $user->full_name }}"
                         onerror="this.onerror=null;this.src='{{ url('avatar/'.urlencode($initials)) }}'">
                    <span class="chat-online-dot {{ $dot }}"></span>
                </div>

                <div class="chat-user-info">
                    <div class="chat-user-name">{{ $user->full_name }}</div>
                    @if($user->branch)
                        <div class="chat-user-branch"><i class="bi bi-building" style="font-size:9px;"></i> {{ $user->branch->name }}</div>
                    @endif
                    <div class="chat-user-preview" id="preview-{{ $user->id }}">
                        @if($lastPreview)
                            {{ $user->last_message->sender_id == $me->id ? 'أنت: ' : '' }}{{ Str::limit($lastPreview, 28) }}
                        @else
                            <span style="color:#c0c0c0;font-style:italic;">لا توجد رسائل بعد</span>
                        @endif
                    </div>
                </div>

                <div class="chat-user-meta">
                    <span class="chat-user-time" id="time-{{ $user->id }}">
                        {{ $user->last_message ? $user->last_message->created_at->format('H:i') : '' }}
                    </span>
                    <span class="chat-unread-badge" id="badge-{{ $user->id }}"
                          style="{{ $user->unread_count > 0 ? '' : 'display:none;' }}">
                        {{ $user->unread_count }}
                    </span>
                </div>
            </div>
            @empty
            <div style="padding:40px 20px;text-align:center;color:#94a3b8;">
                <i class="bi bi-person-x" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                لا يوجد موظفون
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══ MAIN AREA ═══ --}}
    <div class="chat-main">

        {{-- Empty state --}}
        <div class="chat-empty" id="chatEmptyState" style="{{ $chatWith ? 'display:none;' : '' }}">
            <div class="chat-empty-icon"><i class="bi bi-chat-square-text"></i></div>
            <h4>اختر محادثة للبدء</h4>
            <p>انقر على اسم أي موظف من القائمة<br>لفتح المحادثة معه</p>
        </div>

        {{-- Chat panel --}}
        <div id="chatPanel" style="{{ $chatWith ? 'display:flex;' : 'display:none;' }}flex-direction:column;flex:1;min-height:0;">

            {{-- Header --}}
            <div class="chat-header">
                <div class="chat-header-avatar-wrap">
                    <img id="chatHeaderAvatar"
                         src="{{ $chatWith ? $chatWith->image_path : '' }}"
                         class="chat-header-avatar" alt=""
                         @if($chatWith)
                         onerror="this.onerror=null;this.src='{{ url('avatar/'.urlencode(strtoupper(substr($chatWith->first_name??'U',0,1).substr($chatWith->last_name??'',0,1)))) }}'"
                         @endif>
                    @php
                        $hwTs     = $chatWith ? cache()->get('chat_online_' . $chatWith->id) : null;
                        $hwNow    = time();
                        $hwOnline = $hwTs && ($hwNow - $hwTs) <= 180;
                        $hwAway   = !$hwOnline && $hwTs && ($hwNow - $hwTs) <= 3600;
                    @endphp
                    <span id="chatHeaderDot" class="chat-header-online-dot {{ $hwOnline ? 'online' : ($hwAway ? 'away' : '') }}"></span>
                </div>
                <div class="chat-header-info">
                    <div class="chat-header-name" id="chatHeaderName">{{ $chatWith ? $chatWith->full_name : '' }}</div>
                    <div class="chat-header-status" id="chatHeaderStatus">
                        @if($chatWith)
                            @if($hwOnline)
                                <span style="color:#22c55e;font-weight:700;">
                                    <i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i> متصل الآن
                                </span>
                            @elseif($hwAway)
                                <span style="color:#f59e0b;font-weight:700;">
                                    <i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i> بعيد
                                </span>
                            @else
                                <span style="color:#94a3b8;">غير متصل</span>
                            @endif
                        @endif
                    </div>
                </div>
                <span class="chat-header-branch" id="chatHeaderBranch"
                      style="{{ ($chatWith && $chatWith->branch) ? '' : 'display:none;' }}">
                    {{ $chatWith && $chatWith->branch ? $chatWith->branch->name : '' }}
                </span>
            </div>

            {{-- Messages wrapper (position:relative so button can anchor to it) --}}
            <div class="chat-msgs-wrap">
                <div class="chat-messages" id="chatMessages">
                    @if($chatWith)
                    <div class="chat-loading">
                        <i class="bi bi-arrow-repeat spin" style="font-size:22px;color:#6366f1;"></i>
                        جاري تحميل الرسائل…
                    </div>
                    @endif
                </div>

                {{-- Floating "new messages" button (WhatsApp-style) --}}
                <button id="newMsgBtn" onclick="scrollToLatest()" title="رسائل جديدة">
                    <span id="newMsgBadge"></span>
                    <i class="bi bi-chevron-down" style="font-size:15px;"></i>
                    رسائل جديدة
                </button>
            </div>

            {{-- Input --}}
            <div class="chat-input-area">

                {{-- File preview --}}
                <div id="filePreviewArea" style="display:none;">
                    <img id="filePreviewImg" src="" alt="">
                    <div class="fp-info">
                        <div class="fp-label">📎 صورة مرفقة</div>
                        <div class="fp-name" id="fpName"></div>
                    </div>
                    <button class="fp-rm" onclick="clearFile()" title="إزالة">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>

                {{-- Emoji picker --}}
                <div id="emojiPicker" style="display:none;"></div>

                {{-- Row --}}
                <div class="chat-input-row">
                    <button class="chat-tool-btn" id="emojiBtn" onclick="toggleEmoji()" title="إيموجي">
                        <i class="bi bi-emoji-smile-fill" style="font-size:20px;color:#f59e0b;"></i>
                    </button>
                    <button class="chat-tool-btn" onclick="document.getElementById('chatFileInput').click()" title="إرسال صورة">
                        <i class="bi bi-image-fill" style="font-size:19px;color:#6366f1;"></i>
                    </button>
                    <input type="file" id="chatFileInput" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp"
                           style="display:none;" onchange="previewFile(this)">
                    <div class="chat-input-wrap">
                        <textarea class="chat-input" id="chatInput"
                                  placeholder="اكتب رسالتك… (Enter = إرسال،  Shift+Enter = سطر جديد)"
                                  rows="1"
                                  {{ !$chatWith ? 'disabled' : '' }}></textarea>
                    </div>
                    <button class="chat-send-btn" id="sendBtn" onclick="sendMessage()"
                            {{ !$chatWith ? 'disabled' : '' }}>
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </div>

        </div>{{-- /chatPanel --}}
    </div>{{-- /chat-main --}}
</div>{{-- /chat-wrap --}}
</div>

{{-- Lightbox --}}
<div id="imgLightbox" style="display:none;" onclick="closeLb()">
    <button class="lb-close" onclick="event.stopPropagation();closeLb()">
        <i class="bi bi-x-lg"></i>
    </button>
    <img id="lbImg" src="" alt="" onclick="event.stopPropagation()">
</div>
@endsection

@section('scripts')
<script>
// ══ CONFIG ══════════════════════════════════════════
const ME_ID      = {{ $me->id }};
const INIT_WITH  = {{ $chatWith ? $chatWith->id : 'null' }};
const URL_MSGS   = '{{ url("dashboard/chat/messages") }}';
const URL_SEND   = '{{ route("dashboard.chat.send") }}';
const URL_UNREAD = '{{ route("dashboard.chat.unread-count") }}';
const URL_USERS  = '{{ route("dashboard.chat.users") }}';
const URL_RS     = '{{ url("dashboard/chat/read-status") }}'; // read-status
const URL_AVT    = '{{ url("avatar") }}';
const CSRF       = '{{ csrf_token() }}';

let currentUser  = INIT_WITH;
let lastMsgId    = 0;
let pollInterval = null;
let rsInterval   = null;   // read-status interval
let sbInterval   = null;   // sidebar refresh interval
let emojiOpen    = false;
let selFile      = null;
let newMsgCount  = 0;      // pending new-message count (shown on floating button)

// ══ NEW-MESSAGE FLOATING BUTTON ═════════════════════
function isNearBottom() {
    const c = document.getElementById('chatMessages');
    if (!c) return true;
    return c.scrollHeight - c.scrollTop - c.clientHeight < 120;
}
function showNewMsgBtn(count) {
    newMsgCount += count;
    const btn   = document.getElementById('newMsgBtn');
    const badge = document.getElementById('newMsgBadge');
    if (!btn) return;
    if (badge) { badge.textContent = newMsgCount; badge.style.display = ''; }
    btn.style.display = 'flex';
}
function hideNewMsgBtn() {
    newMsgCount = 0;
    const btn   = document.getElementById('newMsgBtn');
    const badge = document.getElementById('newMsgBadge');
    if (btn)   btn.style.display   = 'none';
    if (badge) badge.style.display = 'none';
}
function scrollToLatest() {
    const c = document.getElementById('chatMessages');
    if (c) c.scrollTop = c.scrollHeight;
    hideNewMsgBtn();
}

// ══ OPEN CHAT ═══════════════════════════════════════
function openChat(userId, name, avatar, branch) {
    if (currentUser === userId) return;
    currentUser = userId; lastMsgId = 0;
    hideNewMsgBtn();

    // URL without reload
    const u = new URL(window.location);
    u.searchParams.set('with', userId);
    window.history.pushState({}, '', u);

    // Sidebar highlight
    document.querySelectorAll('.chat-user-item').forEach(el => el.classList.remove('active'));
    const item = document.querySelector(`.chat-user-item[data-user-id="${userId}"]`);
    if (item) item.classList.add('active');

    // Read online status from DOM attributes
    const online = item && item.dataset.online === '1';
    const away   = item && item.dataset.away   === '1';

    // Clear badge
    const badge = document.getElementById('badge-' + userId);
    if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }

    // Show panel
    document.getElementById('chatEmptyState').style.display = 'none';
    document.getElementById('chatPanel').style.display = 'flex';

    // Update header
    _setHeader(name, avatar, branch, online, away);

    // Clear messages
    document.getElementById('chatMessages').innerHTML =
        '<div class="chat-loading"><i class="bi bi-arrow-repeat spin" style="font-size:22px;color:#6366f1;"></i> جاري التحميل…</div>';

    // Enable input
    const inp = document.getElementById('chatInput');
    const btn = document.getElementById('sendBtn');
    if (inp) { inp.disabled = false; inp.focus(); }
    if (btn) btn.disabled = false;

    closeEmoji(); clearFile();

    // Reset polls
    if (pollInterval) clearInterval(pollInterval);
    if (rsInterval)   clearInterval(rsInterval);
    loadMessages(userId, true);
    pollInterval = setInterval(() => loadMessages(userId, false), 3000);
    rsInterval   = setInterval(() => checkReadStatus(userId),     5000);
}

function _setHeader(name, avatar, branch, online, away) {
    const avEl  = document.getElementById('chatHeaderAvatar');
    const dotEl = document.getElementById('chatHeaderDot');
    const nmEl  = document.getElementById('chatHeaderName');
    const stEl  = document.getElementById('chatHeaderStatus');
    const brEl  = document.getElementById('chatHeaderBranch');

    const inits = name.split(' ').filter(Boolean).map(w=>w[0]).join('').toUpperCase().slice(0,2);
    avEl.src = avatar;
    avEl.onerror = function(){ this.onerror=null; this.src=URL_AVT+'/'+encodeURIComponent(inits); };

    if (online) {
        dotEl.className = 'chat-header-online-dot online';
        stEl.innerHTML  = '<span style="color:#22c55e;font-weight:700;"><i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i> متصل الآن</span>';
    } else if (away) {
        dotEl.className = 'chat-header-online-dot away';
        stEl.innerHTML  = '<span style="color:#f59e0b;font-weight:700;"><i class="bi bi-circle-fill" style="font-size:7px;vertical-align:middle;"></i> بعيد</span>';
    } else {
        dotEl.className = 'chat-header-online-dot';
        stEl.innerHTML  = '<span style="color:#94a3b8;">غير متصل</span>';
    }
    nmEl.textContent = name;
    if (branch) { brEl.textContent = branch; brEl.style.display = ''; }
    else        { brEl.style.display = 'none'; }
}

// ══ LOAD MESSAGES ═══════════════════════════════════
function loadMessages(userId, initial) {
    fetch(`${URL_MSGS}/${userId}?after_id=${lastMsgId}`,
        { headers: {'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json())
    .then(data => {
        const c = document.getElementById('chatMessages');
        if (!c || currentUser !== userId) return;

        if (initial) c.innerHTML = '';

        if (!data.messages.length && initial) {
            c.innerHTML = `<div class="chat-empty" style="flex:1;">
                <div class="chat-empty-icon" style="width:72px;height:72px;font-size:30px;">
                    <i class="bi bi-chat-heart"></i></div>
                <h4 style="font-size:15px;">ابدأ المحادثة</h4>
                <p>أرسل رسالة أو صورة الآن</p></div>`;
            return;
        }

        if (data.messages.length) {
            lastMsgId = data.messages[data.messages.length - 1].id;

            if (initial) {
                // ── Initial load: render all with date dividers, force scroll ──
                let lastDate = null;
                data.messages.forEach(msg => {
                    if (msg.date !== lastDate) {
                        lastDate = msg.date;
                        c.insertAdjacentHTML('beforeend',
                            `<div class="chat-date-divider"><span>${_fd(msg.date)}</span></div>`);
                    }
                    c.insertAdjacentHTML('beforeend', buildBubble(msg));
                });
                hideNewMsgBtn();
                scrollAfterImgs(c, true);
            } else {
                // ── Polling: smart scroll / floating button ──
                let newFromOther = 0;
                data.messages.forEach(msg => {
                    c.insertAdjacentHTML('beforeend', buildBubble(msg));
                    updatePreview(userId, msg);
                    if (!msg.is_mine) newFromOther++;
                });
                // Play notification sound for messages from others
                if (newFromOther > 0 && typeof window.playMsgSound === 'function') {
                    window.playMsgSound();
                }
                if (isNearBottom()) {
                    // User is already at the bottom — just scroll
                    scrollAfterImgs(c, false);
                    hideNewMsgBtn();
                } else if (newFromOther > 0) {
                    // User scrolled up — show floating indicator instead
                    showNewMsgBtn(newFromOther);
                }
                // Own sent messages always scroll (they came from sendMessage which already scrolls)
            }
        }
    }).catch(() => {});
}

// ══ BUILD BUBBLE ════════════════════════════════════
function buildBubble(msg) {
    const mine = msg.is_mine;
    const inits = (msg.sender||'??').split(' ').filter(Boolean).map(w=>w[0]).join('').toUpperCase().slice(0,2);
    const avSrc  = msg.avatar || `${URL_AVT}/${encodeURIComponent(inits)}`;

    // Avatar (only for received messages)
    const avHtml = !mine
        ? `<img src="${avSrc}" class="chat-msg-avatar" alt=""
               onerror="this.onerror=null;this.src='${URL_AVT}/${encodeURIComponent(inits)}'">`
        : `<div class="chat-msg-avatar-spacer"></div>`;

    // Content
    let content = '';
    if (msg.attachment) {
        content += `<div class="chat-img-container">
            <img src="${msg.attachment}" alt="صورة"
                 onclick="openLb('${msg.attachment}')"
                 onload="_imgLoaded(this)"
                 onerror="this.style.display='none'">
        </div>`;
    }
    if (msg.body) {
        content += `<div class="chat-bubble-text">${esc(msg.body)}</div>`;
    }

    const imgOnly = (msg.attachment && !msg.body) ? 'img-only' : '';

    // Read receipt tick (only for sent messages)
    let tickHtml = '';
    if (mine) {
        const readCls = msg.is_read ? 'read' : 'unread';
        const icon    = 'bi bi-check2-all';
        tickHtml = `<span class="msg-tick ${readCls}" data-msg-id="${msg.id}">
            <i class="${icon}" style="font-size:13px;"></i>
        </span>`;
    }

    return `
    <div class="chat-msg-row ${mine ? 'mine' : ''}">
        ${!mine ? avHtml : ''}
        <div class="chat-bubble-wrap">
            <div class="chat-bubble ${imgOnly}">${content}</div>
            <div class="chat-msg-time">
                <span>${msg.created_at}</span>${tickHtml}
            </div>
        </div>
        ${mine ? avHtml : ''}
    </div>`;
}

// ══ READ STATUS (blue ticks) ═════════════════════════
function checkReadStatus(userId) {
    fetch(`${URL_RS}/${userId}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json())
    .then(data => {
        (data.read_ids || []).forEach(id => {
            const tick = document.querySelector(`.msg-tick[data-msg-id="${id}"]`);
            if (tick && !tick.classList.contains('read')) {
                tick.classList.remove('unread');
                tick.classList.add('read');
            }
        });
    }).catch(() => {});
}

// ══ SEND MESSAGE ════════════════════════════════════
function sendMessage() {
    const inp = document.getElementById('chatInput');
    const btn = document.getElementById('sendBtn');
    if (!currentUser || !inp) return;

    const body    = inp.value.trim();
    const hasFile = selFile !== null;
    if (!body && !hasFile) return;

    btn.disabled = true;
    inp.value = ''; autoResize(inp);

    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('receiver_id', currentUser);
    if (body)    fd.append('body',       body);
    if (hasFile) fd.append('attachment', selFile);

    clearFile(); closeEmoji();

    fetch(URL_SEND, {
        method: 'POST',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            const c = document.getElementById('chatMessages');
            if (c) {
                c.querySelector('.chat-empty')?.remove();
                c.insertAdjacentHTML('beforeend', buildBubble(data.message));
                lastMsgId = data.message.id;
                hideNewMsgBtn();
                scrollAfterImgs(c, true);
                updatePreview(currentUser, data.message);
            }
        }
    })
    .catch(() => { btn.disabled = false; });
}

// ══ BRANCH FILTER (AJAX) ════════════════════════════
function loadUsers(branchId) {
    const list = document.getElementById('usersList');
    list.innerHTML = '<div style="padding:28px;text-align:center;"><i class="bi bi-arrow-repeat spin" style="font-size:24px;color:#6366f1;"></i></div>';

    fetch(`${URL_USERS}?branch_id=${branchId||''}`, {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(r => r.json())
    .then(users => {
        if (!users.length) {
            list.innerHTML = `<div style="padding:38px 18px;text-align:center;color:#94a3b8;">
                <i class="bi bi-person-x" style="font-size:30px;display:block;margin-bottom:9px;"></i>
                لا يوجد موظفون في هذا الفرع</div>`;
            return;
        }
        list.innerHTML = users.map(u => {
            const dot    = u.online ? 'online' : (u.away ? 'away' : '');
            const inits  = ((u.first_name||'U')[0]+(u.last_name||'')[0]).toUpperCase();
            const prev   = u.last_msg_body
                ? `${u.last_msg_mine ? 'أنت: ' : ''}${u.last_msg_body.slice(0,28)}`
                : `<span style="color:#c0c0c0;font-style:italic;">لا توجد رسائل بعد</span>`;
            const active = currentUser === u.id;
            return `
            <div class="chat-user-item ${active ? 'active' : ''}"
                 data-user-id="${u.id}" data-name="${(u.name||'').toLowerCase()}"
                 data-online="${u.online?'1':'0'}" data-away="${u.away?'1':'0'}"
                 onclick="openChat(${u.id},'${(u.name||'').replace(/'/g,"\\'")}','${u.avatar||''}','${(u.branch||'').replace(/'/g,"\\'")}')">
                <div class="chat-avatar-wrap">
                    <img src="${u.avatar||''}" class="chat-avatar" alt="${u.name||''}"
                         onerror="this.onerror=null;this.src='${URL_AVT}/${encodeURIComponent(inits)}'">
                    <span class="chat-online-dot ${dot}"></span>
                </div>
                <div class="chat-user-info">
                    <div class="chat-user-name">${u.name||''}</div>
                    ${u.branch?`<div class="chat-user-branch"><i class="bi bi-building" style="font-size:9px;"></i> ${u.branch}</div>`:''}
                    <div class="chat-user-preview" id="preview-${u.id}">${prev}</div>
                </div>
                <div class="chat-user-meta">
                    <span class="chat-user-time" id="time-${u.id}">${u.last_msg_time||''}</span>
                    <span class="chat-unread-badge" id="badge-${u.id}"
                          style="${u.unread_count>0?'':'display:none;'}">${u.unread_count}</span>
                </div>
            </div>`;
        }).join('');

        // Re-apply search if active
        const q = (document.getElementById('userSearch')?.value||'').toLowerCase();
        if (q) document.querySelectorAll('.chat-user-item').forEach(el =>
            el.style.display = el.dataset.name.includes(q) ? '' : 'none');
    })
    .catch(() => { list.innerHTML='<div style="padding:28px;text-align:center;color:#ef4444;">خطأ في التحميل</div>'; });
}

// ══ EMOJI ════════════════════════════════════════════
const EMOJIS = {
    '😀': ['😀','😃','😄','😁','😆','😅','🤣','😂','🙂','😊','🥰','😍','🤩','😎','🥳','🤔','😴','😭','😱','😡','🤬','🥺','🤗','😏','😒'],
    '👍': ['👍','👎','👌','✌️','🤞','🤙','💪','🙏','🤝','👋','🫶','✋','☝️','👆','👇','👉','👈','🤜','🤛','🙌','👏'],
    '❤️': ['❤️','🧡','💛','💚','💙','💜','🖤','🤍','💔','❤️‍🔥','💯','✅','❌','⭐','🌟','💫','✨','🔥','🎉','🎊','🏆','🎯','💎'],
    '🐱': ['🐱','🐶','🦊','🐻','🦁','🐸','🐧','🦋','🌺','🌹','🌈','☀️','🌙','⚡','🌊','🍕','☕','🍔','🎵','🎸'],
    '💼': ['📝','📋','📌','💼','💡','🔑','⏰','📊','📈','🖥️','📱','📞','✏️','🏥','💊','🔬','📏','🗓️','🔒','📧'],
};
const EKEYS = Object.keys(EMOJIS);

function buildEmojiPicker() {
    const tabs  = EKEYS.map((k,i) => `<button class="emoji-tab${i===0?' active':''}" onclick="swETab(this,${i})">${k}</button>`).join('');
    const grids = EKEYS.map((k,i) => `<div class="emoji-grid" id="eg${i}" style="${i?'display:none;':''}">
        ${EMOJIS[k].map(e=>`<button class="emoji-btn" onclick="insEmoji('${e}')">${e}</button>`).join('')}
    </div>`).join('');
    document.getElementById('emojiPicker').innerHTML =
        `<div class="emoji-tabs">${tabs}</div>${grids}`;
}
function swETab(btn, idx) {
    document.querySelectorAll('.emoji-tab').forEach(t=>t.classList.remove('active'));
    btn.classList.add('active');
    EKEYS.forEach((_,i)=>{ const g=document.getElementById('eg'+i); if(g) g.style.display=i===idx?'':'none'; });
}
function toggleEmoji() {
    const p = document.getElementById('emojiPicker');
    emojiOpen = !emojiOpen;
    p.style.display = emojiOpen ? 'block' : 'none';
    if (emojiOpen && !p.innerHTML) buildEmojiPicker();
}
function closeEmoji() {
    emojiOpen = false;
    const p = document.getElementById('emojiPicker');
    if (p) p.style.display = 'none';
}
function insEmoji(e) {
    const inp = document.getElementById('chatInput');
    if (!inp) return;
    const s = inp.selectionStart, en = inp.selectionEnd;
    inp.value = inp.value.slice(0,s) + e + inp.value.slice(en);
    inp.selectionStart = inp.selectionEnd = s + e.length;
    inp.focus(); autoResize(inp);
}
document.addEventListener('click', e => {
    if (!e.target.closest('#emojiPicker') && !e.target.closest('#emojiBtn')) closeEmoji();
});

// ══ FILE / IMAGE ═════════════════════════════════════
function previewFile(input) {
    if (!input.files?.[0]) return;
    selFile = input.files[0];
    const r = new FileReader();
    r.onload = ev => {
        document.getElementById('filePreviewImg').src = ev.target.result;
        document.getElementById('fpName').textContent = selFile.name;
        document.getElementById('filePreviewArea').style.display = 'flex';
    };
    r.readAsDataURL(selFile);
}
function clearFile() {
    selFile = null;
    document.getElementById('filePreviewArea').style.display = 'none';
    document.getElementById('filePreviewImg').src = '';
    const fi = document.getElementById('chatFileInput');
    if (fi) fi.value = '';
}

// ══ LIGHTBOX ═════════════════════════════════════════
function openLb(src)  { document.getElementById('lbImg').src=src; document.getElementById('imgLightbox').style.display='flex'; }
function closeLb()    { document.getElementById('imgLightbox').style.display='none'; }

// ══ HELPERS ══════════════════════════════════════════
function esc(t) {
    return (t||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                  .replace(/"/g,'&quot;').replace(/\n/g,'<br>');
}
function _fd(d) {
    const t=new Date().toISOString().slice(0,10), y=new Date(Date.now()-86400000).toISOString().slice(0,10);
    return d===t?'اليوم':d===y?'أمس':d;
}
function scrollB(el, force) {
    const near = el.scrollHeight - el.scrollTop - el.clientHeight < 160;
    if (force || near) el.scrollTop = el.scrollHeight;
}
// Scroll to bottom, then re-scroll after each image finishes loading
// This fixes the issue where images load AFTER the scroll call
function scrollAfterImgs(container, force) {
    scrollB(container, force);
    // Find all unbound images in the container
    container.querySelectorAll('.chat-img-container img:not([data-sb])').forEach(img => {
        img.dataset.sb = '1'; // mark as bound
        const rescroll = () => scrollB(container, force);
        if (img.complete && img.naturalHeight > 0) {
            rescroll(); // already loaded
        } else {
            img.addEventListener('load',  rescroll, { once: true });
            img.addEventListener('error', rescroll, { once: true });
        }
    });
}
// Called from onload attribute on each chat image
function _imgLoaded(img) {
    const c = document.getElementById('chatMessages');
    if (c) scrollB(c, false); // scroll only if near bottom (don't force on old images)
}
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
function updatePreview(userId, msg) {
    const pv = document.getElementById('preview-' + userId);
    const tm = document.getElementById('time-'    + userId);
    const body = msg.body || (msg.attachment ? '📷 صورة' : '');
    if (pv) pv.textContent = (msg.is_mine ? 'أنت: ' : '') + body.slice(0,28);
    if (tm) tm.textContent = msg.created_at;
}

// ══ SIDEBAR AUTO-REFRESH ════════════════════════════
// Runs every 10s — updates preview text, time, badge, and online dots
// without rebuilding the DOM (so active selection is preserved)
function refreshSidebarPreviews() {
    const branchId = document.getElementById('branchFilter')?.value || '';
    fetch(`${URL_USERS}?branch_id=${branchId}`, {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(r => r.json())
    .then(users => {
        users.forEach(u => {
            // ── Preview text ──
            const pv = document.getElementById('preview-' + u.id);
            if (pv) {
                if (u.last_msg_body) {
                    pv.textContent = (u.last_msg_mine ? 'أنت: ' : '') + u.last_msg_body.slice(0, 28);
                } else {
                    pv.innerHTML = '<span style="color:#c0c0c0;font-style:italic;">لا توجد رسائل بعد</span>';
                }
            }
            // ── Last message time ──
            const tm = document.getElementById('time-' + u.id);
            if (tm) tm.textContent = u.last_msg_time || '';

            // ── Unread badge (skip if this is the open chat — already cleared) ──
            if (u.id !== currentUser) {
                const badge = document.getElementById('badge-' + u.id);
                if (badge) {
                    badge.textContent   = u.unread_count;
                    badge.style.display = u.unread_count > 0 ? '' : 'none';
                }
            }

            // ── Online dot + data attributes ──
            const item = document.querySelector(`.chat-user-item[data-user-id="${u.id}"]`);
            if (item) {
                item.dataset.online = u.online ? '1' : '0';
                item.dataset.away   = u.away   ? '1' : '0';
                const dot = item.querySelector('.chat-online-dot');
                if (dot) {
                    dot.className = 'chat-online-dot' + (u.online ? ' online' : (u.away ? ' away' : ''));
                }
            }
        });
    }).catch(() => {});
}

// ══ INIT ═════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    // Textarea events
    const inp = document.getElementById('chatInput');
    if (inp) {
        inp.addEventListener('keydown', e => {
            if (e.key==='Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });
        inp.addEventListener('input', () => autoResize(inp));
    }

    // Paste image from clipboard
    document.addEventListener('paste', e => {
        if (!currentUser) return;
        const items = (e.clipboardData||window.clipboardData)?.items;
        if (!items) return;
        for (let i=0; i<items.length; i++) {
            if (items[i].type.startsWith('image/')) {
                selFile = items[i].getAsFile();
                const r = new FileReader();
                r.onload = ev => {
                    document.getElementById('filePreviewImg').src = ev.target.result;
                    document.getElementById('fpName').textContent = 'صورة ملصقة';
                    document.getElementById('filePreviewArea').style.display = 'flex';
                };
                r.readAsDataURL(selFile);
                e.preventDefault();
                break;
            }
        }
    });

    // Branch filter
    document.getElementById('branchFilter')?.addEventListener('change', function() {
        loadUsers(this.value);
    });

    // Search
    document.getElementById('userSearch')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.chat-user-item').forEach(el =>
            el.style.display = el.dataset.name.includes(q) ? '' : 'none');
    });

    // Scroll listener — hide floating button when user reaches bottom
    const chatMsgsEl = document.getElementById('chatMessages');
    if (chatMsgsEl) {
        chatMsgsEl.addEventListener('scroll', () => {
            if (isNearBottom()) hideNewMsgBtn();
        }, { passive: true });
    }

    // Sidebar auto-refresh every 10 s
    sbInterval = setInterval(refreshSidebarPreviews, 10000);

    // If opened server-side
    if (INIT_WITH) {
        if (pollInterval) clearInterval(pollInterval);
        if (rsInterval)   clearInterval(rsInterval);
        loadMessages(INIT_WITH, true);
        pollInterval = setInterval(() => loadMessages(INIT_WITH, false), 3000);
        rsInterval   = setInterval(() => checkReadStatus(INIT_WITH),     5000);
    }

    // ESC closes lightbox
    document.addEventListener('keydown', e => { if (e.key==='Escape') closeLb(); });
});
</script>
@endsection
