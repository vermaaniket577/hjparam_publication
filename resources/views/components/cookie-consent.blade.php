<!-- HJPARAM Cookie Consent Banner -->
<div id="hjparam-cookie-banner" 
     style="position: fixed !important; bottom: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 2147483647 !important; padding: 16px !important; pointer-events: none !important; box-sizing: border-box !important; display: none; opacity: 0; transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;">
    
    <div style="max-width: 960px !important; margin: 0 auto !important; background-color: #ffffff !important; color: #1e293b !important; border-radius: 16px !important; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.06) !important; border: 1px solid #e2e8f0 !important; padding: 20px 24px !important; pointer-events: auto !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important; box-sizing: border-box !important;">
        
        <div style="display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: space-between !important; gap: 20px !important; flex-wrap: wrap !important;">
            
            <!-- Left: Icon & Text -->
            <div style="display: flex !important; align-items: flex-start !important; gap: 16px !important; flex: 1 1 340px !important; min-width: 280px !important;">
                <div style="width: 44px !important; height: 44px !important; border-radius: 12px !important; background-color: #eff6ff !important; border: 1px solid #bfdbfe !important; display: flex !important; align-items: center !important; justify-content: center !important; flex-shrink: 0 !important; color: #2563eb !important; margin-top: 2px !important;">
                    <svg style="width: 22px !important; height: 22px !important;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                
                <div style="display: flex !important; flex-direction: column !important; gap: 4px !important;">
                    <div style="display: flex !important; align-items: center !important; gap: 8px !important; flex-wrap: wrap !important;">
                        <span style="font-size: 15px !important; font-weight: 700 !important; color: #0f172a !important; line-height: 1.3 !important;">
                            We respect your privacy & cookies
                        </span>
                        <span style="display: inline-block !important; padding: 2px 8px !important; font-size: 10px !important; font-weight: 700 !important; background-color: #ecfdf5 !important; color: #047857 !important; border: 1px solid #a7f3d0 !important; border-radius: 9999px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important;">
                            GDPR / ePrivacy
                        </span>
                    </div>
                    
                    <p style="font-size: 13px !important; color: #475569 !important; line-height: 1.5 !important; margin: 0 !important; font-weight: 400 !important;">
                        We use cookies and secure browser sessions to ensure smooth authentication, personalize your scholarly reading experience, and analyze platform traffic. Read our 
                        <a href="{{ route('info.page', 'privacy') }}#cookies" style="color: #2563eb !important; text-decoration: underline !important; font-weight: 600 !important;">Cookie Policy</a> 
                        and 
                        <a href="{{ route('info.page', 'privacy') }}" style="color: #2563eb !important; text-decoration: underline !important; font-weight: 600 !important;">Privacy Policy</a>.
                    </p>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div style="display: flex !important; align-items: center !important; gap: 10px !important; flex-wrap: wrap !important; flex-shrink: 0 !important;">
                <button type="button" 
                        id="hjparam-btn-essential"
                        onclick="window.hjparamAcceptEssential(this, event)"
                        style="padding: 10px 18px !important; border-radius: 10px !important; font-size: 13px !important; font-weight: 600 !important; color: #334155 !important; background-color: #f1f5f9 !important; border: 1px solid #cbd5e1 !important; cursor: pointer !important; transition: all 0.2s ease !important; outline: none !important; font-family: inherit !important;"
                        onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.borderColor='#94a3b8';"
                        onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.borderColor='#cbd5e1';">
                    Essential Only
                </button>

                <button type="button" 
                        id="hjparam-btn-accept"
                        onclick="window.hjparamAcceptAll(this, event)"
                        style="padding: 10px 22px !important; border-radius: 10px !important; font-size: 13px !important; font-weight: 700 !important; color: #ffffff !important; background-color: #2563eb !important; border: 1px solid #1d4ed8 !important; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important; cursor: pointer !important; transition: all 0.2s ease !important; outline: none !important; white-space: nowrap !important; font-family: inherit !important;"
                        onmouseover="this.style.backgroundColor='#1d4ed8'; this.style.transform='translateY(-1px)';"
                        onmouseout="this.style.backgroundColor='#2563eb'; this.style.transform='translateY(0)';"
                        onmousedown="this.style.transform='translateY(1px)';">
                    Accept All Cookies
                </button>
            </div>

        </div>
    </div>
</div>

