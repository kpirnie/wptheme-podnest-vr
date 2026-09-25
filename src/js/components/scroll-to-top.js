/**
 * Scroll To Top Component
 * Standalone back-to-top button with progress indicator support
 */

class ScrollToTop {
    constructor(options = {}) {
        this.options = {
            selector: '.back-to-top',
            showOffset: 300,
            duration: 600,
            easing: 'easeInOutCubic',
            showClass: 'show',
            showProgress: false,
            progressSelector: '.progress-ring-circle',
            createButton: false,
            buttonContent: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"></polyline></svg>',
            buttonClass: 'back-to-top',
            container: document.body,
            ...options
        };

        this.button = document.querySelector(this.options.selector);
        this.progressRing = null;
        this.circumference = 0;
        this.isVisible = false;

        this.easings = {
            linear: t => t,
            easeInQuad: t => t * t,
            easeOutQuad: t => t * (2 - t),
            easeInOutQuad: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
            easeInCubic: t => t * t * t,
            easeOutCubic: t => (--t) * t * t + 1,
            easeInOutCubic: t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
            easeOutBack: t => 1 + (--t) * t * (2.70158 * t + 1.70158)
        };

        this.init();
    }

    init() {
        // Create button if requested
        if (this.options.createButton && !this.button) {
            this.createButton();
        }

        if (!this.button) return;

        // Set up progress ring if enabled
        if (this.options.showProgress) {
            this.setupProgress();
        }

        // Click handler
        this.button.addEventListener('click', (e) => {
            e.preventDefault();
            this.scrollToTop();
        });

        // Keyboard support
        this.button.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.scrollToTop();
            }
        });

        // Scroll handler
        this.handleScroll = this.throttle(() => {
            this.update();
        }, 100);

        window.addEventListener('scroll', this.handleScroll, { passive: true });

        // Set accessibility attributes
        this.button.setAttribute('aria-label', 'Scroll to top');
        this.button.setAttribute('role', 'button');
        this.button.setAttribute('tabindex', '0');
        this.button.setAttribute('aria-hidden', 'true');

        // Initial update
        this.update();
    }

    createButton() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.className = this.options.buttonClass;

        if (this.options.showProgress) {
            this.button.classList.add('back-to-top-progress');
            this.button.innerHTML = `
                <svg class="progress-ring" viewBox="0 0 54 54">
                    <circle class="progress-ring-bg" cx="27" cy="27" r="24"></circle>
                    <circle class="progress-ring-circle" cx="27" cy="27" r="24"></circle>
                </svg>
                <span class="back-to-top-icon">${this.options.buttonContent}</span>
            `;
        } else {
            this.button.innerHTML = this.options.buttonContent;
        }

        this.options.container.appendChild(this.button);
    }

    setupProgress() {
        this.progressRing = this.button.querySelector(this.options.progressSelector);

        if (this.progressRing) {
            const radius = this.progressRing.getAttribute('r');
            this.circumference = 2 * Math.PI * radius;
            this.progressRing.style.strokeDasharray = `${this.circumference} ${this.circumference}`;
            this.progressRing.style.strokeDashoffset = this.circumference;
        }
    }

    update() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;

        // Toggle visibility
        if (scrollTop > this.options.showOffset) {
            this.show();
        } else {
            this.hide();
        }

        // Update progress
        if (this.options.showProgress && this.progressRing && scrollHeight > 0) {
            const progress = scrollTop / scrollHeight;
            const offset = this.circumference - (progress * this.circumference);
            this.progressRing.style.strokeDashoffset = offset;
        }
    }

    show() {
        if (this.isVisible) return;

        this.isVisible = true;
        this.button.classList.add(this.options.showClass);
        this.button.setAttribute('aria-hidden', 'false');

        // Dispatch event
        this.button.dispatchEvent(new CustomEvent('scrolltotop:show'));
    }

    hide() {
        if (!this.isVisible) return;

        this.isVisible = false;
        this.button.classList.remove(this.options.showClass);
        this.button.setAttribute('aria-hidden', 'true');

        // Dispatch event
        this.button.dispatchEvent(new CustomEvent('scrolltotop:hide'));
    }

    scrollToTop() {
        const startPosition = window.pageYOffset;
        const startTime = performance.now();
        const easeFunc = this.easings[this.options.easing] || this.easings.easeInOutCubic;

        // Dispatch start event
        this.button.dispatchEvent(new CustomEvent('scrolltotop:start'));

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / this.options.duration, 1);
            const easeProgress = easeFunc(progress);

            window.scrollTo(0, startPosition * (1 - easeProgress));

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                // Dispatch complete event
                this.button.dispatchEvent(new CustomEvent('scrolltotop:complete'));

                // Focus on top of page for accessibility
                const focusTarget = document.getElementById('top') || document.body;
                focusTarget.setAttribute('tabindex', '-1');
                focusTarget.focus({ preventScroll: true });
                focusTarget.removeAttribute('tabindex');
            }
        };

        requestAnimationFrame(animate);
    }

    throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    setOffset(offset) {
        this.options.showOffset = offset;
        this.update();
    }

    setDuration(duration) {
        this.options.duration = duration;
    }

    destroy() {
        if (!this.button) return;

        window.removeEventListener('scroll', this.handleScroll);
        this.button.removeEventListener('click', this.scrollToTop);

        if (this.options.createButton) {
            this.button.remove();
        }

        this.button = null;
    }
}

// Auto-initialize
function initScrollToTop() {
    const button = document.querySelector('.back-to-top');

    if (button && !button.scrollToTopInstance) {
        button.scrollToTopInstance = new ScrollToTop({
            showProgress: button.classList.contains('back-to-top-progress')
        });
    }
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollToTop);
} else {
    initScrollToTop();
}

export default ScrollToTop;