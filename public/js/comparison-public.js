/**
 * Comparison Plugin - Public JavaScript
 * Updated version with card view functionality removed
 * Keeps table/list view (v2) and redirect popup functionality
 */

(function() {
    'use strict';

    // Popup redirect variables
    let redirectTimeout;
    let currentRedirectUrl = '';
    let redirectSettings = {
        redirectDelay: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.redirectDelay : 3000,
        redirectTitle: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.redirectTitle : 'Thank you for visiting!',
        safeText: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.safeText : 'You are now being redirected...',
        loadingText: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.loadingText : 'PLEASE WAIT',
        fallbackText: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.fallbackText : 'If you are not forwarded',
        buttonText: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.buttonText : 'CLICK HERE',
        terms: window.comparisonRedirectSettings ? window.comparisonRedirectSettings.terms : []
    };

    // Function to open redirect popup
    function openRedirectPopup(url, brandName, brandLogo, affiliateUrl) {
        // Use affiliate URL for the actual redirect, fallback to redirect URL
        currentRedirectUrl = affiliateUrl || url;
        
        const overlay = document.getElementById('com-redirect-overlay');
        const redirectBox = document.getElementById('com-redirect-box');
        
        if (!overlay || !redirectBox) {
            // Fallback to direct redirect if popup elements don't exist
            window.location.href = url;
            return false;
        }
        
        // Update brand logo if provided
        const logoElement = document.getElementById('com-redirect-brand-logo');
        if (logoElement) {
            if (brandLogo) {
                logoElement.innerHTML = '<img src="' + brandLogo + '" alt="' + brandName + '">';
                logoElement.style.display = 'block';
            } else {
                logoElement.style.display = 'none';
            }
        }
        
        // Update dynamic text with brand name
        let titleText = redirectSettings.redirectTitle;
        if (brandName) {
            titleText = titleText.replace('{BRAND_NAME}', brandName);
        }
        const titleElement = document.getElementById('com-redirect-title');
        if (titleElement) {
            titleElement.textContent = titleText;
        }
        
        let safeText = redirectSettings.safeText;
        if (brandName) {
            safeText = safeText.replace('{BRAND_NAME}', brandName);
        }
        const safeElement = document.getElementById('com-safe-redirect');
        if (safeElement) {
            safeElement.textContent = safeText;
        }
        
        // Update fallback button href
        const clickHereBtn = document.getElementById('com-redirect-clickhere');
        if (clickHereBtn) {
            clickHereBtn.href = affiliateUrl || url;
        }
        
        // Show the popup
        overlay.classList.add('active');
        redirectBox.classList.add('active');
        
        // Reset and start loading animation
        const loadingFill = redirectBox.querySelector('.com-redirect-loading-fill');
        if (loadingFill) {
            // Reset animation
            loadingFill.style.transition = 'none';
            loadingFill.style.width = '0%';
            
            // Force browser reflow
            void loadingFill.offsetWidth;
            
            // Set transition duration based on redirect delay
            const duration = redirectSettings.redirectDelay / 1000;
            loadingFill.style.transition = 'width ' + duration + 's linear';
            
            // Start animation
            setTimeout(function() {
                loadingFill.style.width = '100%';
            }, 50);
        }
        
        // Auto redirect after delay
        redirectTimeout = setTimeout(function() {
            if (currentRedirectUrl) {
                window.location.href = url; // Use the original URL for tracking
            }
            closeRedirectPopup();
        }, redirectSettings.redirectDelay);
        
        return false;
    }

    // Function to close redirect popup
    function closeRedirectPopup() {
        const overlay = document.getElementById('com-redirect-overlay');
        const redirectBox = document.getElementById('com-redirect-box');
        
        if (overlay && redirectBox) {
            overlay.classList.remove('active');
            redirectBox.classList.remove('active');
        }
        
        // Clear timeout if closed early
        if (redirectTimeout) {
            clearTimeout(redirectTimeout);
            redirectTimeout = null;
        }
        
        currentRedirectUrl = '';
        
        // Reset loading bar after animation
        setTimeout(function() {
            const loadingFill = document.querySelector('.com-redirect-loading-fill');
            if (loadingFill) {
                loadingFill.style.transition = 'none';
                loadingFill.style.width = '0%';
            }
        }, 300);
    }

    // Create popup HTML if it doesn't exist
    function createPopupHTML() {
        if (!document.getElementById('com-redirect-overlay')) {
            const popupHtml = `
                <!-- Comparison Redirect Overlay -->
                <div id="com-redirect-overlay" class="com-redirect-overlay"></div>
                
                <!-- Comparison Redirect Box -->
                <div id="com-redirect-box" class="com-redirect-box">
                    <button class="com-redirect-close-btn" onclick="return false;"></button>
                    
                    <div id="com-redirect-brand-logo" class="com-redirect-brand-logo"></div>
                    
                    <h3 id="com-redirect-title">${redirectSettings.redirectTitle}</h3>
                    <p id="com-safe-redirect">${redirectSettings.safeText}</p>
                    
                    <div class="com-redirect-loading-bar">
                        <div class="com-redirect-loading-fill"></div>
                    </div>
                    
                    <div class="com-redirect-loading-text">${redirectSettings.loadingText}</div>
                    
                    <div class="com-redirect-fallback-text">
                        ${redirectSettings.fallbackText}
                        <a href="#" id="com-redirect-clickhere" rel="nofollow noopener sponsored">${redirectSettings.buttonText}</a>
                    </div>
                    
                    ${redirectSettings.terms.length > 0 ? 
                        '<div class="com-redirect-terms-text">' + redirectSettings.terms.join(' • ') + '</div>' : 
                        ''}
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', popupHtml);
        }
    }

    // ====================
    // MAIN FUNCTIONALITY
    // ====================
    
    // REMOVED: Toggle terms functionality for card view
    // This was used for the dropdown terms in card view which is no longer needed

    window.addEventListener("DOMContentLoaded", function() {
        // Create popup HTML on DOM ready
        createPopupHTML();
        
        // Isotope grid item click (category filter)
        document.addEventListener("click", function(t) {
            var e, n = t.target;
            if (n.classList.contains("isotop__grid--item") || (n = n.closest(".isotop__grid--item")), 
                null === (e = n) || void 0 === e || !e.classList.contains("isotop__grid--item")) 
                return !0;
            
            t.preventDefault();
            t.stopPropagation();
            
            var i = n.closest(".isotop__grid").querySelectorAll(".isotop__grid--list"),
                o = n.closest(".com-comparison-plugin").getElementsByClassName("com_comarison__list--container");
            
            i.forEach(function(t) {
                t.classList.remove("active");
            });
            
            Array.from(o).forEach(function(t) {
                t.classList.contains(n.dataset.key) ? t.classList.remove("hidden") : t.classList.add("hidden");
            });
            
            n.parentNode.classList.add("active");
            return !1;
        });

        // Info open button (for table extra info)
        document.addEventListener("click", function(t) {
            var e, n = t.target;
            if (!n.classList.contains("js-info-open")) return !0;
            t.preventDefault();
            t.stopPropagation();
            null === (e = n.closest(".item.hidden-info")) || void 0 === e || e.classList.remove("hidden-info");
        });

        // Info close button (for table extra info)
        document.addEventListener("click", function(t) {
            var e = t.target;
            if (!e.classList.contains("js-info-close")) return !0;
            t.preventDefault();
            t.stopPropagation();
            e.closest(".item").classList.add("hidden-info");
        });

        // Menu toggle (mobile dropdown)
        document.addEventListener("click", function(t) {
            var e = t.target;
            if (!e.classList.contains("com__btn") && !e.classList.contains("com__icon--menu")) return !0;
            t.preventDefault();
            t.stopPropagation();
            var n = e.closest(".com-comparison-plugin").getElementsByClassName("isotop__grid");
            return n[0].classList.contains("hidden") ? n[0].classList.remove("hidden") : n[0].classList.add("hidden"), !1;
        });

        // REMOVED: Load more cards handler for card view
        // This was the com_btn--loadmore handler that called /getcards endpoint

        // Load more LIST cards (for table/list view) - KEPT
        document.addEventListener("click", function(t) {
            var e = t.target;
            if (!e.classList.contains("com_btn-list--loadmore")) return !0;
            t.preventDefault();
            t.stopPropagation();
            
            var n = e.getAttribute("data-list"),
                i = e.getAttribute("data-limit"),
                o = e.getAttribute("data-resturl"),
                s = e.getAttribute("data-id");
            
            fetch(o + "/wp-json/comparison/v1/get_list_cards", {
                body: "p=" + JSON.stringify({category: n, offset: i, list_id: s}),
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                method: "post"
            })
            .then(function(t) { return t.json(); })
            .then(function(t) {
                if (200 == t.status) {
                    var n = e.closest(".com_comarison__list--container"),
                        i = n.querySelector(".com_comparision_table-container");
                    setTimeout(function() {
                        n.classList.add("animate");
                    }, 500);
                    i.insertAdjacentHTML("beforeend", t.data);
                    e.remove();
                }
            });
        });

        // ====================
        // POPUP REDIRECT FUNCTIONALITY
        // ====================
        
        // Intercept clicks on redirect links
        document.addEventListener("click", function(e) {
            const target = e.target;
            let link = target;
            
            // Check if clicked element or its parent is a redirect link
            if (!link.matches('a[href*="/redirect/"]')) {
                link = target.closest('a[href*="/redirect/"]');
            }
            
            // Also check for buttons with specific classes (table view buttons)
            if (!link && (target.classList.contains('comparison-bonus-list-submit__button') || 
                         target.closest('.comparison-bonus-list-submit__button'))) {
                link = target.classList.contains('comparison-bonus-list-submit__button') ? 
                       target : target.closest('.comparison-bonus-list-submit__button');
            }
            
            if (link && link.href && link.href.includes('/redirect/')) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the affiliate URL from data attribute
                const affiliateUrl = link.dataset.affiliateUrl || link.href;
                
                // Try to get brand data from the table row or item
                const item = link.closest('.item, .com-comparison');
                let brandName = '';
                let brandLogo = '';
                
                if (item) {
                    // Try different selectors for brand name (table view)
                    const nameElement = item.querySelector('.comparison-brand-wrap h4, h3, h4');
                    if (nameElement) {
                        brandName = nameElement.textContent.trim();
                    }
                    
                    // Try different selectors for brand logo (table view)
                    const logoElement = item.querySelector('.comparison-brand-link img, img');
                    if (logoElement) {
                        brandLogo = logoElement.src;
                    }
                }
                
                // Fallback to data attributes
                brandName = brandName || link.dataset.brandName || item?.dataset.brandName || 'this brand';
                brandLogo = brandLogo || link.dataset.brandLogo || item?.dataset.brandLogo || '';
                
                // Open popup with affiliate URL
                openRedirectPopup(link.href, brandName, brandLogo, affiliateUrl);
                
                return false;
            }
        });
        
        // Close button click
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains('com-redirect-close-btn') || 
                e.target.parentElement?.classList.contains('com-redirect-close-btn')) {
                e.preventDefault();
                closeRedirectPopup();
            }
        });
        
        // Overlay click to close
        document.addEventListener("click", function(e) {
            if (e.target.id === 'com-redirect-overlay') {
                e.preventDefault();
                closeRedirectPopup();
            }
        });
        
        // Close on Escape key
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape" && document.getElementById('com-redirect-box')?.classList.contains('active')) {
                closeRedirectPopup();
            }
        });
    });

})();
