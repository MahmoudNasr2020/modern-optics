<style>
/* ═══════════ Crystal Eye Preloader ═══════════ */
#preloader {
    position: fixed; width: 100%; height: 100%;
    top: 0; left: 0; z-index: 99999;
    display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: 28px; overflow: hidden;
    background: radial-gradient(ellipse at 30% 40%, #0a0520 0%, #060115 55%, #000 100%);
    transition: opacity .65s ease, transform .65s ease;
}
#preloader.fade-out { opacity: 0; transform: scale(1.06); pointer-events: none; }

/* ── Stars background ── */
.pl-stars { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
.pl-star  {
    position: absolute; border-radius: 50%;
    background: rgba(255,255,255,0.8); animation: starTwinkle 3s ease-in-out infinite;
}
.pl-star:nth-child(1)  { width:2px;height:2px; top:12%;left:20%;  animation-delay:0s;   }
.pl-star:nth-child(2)  { width:1px;height:1px; top:25%;left:75%;  animation-delay:.5s;  }
.pl-star:nth-child(3)  { width:2px;height:2px; top:60%;left:10%;  animation-delay:1s;   }
.pl-star:nth-child(4)  { width:1px;height:1px; top:80%;left:55%;  animation-delay:1.5s; }
.pl-star:nth-child(5)  { width:3px;height:3px; top:35%;left:88%;  animation-delay:.8s;  }
.pl-star:nth-child(6)  { width:1px;height:1px; top:70%;left:35%;  animation-delay:2s;   }
.pl-star:nth-child(7)  { width:2px;height:2px; top:8%; left:60%;  animation-delay:2.4s; }
.pl-star:nth-child(8)  { width:1px;height:1px; top:90%;left:80%;  animation-delay:.3s;  }
.pl-star:nth-child(9)  { width:2px;height:2px; top:50%;left:92%;  animation-delay:1.8s; }
.pl-star:nth-child(10) { width:1px;height:1px; top:18%;left:42%;  animation-delay:1.2s; }

/* ── Background ambient glows ── */
.pl-glow {
    position: absolute; border-radius: 50%; pointer-events: none;
    animation: bgPulse 6s ease-in-out infinite alternate;
}
.pl-glow-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(99,102,241,0.13) 0%, transparent 70%);
    top: -200px; left: -150px;
}
.pl-glow-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(139,92,246,0.10) 0%, transparent 70%);
    bottom: -120px; right: -100px; animation-delay: 3s;
}
.pl-glow-3 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(56,189,248,0.08) 0%, transparent 70%);
    top: 40%; left: 60%; animation-delay: 1.5s;
}

/* ── Eye container ── */
.pl-eye-wrap {
    position: relative; width: 210px; height: 210px;
    display: flex; align-items: center; justify-content: center;
}

/* ── Orbital rings ── */
.pl-ring {
    position: absolute; border-radius: 50%;
    border: 2px solid transparent;
}
.pl-ring-1 {
    width: 210px; height: 210px;
    border-top-color: rgba(99,102,241,1);
    border-right-color: rgba(99,102,241,0.4);
    animation: spinCW 2.2s linear infinite;
    box-shadow: 0 0 18px rgba(99,102,241,0.35), inset 0 0 18px rgba(99,102,241,0.08);
}
.pl-ring-2 {
    width: 172px; height: 172px;
    border-bottom-color: rgba(139,92,246,0.9);
    border-left-color: rgba(139,92,246,0.35);
    animation: spinCCW 1.7s linear infinite;
    box-shadow: 0 0 14px rgba(139,92,246,0.3);
}
.pl-ring-3 {
    width: 136px; height: 136px;
    border-top-color: rgba(56,189,248,0.8);
    border-left-color: rgba(56,189,248,0.2);
    animation: spinCW 3s linear infinite;
    box-shadow: 0 0 12px rgba(56,189,248,0.25);
}

