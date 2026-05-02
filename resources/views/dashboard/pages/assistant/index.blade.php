@extends('dashboard.layouts.master')
@section('title', 'AI Assistant')
@section('content')

<style>
/* ── Page Layout ── */
.assistant-page { height: calc(100vh - 120px); display: flex; flex-direction: column; padding: 0; }

/* ── Header ── */
.assistant-header {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    color: #fff; padding: 18px 24px; display: flex; align-items: center; gap: 14px;
    border-radius: 14px 14px 0 0; box-shadow: 0 2px 12px rgba(99,102,241,0.25);
    flex-shrink: 0;
}
.assistant-header .bot-icon {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center; font-size: 22px;
    box-shadow: 0 4px 12px rgba(99,102,241,0.4); flex-shrink: 0;
}
.assistant-header h3 { margin: 0; font-size: 18px; font-weight: 700; }
.assistant-header .sub { font-size: 12px; opacity: .7; margin-top: 2px; }
.assistant-status {
    margin-left: auto; display: flex; align-items: center; gap: 6px;
    font-size: 12px; opacity: .85;
}
.status-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #22c55e;
    animation: statusPulse 2s infinite;
}
@keyframes statusPulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

/* ── Suggestion pills ── */
.suggestions {
    padding: 12px 20px; background: #f8f9fe; border-bottom: 1px solid #e8ecf7;
    display: flex; gap: 8px; flex-wrap: wrap; flex-shrink: 0;
}
.sugg-pill {
    background: #fff; border: 1px solid #e0e6ed; border-radius: 20px;
    padding: 5px 14px; font-size: 12px; color: #4f46e5; cursor: pointer;
    transition: all .18s; font-weight: 500; white-space: nowrap;
}
.sugg-pill:hover { background: #6366f1; color: #fff; border-color: #6366f1; transform: translateY(-1px); }

/* ── Messages area ── */
.chat-messages {
    flex: 1; overflow-y: auto; padding: 20px; background: #f0f2fc;
    display: flex; flex-direction: column; gap: 14px;
    scroll-behavior: smooth;
}
.chat-messages::-webkit-scrollbar { width: 5px; }
.chat-messages::-webkit-scrollbar-thumb { background: rgba(99,102,241,.3); border-radius: 4px; }

/* ── Message bubbles ── */
.msg { display: flex; gap: 10px; max-width: 80%; }
.msg.user { align-self: flex-end; flex-direction: row-reverse; }
.msg.bot  { align-self: flex-start; }

.msg-avatar {
    width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center;
    justify-content: center; font-size: 15px; font-weight: 700; flex-shrink: 0;
    align-self: flex-end;
}
.msg.bot  .msg-avatar { background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; }
.msg.user .msg-avatar { background: linear-gradient(135deg,#10b981,#059669); color:#fff; font-size:12px; }

.msg-bubble {
    padding: 12px 16px; border-radius: 16px; font-size: 13.5px; line-height: 1.6;
    white-space: pre-wrap; word-break: break-word;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
}
.msg.bot  .msg-bubble { background: #fff; color: #1e293b; border-bottom-left-radius: 4px; }
.msg.user .msg-bubble { background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; border-bottom-right-radius: 4px; }

.msg-time { font-size: 10px; opacity: .55; margin-top: 4px; text-align: right; }
.msg.user .msg-time { text-align: left; }

/* ── Stat cards ── */
.stat-cards { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
.stat-card {
    background: #f8f9fe; border-radius: 10px; padding: 12px 16px;
    min-width: 130px; border-left: 3px solid #6366f1;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.stat-card .sc-val { font-size: 22px; font-weight: 700; color: #1e293b; }
.stat-card .sc-lbl { font-size: 11px; color: #64748b; margin-top: 2px; }

/* ── Mini bar chart ── */
.mini-chart { margin-top: 10px; }
.chart-bar-wrap { display: flex; flex-direction: column; gap: 5px; max-height: 220px; overflow-y: auto; }
.chart-row { display: flex; align-items: center; gap: 8px; }
.chart-label { font-size: 11px; color: #64748b; min-width: 80px; text-align: right; flex-shrink: 0; }
.chart-bar-bg { flex: 1; height: 18px; background: #e8ecf7; border-radius: 6px; overflow: hidden; }
.chart-bar { height: 100%; border-radius: 6px; background: linear-gradient(90deg,#6366f1,#8b5cf6); transition: width .5s ease; }
.chart-val { font-size: 11px; color: #4f46e5; font-weight: 600; min-width: 60px; }

/* ── Typing indicator ── */
.typing-indicator { display: flex; gap: 4px; align-items: center; padding: 8px 0; }
.typing-dot { width: 7px; height: 7px; border-radius: 50%; background: #94a3b8; animation: typingAnim 1.2s infinite; }
.typing-dot:nth-child(2) { animation-delay: .2s; }
.typing-dot:nth-child(3) { animation-delay: .4s; }
@keyframes typingAnim { 0%,60%,100%{transform:translateY(0);} 30%{transform:translateY(-6px);} }

/* ── Input bar ── */
.chat-input-bar {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px; background: #fff;
    border-top: 1px solid #e8ecf7; flex-shrink: 0;
    border-radius: 0 0 14px 14px;
}
#chatInput {
    flex: 1; border: 1.5px solid #e0e6ed; border-radius: 24px;
    padding: 10px 18px; font-size: 14px; outline: none; resize: none;
    transition: border-color .18s; font-family: inherit; line-height: 1.4;
    max-height: 100px; overflow-y: auto;
}
#chatInput:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.btn-send {
    width: 42px; height: 42px; border-radius: 50%; border: none; cursor: pointer;
    background: linear-gradient(135deg,#6366f1,#8b5cf6); color: #fff;
    font-size: 16px; display: flex; align-items: center; justify-content: center;
    transition: transform .18s, box-shadow .18s; flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(99,102,241,.4);
}
.btn-send:hover { transform: scale(1.08); box-shadow: 0 5px 16px rgba(99,102,241,.5); }
.btn-send:active { transform: scale(.95); }
.btn-clear {
    padding: 7px 14px; border-radius: 20px; border: 1px solid #e0e6ed;
    background: #f8f9fe; color: #64748b; font-size: 12px; cursor: pointer;
    transition: all .18s; white-space: nowrap;
}
.btn-clear:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }

/* ── Welcome screen ── */
.welcome-screen {
    text-align: center; padding: 40px 20px; color: #94a3b8;
}
.welcome-screen .big-icon { font-size: 64px; display: block; margin-bottom: 14px; }
.welcome-screen h4 { color: #334155; font-size: 18px; margin-bottom: 8px; }

/* ── Bold markdown ── */
.msg-bubble strong { font-weight: 700; }

/* Card wrapper */
.chat-card {
    background: #fff; border-radius: 14px; overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.08); display: flex; flex-direction: column;
    height: calc(100vh - 120px);
}
</style>

<section class="content-header">
    <h1><i class="bi bi-robot"></i> AI Assistant <small>Smart business insights</small></h1>
</section>

<div class="assistant-page">
    <div class="chat-card">

        {{-- Header --}}
        <div class="assistant-header">
            <div class="bot-icon"><i class="bi bi-robot"></i></div>
            <div>
                <h3>المساعد الذكي</h3>
                <div class="sub">AI-powered insights for your business</div>
            </div>
            <div class="assistant-status">
                <div class="status-dot"></div>
                <span>Online</span>
            </div>
        </div>

        {{-- Suggestion Pills --}}
        <div class="suggestions">
            <span class="sugg-pill" onclick="sendSuggestion('مبيعات اليوم')">📊 مبيعات اليوم</span>
            <span class="sugg-pill" onclick="sendSuggestion('مبيعات هذا الأسبوع')">📅 هذا الأسبوع</span>
            <span class="sugg-pill" onclick="sendSuggestion('مبيعات هذا الشهر')">📆 هذا الشهر</span>
            <span class="sugg-pill" onclick="sendSuggestion('مقارنة الأسبوع الحالي بالماضي')">🔄 مقارنة الأسابيع</span>
            <span class="sugg-pill" onclick="sendSuggestion('مقارنة الشهر الحالي بالماضي')">📊 مقارنة الأشهر</span>
            <span class="sugg-pill" onclick="sendSuggestion('عدد العدسات المباعة اليوم')">👁️ العدسات</span>
            <span class="sugg-pill" onclick="sendSuggestion('مقارنة الفروع')">🏢 مقارنة الفروع</span>
            <span class="sugg-pill" onclick="sendSuggestion('أكثر المنتجات مبيعاً')">🏆 أفضل المنتجات</span>
            <span class="sugg-pill" onclick="sendSuggestion('الفواتير المعلقة')">⏳ الفواتير المعلقة</span>
            <span class="sugg-pill" onclick="sendSuggestion('مصروفات هذا الشهر')">💸 المصروفات</span>
            <span class="sugg-pill" onclick="sendSuggestion('مصروفات حسب الفئة')">📋 تصنيف المصروفات</span>
            <span class="sugg-pill" onclick="sendSuggestion('المخزون')">📦 المخزون</span>
            <span class="sugg-pill" onclick="sendSuggestion('مساعدة')">❓ مساعدة</span>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="chatMessages">
            <div class="welcome-screen" id="welcomeScreen">
                <span class="big-icon">🤖</span>
                <h4>أهلاً! أنا المساعد الذكي</h4>
                <p style="font-size:14px;">اسألني عن مبيعات اليوم، الأسبوع، الشهر، العدسات، المنتجات، الفروع، وأكثر...</p>
            </div>
        </div>

        {{-- Input --}}
        <div class="chat-input-bar">
            <button class="btn-clear" onclick="clearChat()"><i class="fa fa-trash-o"></i> مسح</button>
            <textarea id="chatInput" placeholder="اكتب سؤالك هنا..." rows="1"
                      onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
            <button class="btn-send" onclick="sendMessage()" title="إرسال">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>

    </div>
</div>

<script>
var userName = '{{ auth()->user()->first_name }}';
var CSRF = '{{ csrf_token() }}';
var queryUrl = '{{ route("dashboard.assistant.query") }}';

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 100) + 'px';
}

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function sendSuggestion(text) {
    document.getElementById('chatInput').value = text;
    sendMessage();
}

function sendMessage() {
    var input = document.getElementById('chatInput');
    var text  = input.value.trim();
    if (!text) return;

    // Hide welcome screen
    var welcome = document.getElementById('welcomeScreen');
    if (welcome) welcome.remove();

    // Add user bubble
    addBubble('user', text, userName.charAt(0).toUpperCase() || 'U');
    input.value = '';
    input.style.height = 'auto';

    // Show typing
    var typingId = addTyping();

    // Call backend
    fetch(queryUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({ message: text })
    })
    .then(function(r) { return r.json(); })
    .then(function(resp) {
        removeTyping(typingId);
        addBotReply(resp.text || 'لم أفهم السؤال.', resp.data);
    })
    .catch(function() {
        removeTyping(typingId);
        addBotReply('⚠️ حدث خطأ في الاتصال. تأكد من الشبكة وحاول مجدداً.', null);
    });
}

function addBubble(type, text, avatarChar) {
    var container = document.getElementById('chatMessages');
    var div = document.createElement('div');
    div.className = 'msg ' + type;

    var avatar = '<div class="msg-avatar">' + avatarChar + '</div>';
    var bubble = '<div><div class="msg-bubble">' + formatText(text) + '</div><div class="msg-time">' + now() + '</div></div>';
    div.innerHTML = avatar + bubble;
    container.appendChild(div);
    scrollToBottom();
    return div;
}

function addBotReply(text, data) {
    var container = document.getElementById('chatMessages');
    var div = document.createElement('div');
    div.className = 'msg bot';

    var extra = '';
    if (data) {
        if (data.type === 'stats' && data.items) {
            extra += '<div class="stat-cards">';
            data.items.forEach(function(item) {
                extra += '<div class="stat-card" style="border-left-color:' + item.color + '">';
                extra += '<div class="sc-val" style="color:' + item.color + '">' + item.value + '</div>';
                extra += '<div class="sc-lbl">' + item.label + '</div>';
                extra += '</div>';
            });
            extra += '</div>';
        }
        if (data.type === 'chart' && data.chart && data.chart.length > 0) {
            var maxVal = Math.max.apply(null, data.chart.map(function(d){ return d.value; }));
            extra += '<div class="mini-chart"><div class="chart-bar-wrap">';
            data.chart.forEach(function(d) {
                var pct = maxVal > 0 ? Math.round((d.value / maxVal) * 100) : 0;
                extra += '<div class="chart-row">';
                extra += '<div class="chart-label">' + d.label + '</div>';
                extra += '<div class="chart-bar-bg"><div class="chart-bar" style="width:' + pct + '%"></div></div>';
                extra += '<div class="chart-val">' + (typeof d.value === 'number' ? d.value.toLocaleString('en', {maximumFractionDigits:0}) : d.value) + '</div>';
                extra += '</div>';
            });
            extra += '</div></div>';
        }
        if (data.type === 'compare' && data.items) {
            var maxV = Math.max.apply(null, data.items.map(function(d){ return d.value; }));
            extra += '<div class="mini-chart"><div class="chart-bar-wrap">';
            data.items.forEach(function(d) {
                var pct = maxV > 0 ? Math.round((d.value / maxV) * 100) : 0;
                extra += '<div class="chart-row">';
                extra += '<div class="chart-label">' + d.label + '</div>';
                extra += '<div class="chart-bar-bg"><div class="chart-bar" style="width:' + pct + '%;background:' + d.color + '"></div></div>';
                extra += '<div class="chart-val">' + d.value.toLocaleString('en', {maximumFractionDigits:0}) + '</div>';
                extra += '</div>';
            });
            extra += '</div></div>';
        }
    }

    var avatar = '<div class="msg-avatar">🤖</div>';
    var bubble = '<div><div class="msg-bubble">' + formatText(text) + extra + '</div><div class="msg-time">' + now() + '</div></div>';
    div.innerHTML = avatar + bubble;
    container.appendChild(div);
    scrollToBottom();
}

function addTyping() {
    var container = document.getElementById('chatMessages');
    var div = document.createElement('div');
    div.className = 'msg bot';
    div.id = 'typing-' + Date.now();
    div.innerHTML = '<div class="msg-avatar">🤖</div><div class="msg-bubble"><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div></div>';
    container.appendChild(div);
    scrollToBottom();
    return div.id;
}

function removeTyping(id) {
    var el = document.getElementById(id);
    if (el) el.remove();
}

function formatText(text) {
    // Convert **bold** to <strong>
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    // Convert newlines to <br>
    text = text.replace(/\n/g, '<br>');
    return text;
}

function scrollToBottom() {
    var el = document.getElementById('chatMessages');
    el.scrollTop = el.scrollHeight;
}

function now() {
    var d = new Date();
    return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
}

function clearChat() {
    var c = document.getElementById('chatMessages');
    c.innerHTML = '<div class="welcome-screen" id="welcomeScreen"><span class="big-icon">🤖</span><h4>أهلاً! أنا المساعد الذكي</h4><p style="font-size:14px;">اسألني عن مبيعات اليوم، الأسبوع، الشهر، العدسات، المنتجات، الفروع، وأكثر...</p></div>';
}

// Focus on load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('chatInput').focus();
});
</script>

@endsection
