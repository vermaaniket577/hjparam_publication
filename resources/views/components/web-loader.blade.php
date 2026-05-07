<div id="global-loader"
    class="fixed inset-0 z-[9999] bg-white flex flex-col items-center justify-center transition-opacity duration-500 ease-in-out">
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
    body.is-loading {
        cursor: progress;
    }

    .btn-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }

    .btn-loading::after {
        content: "";
        position: absolute;
        width: 1em;
        height: 1em;
        top: 50%;
        left: 50%;
        margin-top: -0.5em;
        margin-left: -0.5em;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

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
    window.showGlobalLoader = function (message = 'Processing your request...') {
        const loader = document.getElementById('global-loader');
        const loaderText = document.getElementById('loader-text');

        if (!loader) return;

        if (loaderText) {
            loaderText.textContent = message;
            loaderText.style.opacity = '1';
        }

        loader.classList.remove('opacity-0', 'pointer-events-none');
        document.body.classList.add('is-loading');
    };

    window.hideGlobalLoader = function () {
        const loader = document.getElementById('global-loader');
        const loaderText = document.getElementById('loader-text');

        if (!loader) return;

        loader.classList.add('opacity-0', 'pointer-events-none');
        document.body.classList.remove('is-loading');

        if (loaderText) {
            loaderText.style.opacity = '0';
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            const loaderText = document.getElementById('loader-text');
            if (loaderText) loaderText.style.opacity = '0';
        }, 800);

        setTimeout(function () {
            window.hideGlobalLoader();
        }, 1200);

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('a, button, [data-show-loader], .btn-loading-trigger');

            if (!trigger || event.defaultPrevented) return;

            if (trigger.matches('button[type="button"], button[type="reset"]')) return;
            if (trigger.closest('[x-data]') && !trigger.closest('form') && !trigger.matches('a[href]')) return;

            const link = trigger.closest('a[href]');
            if (link) {
                const href = link.getAttribute('href') || '';
                const isModifiedClick = event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;

                if (
                    isModifiedClick ||
                    link.target === '_blank' ||
                    link.hasAttribute('download') ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:')
                ) {
                    return;
                }

                window.showGlobalLoader('Loading page...');
                return;
            }

            if (trigger.matches('.btn-loading-trigger')) {
                window.showGlobalLoader('Processing your request...');
            }
        });

        document.addEventListener('submit', function (event) {
            if (event.defaultPrevented) return;

            const form = event.target;
            const submitter = event.submitter;

            if (form.hasAttribute('data-no-loader')) return;
            if (submitter && submitter.hasAttribute('formnovalidate')) return;

            if (submitter) {
                submitter.classList.add('btn-loading');
            }

            window.showGlobalLoader('Submitting...');
        });
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) window.hideGlobalLoader();
    });

    window.addEventListener('pagehide', function () {
        window.showGlobalLoader('Loading page...');
    });
</script>
