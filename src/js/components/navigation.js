/**
 * Navigation Component
 * Handles mobile navigation, sticky nav, and menu interactions
 */

class Navigation {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            toggleSelector: '.navbar-toggler',
            collapseSelector: '.navbar-collapse',
            menuSelector: '.navbar-nav',
            dropdownSelector: '.dropdown',
            stickyClass: 'navbar-sticky',
            scrolledClass: 'navbar-scrolled',
            openClass: 'show',
            breakpoint: 992,
            stickyOffset: 0,
            hideOnScroll: false,
            hideOffset: 200,
            ...options
        };

        this.toggle = this.element.querySelector(this.options.toggleSelector);
        this.collapse = this.element.querySelector(this.options.collapseSelector);
        this.isOpen = false;
        this.lastScrollTop = 0;
        this.isSticky = this.element.classList.contains(this.options.stickyClass);

        this.init();
    }

    init() {
        // Mobile toggle
        if (this.toggle && this.collapse) {
            this.toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleMobile();
            });

            // Set ARIA attributes
            this.toggle.setAttribute('aria-expanded', 'false');
            this.toggle.setAttribute('aria-controls', this.collapse.id || 'navbar-collapse');

            if (!this.collapse.id) {
                this.collapse.id = 'navbar-collapse';
            }
        }

        // Close mobile menu on link click
        const navLinks = this.element.querySelectorAll('.nav-link:not(.dropdown-toggle)');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < this.options.breakpoint && this.isOpen) {
                    this.closeMobile();
                }
            });
        });

        // Close mobile menu on outside click
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.element.contains(e.target)) {
                this.closeMobile();
            }
        });

        // Handle resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= this.options.breakpoint && this.isOpen) {
                this.closeMobile();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeMobile();
                this.toggle?.focus();
            }
        });

        // Sticky navigation
        if (this.isSticky || this.options.hideOnScroll) {
            this.initSticky();
        }

        // Initialize dropdowns for mobile
        this.initMobileDropdowns();
    }

    toggleMobile() {
        if (this.isOpen) {
            this.closeMobile();
        } else {
            this.openMobile();
        }
    }

    openMobile() {
        if (!this.collapse) return;

        this.isOpen = true;
        this.collapse.classList.add(this.options.openClass);
        this.toggle?.setAttribute('aria-expanded', 'true');
        this.toggle?.classList.add('active');

        // Animate open
        this.collapse.style.height = '0';
        this.collapse.style.overflow = 'hidden';
        this.collapse.style.display = 'block';

        const height = this.collapse.scrollHeight;
        this.collapse.style.transition = 'height 0.3s ease';
        this.collapse.style.height = height + 'px';

        setTimeout(() => {
            this.collapse.style.height = '';
            this.collapse.style.overflow = '';
            this.collapse.style.transition = '';
        }, 300);

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('navigation:open', {
            detail: { navigation: this }
        }));
    }

    closeMobile() {
        if (!this.collapse) return;

        this.isOpen = false;
        this.toggle?.setAttribute('aria-expanded', 'false');
        this.toggle?.classList.remove('active');

        // Animate close
        const height = this.collapse.scrollHeight;
        this.collapse.style.height = height + 'px';
        this.collapse.offsetHeight; // Force reflow
        this.collapse.style.overflow = 'hidden';
        this.collapse.style.transition = 'height 0.3s ease';
        this.collapse.style.height = '0';

        setTimeout(() => {
            this.collapse.classList.remove(this.options.openClass);
            this.collapse.style.height = '';
            this.collapse.style.overflow = '';
            this.collapse.style.transition = '';
            this.collapse.style.display = '';
        }, 300);

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('navigation:close', {
            detail: { navigation: this }
        }));
    }

    initSticky() {
        const navHeight = this.element.offsetHeight;
        let placeholder = null;

        // Create placeholder for fixed nav
        if (this.isSticky) {
            placeholder = document.createElement('div');
            placeholder.style.height = '0';
            placeholder.className = 'navbar-placeholder';
            this.element.parentNode.insertBefore(placeholder, this.element);
        }

        const handleScroll = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Sticky behavior
            if (this.isSticky) {
                if (scrollTop > this.options.stickyOffset) {
                    if (!this.element.classList.contains('fixed')) {
                        this.element.classList.add('fixed');
                        this.element.style.position = 'fixed';
                        this.element.style.top = '0';
                        this.element.style.left = '0';
                        this.element.style.right = '0';
                        this.element.style.zIndex = '1030';
                        placeholder.style.height = navHeight + 'px';
                    }
                } else {
                    if (this.element.classList.contains('fixed')) {
                        this.element.classList.remove('fixed');
                        this.element.style.position = '';
                        this.element.style.top = '';
                        this.element.style.left = '';
                        this.element.style.right = '';
                        this.element.style.zIndex = '';
                        placeholder.style.height = '0';
                    }
                }
            }

            // Scrolled class (for styling changes)
            if (scrollTop > 50) {
                this.element.classList.add(this.options.scrolledClass);
            } else {
                this.element.classList.remove(this.options.scrolledClass);
            }

            // Hide on scroll down, show on scroll up
            if (this.options.hideOnScroll && scrollTop > this.options.hideOffset) {
                if (scrollTop > this.lastScrollTop) {
                    // Scrolling down
                    this.element.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up
                    this.element.style.transform = 'translateY(0)';
                }
            } else {
                this.element.style.transform = '';
            }

            this.lastScrollTop = scrollTop;
        };

        // Add transition for hide on scroll
        if (this.options.hideOnScroll) {
            this.element.style.transition = 'transform 0.3s ease';
        }

        // Throttled scroll handler
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Initial check
        handleScroll();
    }

    initMobileDropdowns() {
        const dropdowns = this.element.querySelectorAll(this.options.dropdownSelector);

        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (toggle && menu) {
                toggle.addEventListener('click', (e) => {
                    // Only handle on mobile
                    if (window.innerWidth < this.options.breakpoint) {
                        e.preventDefault();
                        e.stopPropagation();

                        const isOpen = dropdown.classList.contains(this.options.openClass);

                        // Close other dropdowns
                        dropdowns.forEach(d => {
                            if (d !== dropdown) {
                                d.classList.remove(this.options.openClass);
                                d.querySelector('.dropdown-menu')?.classList.remove(this.options.openClass);
                            }
                        });

                        // Toggle current
                        dropdown.classList.toggle(this.options.openClass);
                        menu.classList.toggle(this.options.openClass);
                        toggle.setAttribute('aria-expanded', !isOpen);
                    }
                });
            }
        });
    }

    destroy() {
        if (this.toggle) {
            this.toggle.removeEventListener('click', this.toggleMobile);
        }
    }
}

