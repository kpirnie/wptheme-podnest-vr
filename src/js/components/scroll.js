/**
 * Scroll Component
 * Handles smooth scrolling, back to top, and scroll animations
 */

class SmoothScroll {
    constructor(options = {}) {
        this.options = {
            selector: 'a[href^="#"]:not([href="#"])',
            offset: 0,
            duration: 800,
            easing: 'easeInOutCubic',
            updateUrl: true,
            ...options
        };

        this.easings = {
            linear: t => t,
            easeInQuad: t => t * t,
            easeOutQuad: t => t * (2 - t),
            easeInOutQuad: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
            easeInCubic: t => t * t * t,
            easeOutCubic: t => (--t) * t * t + 1,
            easeInOutCubic: t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
            easeInQuart: t => t * t * t * t,
            easeOutQuart: t => 1 - (--t) * t * t * t,
            easeInOutQuart: t => t < 0.5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t
        };

        this.init();
    }

    init() {
        document.querySelectorAll(this.options.selector).forEach(link => {
            link.addEventListener('click', (e) => this.handleClick(e, link));
        });
    }

    handleClick(e, link) {
        const href = link.getAttribute('href');
        const target = document.querySelector(href);

        if (target) {
            e.preventDefault();
            this.scrollTo(target, {
                callback: () => {
                    // Update URL
                    if (this.options.updateUrl) {
                        history.pushState(null, null, href);
                    }

                    // Set focus for accessibility
                    target.setAttribute('tabindex', '-1');
                    target.focus({ preventScroll: true });
                }
            });
        }
    }

    scrollTo(target, options = {}) {
        const config = {
            offset: this.options.offset,
            duration: this.options.duration,
            easing: this.options.easing,
            callback: null,
            ...options
        };

        const targetElement = typeof target === 'string' ? document.querySelector(target) : target;

        if (!targetElement) return;

        const startPosition = window.pageYOffset;
        const targetPosition = targetElement.getBoundingClientRect().top + startPosition - config.offset;
        const distance = targetPosition - startPosition;
        const startTime = performance.now();

        const easeFunc = this.easings[config.easing] || this.easings.easeInOutCubic;

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / config.duration, 1);
            const easeProgress = easeFunc(progress);

            window.scrollTo(0, startPosition + distance * easeProgress);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                if (config.callback) {
                    config.callback();
                }
            }
        };

        requestAnimationFrame(animate);
    }

    scrollToTop(options = {}) {
        const config = {
            duration: this.options.duration,
            easing: this.options.easing,
            ...options
        };

        const startPosition = window.pageYOffset;
        const startTime = performance.now();

        const easeFunc = this.easings[config.easing] || this.easings.easeInOutCubic;

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / config.duration, 1);
            const easeProgress = easeFunc(progress);

            window.scrollTo(0, startPosition * (1 - easeProgress));

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }
}

/**
 * Back to Top Button
 */
class BackToTop {
    constructor(options = {}) {
        this.options = {
            selector: '.back-to-top',
            showOffset: 300,
            duration: 800,
            easing: 'easeInOutCubic',
            showClass: 'show',
            ...options
        };

        this.button = document.querySelector(this.options.selector);
        this.smoothScroll = new SmoothScroll();
        this.isVisible = false;

        if (this.button) {
            this.init();
        }
    }

    init() {
        // Click handler
        this.button.addEventListener('click', (e) => {
            e.preventDefault();
            this.smoothScroll.scrollToTop({
                duration: this.options.duration,
                easing: this.options.easing
            });
        });

        // Scroll handler
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    this.toggleVisibility();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Initial check
        this.toggleVisibility();
    }

    toggleVisibility() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > this.options.showOffset) {
            if (!this.isVisible) {
                this.isVisible = true;
                this.button.classList.add(this.options.showClass);
                this.button.setAttribute('aria-hidden', 'false');
            }
        } else {
            if (this.isVisible) {
                this.isVisible = false;
                this.button.classList.remove(this.options.showClass);
                this.button.setAttribute('aria-hidden', 'true');
            }
        }
    }
}

/**
 * Scroll Reveal Animation
 */
class ScrollReveal {
    constructor(options = {}) {
        this.options = {
            selector: '[data-scroll-reveal]',
            rootMargin: '0px 0px -100px 0px',
            threshold: 0,
            activeClass: 'revealed',
            once: true,
            ...options
        };

        this.elements = document.querySelectorAll(this.options.selector);

        if (this.elements.length > 0) {
            this.init();
        }
    }

