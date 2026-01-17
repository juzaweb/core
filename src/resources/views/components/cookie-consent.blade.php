@if(setting('cookie_consent_enabled'))
<div id="cookie-consent-banner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-container">
        <div class="cookie-consent-content">
            <p class="cookie-consent-message">
                {{ setting('cookie_consent_message') ?: __('admin::translation.cookie_consent_message_default') }}
            </p>
        </div>
        <div class="cookie-consent-actions">
            <button id="cookie-consent-reject" class="cookie-consent-button cookie-consent-reject">
                {{ __('admin::translation.cookie_consent_reject') }}
            </button>
            <button id="cookie-consent-accept" class="cookie-consent-button cookie-consent-accept">
                {{ __('admin::translation.cookie_consent_accept') }}
            </button>
        </div>
    </div>
</div>

<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.95);
    color: #fff;
    padding: 1.5rem;
    z-index: 9999;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.cookie-consent-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.cookie-consent-content {
    flex: 1;
    min-width: 250px;
}

.cookie-consent-message {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
}

.cookie-consent-actions {
    display: flex;
    gap: 0.5rem;
}

.cookie-consent-button {
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.cookie-consent-accept {
    background-color: #28a745;
    color: #fff;
}

.cookie-consent-accept:hover {
    background-color: #218838;
}

.cookie-consent-reject {
    background-color: #6c757d;
    color: #fff;
}

.cookie-consent-reject:hover {
    background-color: #5a6268;
}

@media (max-width: 768px) {
    .cookie-consent-container {
        flex-direction: column;
        text-align: center;
    }
    
    .cookie-consent-actions {
        width: 100%;
        justify-content: center;
    }
    
    .cookie-consent-button {
        flex: 1;
    }
}
</style>

<script>
(function() {
    'use strict';
    
    const COOKIE_NAME = 'cookie_consent_accepted';
    const COOKIE_EXPIRY_DAYS = 365;
    
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
    }
    
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    
    function showBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }
    
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }
    
    function acceptCookies() {
        setCookie(COOKIE_NAME, 'true', COOKIE_EXPIRY_DAYS);
        hideBanner();
    }
    
    function rejectCookies() {
        setCookie(COOKIE_NAME, 'false', COOKIE_EXPIRY_DAYS);
        hideBanner();
    }
    
    function init() {
        // Check if user has already made a choice
        if (!getCookie(COOKIE_NAME)) {
            showBanner();
        }
        
        // Add event listener to accept button
        const acceptBtn = document.getElementById('cookie-consent-accept');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', acceptCookies);
        }
        
        // Add event listener to reject button
        const rejectBtn = document.getElementById('cookie-consent-reject');
        if (rejectBtn) {
            rejectBtn.addEventListener('click', rejectCookies);
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endif