/**
 * Scroll Spy Component
 * Highlights navigation items based on scroll position
 */
class ScrollSpy {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            target: null,
            offset: 100,
            activeClass: 'active',
            smoothScroll: true,
            ...options
        };

        this.navItems = [];
        this.sections = [];

        this.init();
    }

    init() {
        // Get navigation target
        const target = this.options.target
            ? document.querySelector(this.options.target)
            : this.element;

        if (!target) return;

        // Get all nav links
        const links = target.querySelectorAll('a[href^="#"]');

        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && href !== '#') {
                const section = document.querySelector(href);
                if (section) {
                    this.navItems.push(link);
                    this.sections.push(section);

                    // Smooth scroll
                    if (this.options.smoothScroll) {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const top = section.offsetTop - this.options.offset + 1;
                            window.scrollTo({
                                top: top,
                                behavior: 'smooth'
                            });
                        });
                    }
                }
            }
        });

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
        const offset = this.options.offset;

        let currentSection = null;

        // Find current section
        this.sections.forEach((section, index) => {
            const sectionTop = section.offsetTop - offset;
            const sectionBottom = sectionTop + section.offsetHeight;

            if (scrollTop >= sectionTop && scrollTop < sectionBottom) {
                currentSection = index;
            }
        });

        // Update active states
        this.navItems.forEach((item, index) => {
            const parent = item.closest('.nav-item') || item.parentElement;

            if (index === currentSection) {
                item.classList.add(this.options.activeClass);
                parent?.classList.add(this.options.activeClass);
            } else {
                item.classList.remove(this.options.activeClass);
                parent?.classList.remove(this.options.activeClass);
            }
        });

        // Dispatch event
        if (currentSection !== null) {
            this.element.dispatchEvent(new CustomEvent('scrollspy:update', {
                detail: {
                    section: this.sections[currentSection],
                    navItem: this.navItems[currentSection]
                }
            }));
        }
    }
}

// Auto-initialize
function initNavigation() {
    // Initialize navbar
    document.querySelectorAll('.navbar').forEach(element => {
        if (!element.navigationInstance) {
            element.navigationInstance = new Navigation(element, {
                hideOnScroll: element.hasAttribute('data-hide-on-scroll'),
                stickyOffset: parseInt(element.getAttribute('data-sticky-offset')) || 0
            });
        }
    });

    // Initialize scroll spy
    document.querySelectorAll('[data-scrollspy]').forEach(element => {
        if (!element.scrollSpyInstance) {
            element.scrollSpyInstance = new ScrollSpy(element, {
                target: element.getAttribute('data-scrollspy-target'),
                offset: parseInt(element.getAttribute('data-scrollspy-offset')) || 100
            });
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNavigation);
} else {
    initNavigation();
}

export { Navigation, ScrollSpy };