    init() {
        // Check for IntersectionObserver support
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(
                (entries) => this.handleIntersect(entries),
                {
                    rootMargin: this.options.rootMargin,
                    threshold: this.options.threshold
                }
            );

            this.elements.forEach(element => {
                this.observer.observe(element);
            });
        } else {
            // Fallback: reveal all elements
            this.elements.forEach(element => {
                element.classList.add(this.options.activeClass);
            });
        }
    }

    handleIntersect(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const delay = element.getAttribute('data-scroll-delay') || 0;

                setTimeout(() => {
                    element.classList.add(this.options.activeClass);

                    // Dispatch event
                    element.dispatchEvent(new CustomEvent('scrollreveal:reveal', {
                        detail: { element }
                    }));
                }, parseInt(delay));

                // Unobserve if once
                if (this.options.once) {
                    this.observer.unobserve(element);
                }
            } else if (!this.options.once) {
                entry.target.classList.remove(this.options.activeClass);
            }
        });
    }

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
}

/**
 * Scroll Progress Indicator
 */
class ScrollProgress {
    constructor(options = {}) {
        this.options = {
            selector: '.scroll-progress',
            barSelector: '.scroll-progress-bar',
            position: 'top',
            height: '4px',
            color: '#3b82f6',
            zIndex: 9999,
            ...options
        };

        this.container = document.querySelector(this.options.selector);

        this.init();
    }

    init() {
        // Create progress bar if not exists
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'scroll-progress';
            this.container.style.cssText = `
                position: fixed;
                ${this.options.position}: 0;
                left: 0;
                width: 100%;
                height: ${this.options.height};
                background-color: transparent;
                z-index: ${this.options.zIndex};
                pointer-events: none;
            `;

            this.bar = document.createElement('div');
            this.bar.className = 'scroll-progress-bar';
            this.bar.style.cssText = `
                height: 100%;
                width: 0%;
                background-color: ${this.options.color};
                transition: width 0.1s linear;
            `;

            this.container.appendChild(this.bar);
            document.body.appendChild(this.container);
        } else {
            this.bar = this.container.querySelector(this.options.barSelector) || this.container;
        }

        // Scroll handler
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    this.update();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Initial update
        this.update();
    }

    update() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;

        this.bar.style.width = `${progress}%`;

        // Dispatch event
        document.dispatchEvent(new CustomEvent('scrollprogress:update', {
            detail: { progress }
        }));
    }

    destroy() {
        if (this.container && this.container.parentNode) {
            this.container.parentNode.removeChild(this.container);
        }
    }
}

/**
 * Infinite Scroll
 */
class InfiniteScroll {
    constructor(options = {}) {
        this.options = {
            container: null,
            loadMore: null,
            threshold: 200,
            loading: false,
            hasMore: true,
            loadingClass: 'loading',
            onLoad: null,
            ...options
        };

        this.container = typeof this.options.container === 'string'
            ? document.querySelector(this.options.container)
            : this.options.container;

        if (this.container) {
            this.init();
        }
    }

    init() {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    this.checkScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

    checkScroll() {
        if (this.options.loading || !this.options.hasMore) return;

        const containerRect = this.container.getBoundingClientRect();
        const containerBottom = containerRect.bottom;
        const windowHeight = window.innerHeight;

        if (containerBottom - windowHeight <= this.options.threshold) {
            this.load();
        }
    }

    async load() {
        if (this.options.loading || !this.options.hasMore) return;

        this.options.loading = true;
        this.container.classList.add(this.options.loadingClass);

        // Dispatch loading event
        this.container.dispatchEvent(new CustomEvent('infinitescroll:loading'));

        try {
            if (this.options.onLoad) {
                const result = await this.options.onLoad();

                if (result === false) {
                    this.options.hasMore = false;
                }
            }
        } catch (error) {
            console.error('Infinite scroll load error:', error);

            // Dispatch error event
            this.container.dispatchEvent(new CustomEvent('infinitescroll:error', {
                detail: { error }
            }));
        } finally {
            this.options.loading = false;
            this.container.classList.remove(this.options.loadingClass);

            // Dispatch loaded event
            this.container.dispatchEvent(new CustomEvent('infinitescroll:loaded'));
        }
    }

    setHasMore(value) {
        this.options.hasMore = value;
    }

    destroy() {
        window.removeEventListener('scroll', this.checkScroll);
    }
}

// Initialized by PNVR.initScroll() in main.js

export {
    BackToTop, SmoothScroll as default, InfiniteScroll, ScrollProgress, ScrollReveal
};