/* ── Orbiting particles ── */
.pl-dot {
    position: absolute; border-radius: 50%;
    width: 6px; height: 6px; top: calc(50% - 3px); left: calc(50% - 3px);
}
.pl-dot-1 {
    background: rgba(99,102,241,1);
    box-shadow: 0 0 10px rgba(99,102,241,0.9), 0 0 20px rgba(99,102,241,0.5);
    animation: orbit105 2.8s linear infinite;
}
.pl-dot-2 {
    background: rgba(99,102,241,0.8);
    box-shadow: 0 0 8px rgba(99,102,241,0.7);
    animation: orbit105 2.8s linear infinite; animation-delay: -.7s;
}
.pl-dot-3 {
    background: rgba(99,102,241,0.8);
    box-shadow: 0 0 8px rgba(99,102,241,0.7);
    animation: orbit105 2.8s linear infinite; animation-delay: -1.4s;
}
.pl-dot-4 {
    background: rgba(99,102,241,0.8);
    box-shadow: 0 0 8px rgba(99,102,241,0.7);
    animation: orbit105 2.8s linear infinite; animation-delay: -2.1s;
}
.pl-dot-5 {
    width: 5px; height: 5px; top: calc(50% - 2.5px); left: calc(50% - 2.5px);
    background: rgba(56,189,248,1);
    box-shadow: 0 0 10px rgba(56,189,248,0.9), 0 0 20px rgba(56,189,248,0.4);
    animation: orbit68 3.8s linear infinite;
}
.pl-dot-6 {
    width: 5px; height: 5px; top: calc(50% - 2.5px); left: calc(50% - 2.5px);
    background: rgba(56,189,248,0.8);
    box-shadow: 0 0 8px rgba(56,189,248,0.7);
    animation: orbit68 3.8s linear infinite; animation-delay: -1.9s;
}

/* ── Eye core ── */
.pl-eye-core {
    position: absolute;
    width: 96px; height: 96px; border-radius: 50%;
    background: radial-gradient(circle at 40% 38%, rgba(100,60,200,0.9) 0%, rgba(50,15,100,0.96) 55%, rgba(10,5,30,1) 100%);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    box-shadow:
        0 0 35px rgba(99,102,241,0.65),
        0 0 70px rgba(99,102,241,0.28),
        0 0 110px rgba(99,102,241,0.12),
        inset 0 0 25px rgba(99,102,241,0.18);
}

/* ── Iris ── */
.pl-iris {
    position: relative;
    width: 60px; height: 60px; border-radius: 50%;
    background:
        radial-gradient(circle at 40% 36%, rgba(160,110,255,0.9) 0%, rgba(90,30,180,0.85) 35%, rgba(40,8,90,0.95) 70%, transparent 100%);
    display: flex; align-items: center; justify-content: center;
    animation: irisBreath 2.2s ease-in-out infinite;
}
/* Iris texture lines */
.pl-iris::before {
    content: '';
    position: absolute; inset: 0; border-radius: 50%;
    background: repeating-conic-gradient(rgba(255,255,255,0.04) 0deg, transparent 4deg, transparent 8deg);
}