<script>
// Expose global handlers immediately to guarantee availability
window.hjparamCookieConsentKey = 'hjparam_cookie_consent';

window.hjparamSetCookie = function(name, value, days) {
    try {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        var secure = (window.location && window.location.protocol === 'https:') ? '; Secure' : '';
        document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax" + secure;
    } catch (e) {
        console.warn('Cookie set error:', e);
    }
};

window.hjparamGetCookie = function(name) {
    try {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
    } catch (e) {}
    return null;
};

window.hjparamHasConsent = function() {
    try {
        if (localStorage.getItem(window.hjparamCookieConsentKey)) {
            return true;
        }
    } catch (e) {}
    try {
        if (window.hjparamGetCookie(window.hjparamCookieConsentKey)) {
            return true;
        }
    } catch (e) {}
    return false;
};

window.hjparamHideCookieBanner = function() {
    var banner = document.getElementById('hjparam-cookie-banner');
    if (!banner) return;
    banner.style.opacity = '0';
    banner.style.transform = 'translateY(100%)';
    setTimeout(function() {
        banner.style.setProperty('display', 'none', 'important');
    }, 400);
};

window.hjparamShowCookieBanner = function() {
    var banner = document.getElementById('hjparam-cookie-banner');
    if (!banner) return;
    banner.style.removeProperty('display');
    banner.style.display = 'block';
    setTimeout(function() {
        banner.style.opacity = '1';
        banner.style.transform = 'translateY(0)';
    }, 50);
};

window.hjparamAcceptAll = function(btn, e) {
    if (e) {
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
    }
    
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '✓ Accepted';
        btn.style.backgroundColor = '#16a34a';
        btn.style.borderColor = '#15803d';
    }

    var payload = {
        status: 'all',
        essential: true,
        analytics: true,
        marketing: true,
        timestamp: new Date().toISOString()
    };

    try {
        localStorage.setItem(window.hjparamCookieConsentKey, JSON.stringify(payload));
    } catch (err) {}

    window.hjparamSetCookie(window.hjparamCookieConsentKey, 'all', 365);

    try {
        var event = new CustomEvent('hjparam:cookie-consent-updated', { detail: payload });
        window.dispatchEvent(event);
        document.dispatchEvent(event);
    } catch (evtErr) {}

    setTimeout(function() {
        window.hjparamHideCookieBanner();
    }, 200);
};

window.hjparamAcceptEssential = function(btn, e) {
    if (e) {
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '✓ Saved';
    }

    var payload = {
        status: 'essential',
        essential: true,
        analytics: false,
        marketing: false,
        timestamp: new Date().toISOString()
    };

    try {
        localStorage.setItem(window.hjparamCookieConsentKey, JSON.stringify(payload));
    } catch (err) {}

    window.hjparamSetCookie(window.hjparamCookieConsentKey, 'essential', 365);

    try {
        var event = new CustomEvent('hjparam:cookie-consent-updated', { detail: payload });
        window.dispatchEvent(event);
        document.dispatchEvent(event);
    } catch (evtErr) {}

    setTimeout(function() {
        window.hjparamHideCookieBanner();
    }, 200);
};

window.hjparamOpenCookiePreferences = function() {
    try {
        localStorage.removeItem(window.hjparamCookieConsentKey);
    } catch (e) {}
    window.hjparamSetCookie(window.hjparamCookieConsentKey, '', -1);
    
    var banner = document.getElementById('hjparam-cookie-banner');
    if (banner) {
        var btnAccept = document.getElementById('hjparam-btn-accept');
        var btnEssential = document.getElementById('hjparam-btn-essential');
        if (btnAccept) {
            btnAccept.disabled = false;
            btnAccept.innerHTML = 'Accept All Cookies';
            btnAccept.style.backgroundColor = '#2563eb';
            btnAccept.style.borderColor = '#1d4ed8';
        }
        if (btnEssential) {
            btnEssential.disabled = false;
            btnEssential.innerHTML = 'Essential Only';
        }
        window.hjparamShowCookieBanner();
    } else {
        window.location.reload();
    }
};

// Auto-run banner check on load
(function() {
    function initBanner() {
        if (!window.hjparamHasConsent()) {
            setTimeout(function() {
                window.hjparamShowCookieBanner();
            }, 300);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBanner);
    } else {
        initBanner();
    }
})();
</script>
