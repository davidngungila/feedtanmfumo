<div id="loading-screen" class="loading-screen">
    <div class="loading-content">
        <div class="loading-animation">
            <div class="loading-dot dot-1"></div>
            <div class="loading-dot dot-2"></div>
            <div class="loading-dot dot-3"></div>
            <div class="loading-dot dot-4"></div>
        </div>
        <div class="loading-text">
            <h1 class="loading-title">{{ __('common.welcome_message') }}</h1>
            <p class="loading-subtitle">{{ __('common.loading_message') }}</p>
        </div>
    </div>
</div>

<style>
    .loading-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    
    .loading-screen.hidden {
        opacity: 0;
        visibility: hidden;
    }
    
    .loading-content {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 20px;
    }
    
    .loading-animation {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 12px;
        width: 80px;
        height: 80px;
        position: relative;
    }
    
    .loading-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #015425 0%, #027a3a 50%, #16a34a 100%);
        animation: pulse 1.4s ease-in-out infinite;
        box-shadow: 0 0 20px rgba(1, 84, 37, 0.4);
    }
    
    .loading-dot.dot-1 {
        animation-delay: 0s;
    }
    
    .loading-dot.dot-2 {
        animation-delay: 0.2s;
    }
    
    .loading-dot.dot-3 {
        animation-delay: 0.4s;
    }
    
    .loading-dot.dot-4 {
        animation-delay: 0.6s;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
            box-shadow: 0 0 20px rgba(1, 84, 37, 0.4);
        }
        50% {
            transform: scale(1.2);
            opacity: 0.7;
            box-shadow: 0 0 30px rgba(1, 84, 37, 0.6);
        }
    }
    
    .loading-text {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .loading-title {
        font-size: 28px;
        font-weight: bold;
        color: #015425;
        margin: 0;
        font-family: 'Quicksand', sans-serif;
    }
    
    .loading-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin: 0;
        font-family: 'Quicksand', sans-serif;
    }
    
    /* Mobile responsive */
    @media (max-width: 640px) {
        .loading-content {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
        
        .loading-animation {
            width: 60px;
            height: 60px;
            gap: 8px;
        }
        
        .loading-dot {
            width: 22px;
            height: 22px;
        }
        
        .loading-title {
            font-size: 22px;
        }
        
        .loading-subtitle {
            font-size: 14px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingScreen = document.getElementById('loading-screen');
        
        if (loadingScreen) {
            // Hide loading screen after page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    loadingScreen.classList.add('hidden');
                    // Remove from DOM after animation
                    setTimeout(function() {
                        loadingScreen.remove();
                    }, 500);
                }, 500);
            });
            
            // Fallback: hide after 3 seconds even if load event doesn't fire
            setTimeout(function() {
                if (loadingScreen && !loadingScreen.classList.contains('hidden')) {
                    loadingScreen.classList.add('hidden');
                    setTimeout(function() {
                        loadingScreen.remove();
                    }, 500);
                }
            }, 3000);
        }
    });
</script>