/* ── Pupil ── */
.pl-pupil {
    width: 26px; height: 26px; border-radius: 50%;
    background: radial-gradient(circle, #08001a 60%, #1a0040 100%);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 12px rgba(139,92,246,0.8), 0 0 4px rgba(139,92,246,0.5);
    position: relative; z-index: 1;
}
.pl-pupil i { font-size: 12px; color: rgba(180,140,255,0.9); }

/* Pupil shine */
.pl-pupil::after {
    content: '';
    position: absolute; width: 6px; height: 6px;
    border-radius: 50%; background: rgba(255,255,255,0.6);
    top: 4px; left: 6px;
}

/* ── Scan line ── */
.pl-scan {
    position: absolute; width: 100%; height: 2px;
    background: linear-gradient(90deg, transparent 0%, rgba(56,189,248,0.0) 20%, rgba(56,189,248,0.9) 50%, rgba(56,189,248,0.0) 80%, transparent 100%);
    animation: scanLine 2s ease-in-out infinite;
    box-shadow: 0 0 10px rgba(56,189,248,0.7);
}

/* ── Loading text ── */
.pl-label {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.pl-label-text {
    font-size: 13px; font-weight: 700; letter-spacing: 5px;
    text-transform: uppercase;
    background: linear-gradient(90deg, rgba(255,255,255,0.4) 0%, #fff 40%, rgba(139,92,246,0.9) 70%, rgba(255,255,255,0.4) 100%);
    background-size: 250% auto;
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 2.4s linear infinite;
}
.pl-progress-bar {
    width: 140px; height: 3px;
    background: rgba(255,255,255,0.08);
    border-radius: 4px; overflow: hidden;
}
.pl-progress-fill {
    height: 100%; border-radius: 4px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #38bdf8, #6366f1);
    background-size: 300% auto;
    animation: progressSlide 1.6s ease-in-out infinite, shimmer 2s linear infinite;
    width: 60%;
}

/* ════════ Keyframes ════════ */
@keyframes spinCW  { to { transform: rotate(360deg);  } }
@keyframes spinCCW { to { transform: rotate(-360deg); } }

@keyframes orbit105 {
    0%   { transform: rotate(0deg)   translateX(105px) rotate(0deg);   }
    100% { transform: rotate(360deg) translateX(105px) rotate(-360deg); }
}
@keyframes orbit68 {
    0%   { transform: rotate(0deg)   translateX(68px) rotate(0deg);   }
    100% { transform: rotate(360deg) translateX(68px) rotate(-360deg); }
}

@keyframes irisBreath {
    0%, 100% { transform: scale(1);    }
    50%       { transform: scale(0.86); }
}

@keyframes scanLine {
    0%   { top: 5%;   opacity: 0;   }
    15%  { opacity: 1; }
    85%  { opacity: 1; }
    100% { top: 95%;  opacity: 0;   }
}

@keyframes bgPulse {
    from { opacity: 0.5; transform: scale(1); }
    to   { opacity: 1;   transform: scale(1.15); }
}

@keyframes starTwinkle {
    0%, 100% { opacity: 0.2; transform: scale(1);   }
    50%       { opacity: 1;   transform: scale(1.6); }
}

@keyframes shimmer {
    0% { background-position: 0%   center; }
    100% { background-position: 250% center; }
}

@keyframes progressSlide {
    0%   { width: 10%; }
    50%  { width: 80%; }
    100% { width: 10%; }
}
</style>

<div id="preloader">

    {{-- Stars --}}
    <div class="pl-stars">
        <div class="pl-star"></div><div class="pl-star"></div><div class="pl-star"></div>
        <div class="pl-star"></div><div class="pl-star"></div><div class="pl-star"></div>
        <div class="pl-star"></div><div class="pl-star"></div><div class="pl-star"></div>
        <div class="pl-star"></div>
    </div>

    {{-- Ambient glows --}}
    <div class="pl-glow pl-glow-1"></div>
    <div class="pl-glow pl-glow-2"></div>
    <div class="pl-glow pl-glow-3"></div>

    {{-- Eye --}}
    <div class="pl-eye-wrap">
        <div class="pl-ring pl-ring-1"></div>
        <div class="pl-ring pl-ring-2"></div>
        <div class="pl-ring pl-ring-3"></div>

        <div class="pl-dot pl-dot-1"></div>
        <div class="pl-dot pl-dot-2"></div>
        <div class="pl-dot pl-dot-3"></div>
        <div class="pl-dot pl-dot-4"></div>
        <div class="pl-dot pl-dot-5"></div>
        <div class="pl-dot pl-dot-6"></div>

        <div class="pl-eye-core">
            <div class="pl-iris">
                <div class="pl-pupil">
                    <i class="bi bi-eye-fill"></i>
                </div>
            </div>
            <div class="pl-scan"></div>
        </div>
    </div>

    {{-- Label --}}
    <div class="pl-label">
        <span class="pl-label-text">جارٍ التحميل</span>
        <div class="pl-progress-bar">
            <div class="pl-progress-fill"></div>
        </div>
    </div>

</div>

<script>
(function () {
    function hideLoader() {
        var el = document.getElementById('preloader');
        if (!el || el.style.display === 'none') return;
        el.classList.add('fade-out');
        setTimeout(function () { el.style.display = 'none'; }, 680);
    }
    window.addEventListener('load', hideLoader);
    setTimeout(hideLoader, 9000);
})();
</script>
