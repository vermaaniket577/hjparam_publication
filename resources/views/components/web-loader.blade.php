<div id="global-loader"
    class="fixed inset-0 z-[100] bg-white flex flex-col items-center justify-center transition-opacity duration-500 ease-in-out">
    <div class="relative flex flex-col items-center">
        <!-- Logo Animation -->
        <div class="mb-6 relative">
            <div
                class="h-16 w-16 bg-blue-900 rounded-sm flex items-center justify-center shadow-lg relative overflow-hidden">
                <span class="text-white font-serif font-bold text-4xl z-10">H</span>
                <!-- Scanning Effect -->
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-400 to-transparent opacity-30 h-full w-full animate-scan">
                </div>
            </div>

            <!-- Pulse Ring -->
            <div class="absolute -inset-4 border-2 border-blue-100 rounded-full animate-ping opacity-75"></div>
            <div class="absolute -inset-4 border border-blue-50 rounded-full animate-pulse"></div>
        </div>

        <!-- Text -->
        <h2 class="text-2xl font-serif font-bold text-gray-800 tracking-tight animate-fade-in-up">
            HJPARAM
        </h2>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500 mt-2 animate-pulse">
            Academic Open Access Publishing
        </p>

        <!-- Loading Bar -->
        <div class="mt-8 w-48 h-0.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-900 w-1/3 animate-loading-bar rounded-full"></div>
        </div>

        <p id="loader-text"
            class="absolute -bottom-12 text-xs text-gray-400 font-medium transition-opacity duration-300">
            Fetching peer-reviewed research...
        </p>
    </div>
</div>

<style>
    @keyframes scan {
        0% {
            transform: translateY(-100%);
        }

        100% {
            transform: translateY(100%);
        }
    }

    @keyframes loading-bar {
        0% {
            transform: translateX(-100%);
        }

        50% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-scan {
        animation: scan 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    .animate-loading-bar {
        animation: loading-bar 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Change text slightly before hiding to simulate steps
        setTimeout(() => {
            const loaderText = document.getElementById('loader-text');
            if (loaderText) loaderText.style.opacity = '0';
        }, 800);

        setTimeout(function () {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.add('opacity-0', 'pointer-events-none');
                // Remove from DOM for performance after fade out
                setTimeout(() => loader.remove(), 500);
            }
        }, 1200); // Minimum view time
    });

    // Handle back/forward cache
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            const loader = document.getElementById('global-loader');
            if (loader) loader.remove();
        }
    });
</script>