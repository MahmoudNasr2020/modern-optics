<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes scaleOut {
        from { transform: scale(1); }
        to { transform: scale(0.8); }
    }

    /* Gradient Animation */
    @keyframes gradientRotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #preloader {
        position: fixed;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        top: 0;
        left: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }

    #preloader.fade-out {
        opacity: 0;
        transform: scale(1.1);
    }

    .spinner-container {
        position: relative;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Outer rotating ring with gradient */
    .spinner-ring {
        position: absolute;
        width: 100px;
        height: 100px;
        border: 4px solid transparent;
        border-top: 4px solid #fff;
        border-right: 4px solid #fff;
        border-radius: 50%;
        animation: spin 1.2s linear infinite;
    }

    /* Middle ring */
    .spinner-ring-2 {
        position: absolute;
        width: 80px;
        height: 80px;
        border: 4px solid transparent;
        border-bottom: 4px solid rgba(255,255,255,0.7);
        border-left: 4px solid rgba(255,255,255,0.7);
        border-radius: 50%;
        animation: spin 1.5s linear infinite reverse;
    }

    /* Inner pulsing circle */
    .spinner-core {
        position: absolute;
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        animation: pulse 1.5s ease-in-out infinite;
        backdrop-filter: blur(10px);
    }

    /* Icon in center */
    .spinner-icon {
        position: absolute;
        font-size: 28px;
        color: white;
        z-index: 10;
        animation: pulse 2s ease-in-out infinite;
    }

    /* Loading text */
    .loading-text {
        margin-top: 150px;
        color: white;
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        animation: pulse 2s ease-in-out infinite;
    }

    /* Dots animation */
    .loading-dots {
        display: inline-block;
    }

    .loading-dots span {
        animation: pulse 1.4s ease-in-out infinite;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    /* Alternative modern spinner design */
    .modern-spinner {
        position: absolute;
        width: 120px;
        height: 120px;
    }

    .modern-spinner div {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 3px solid transparent;
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .modern-spinner div:nth-child(1) {
        border-top-color: #fff;
        animation-duration: 1s;
    }

    .modern-spinner div:nth-child(2) {
        border-top-color: rgba(255,255,255,0.7);
        animation-duration: 1.5s;
        width: 90%;
        height: 90%;
        top: 5%;
        left: 5%;
    }

    .modern-spinner div:nth-child(3) {
        border-top-color: rgba(255,255,255,0.4);
        animation-duration: 2s;
        width: 80%;
        height: 80%;
        top: 10%;
        left: 10%;
    }
</style>

<!-- Spinner Overlay - Design 1 (Multi-Ring) -->
<div id="preloader">
    <div class="spinner-container">
        <div class="spinner-ring"></div>
        <div class="spinner-ring-2"></div>
        <div class="spinner-core"></div>
        <i class="bi bi-eyeglasses spinner-icon"></i>
    </div>
    <div class="loading-text">
        Loading<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
    </div>
</div>

<!-- Alternative Design 2 (Modern Triple Ring) - Uncomment to use -->
<!--
<div id="preloader">
    <div class="modern-spinner">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="loading-text">
        Please Wait<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>
    </div>
</div>
-->

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById("preloader");
        if (loader) {
            // Add fade out class
            loader.classList.add('fade-out');

            // Remove from DOM after animation completes
            setTimeout(function() {
                loader.style.display = "none";
            }, 500);
        }
    });

    // Optional: Hide preloader after maximum time (fallback)
    setTimeout(function() {
        const loader = document.getElementById("preloader");
        if (loader && loader.style.display !== "none") {
            loader.classList.add('fade-out');
            setTimeout(function() {
                loader.style.display = "none";
            }, 500);
        }
    }, 10000); // 10 seconds max
</script>
