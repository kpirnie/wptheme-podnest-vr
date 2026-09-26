/**
 * Tooltip Component
 * Handles tooltips and popovers with positioning
 */

class Tooltip {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            placement: 'top',
            trigger: 'hover',
            content: '',
            html: false,
            delay: { show: 0, hide: 0 },
            offset: 8,
            container: document.body,
            customClass: '',
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            ...options
        };

        this.tooltip = null;
        this.isVisible = false;
        this.showTimeout = null;
        this.hideTimeout = null;

        this.init();
    }

    init() {
        // Get content from attribute if not provided
        if (!this.options.content) {
            this.options.content = this.element.getAttribute('data-tooltip') ||
                this.element.getAttribute('title') || '';
            // Remove title to prevent native tooltip
            this.element.removeAttribute('title');
        }

        // Get placement from attribute
        if (this.element.hasAttribute('data-tooltip-position')) {
            this.options.placement = this.element.getAttribute('data-tooltip-position');
        }

        // Set up triggers
        const triggers = this.options.trigger.split(' ');

        triggers.forEach(trigger => {
            switch (trigger) {
                case 'hover':
                    this.element.addEventListener('mouseenter', () => this.show());
                    this.element.addEventListener('mouseleave', () => this.hide());
                    break;

                case 'focus':
                    this.element.addEventListener('focus', () => this.show());
                    this.element.addEventListener('blur', () => this.hide());
                    break;

                case 'click':
                    this.element.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggle();
                    });
                    break;

                case 'manual':
                    // Manual trigger - no automatic events
                    break;
            }
        });

        // Accessibility
        this.element.setAttribute('aria-describedby', '');
    }

    createTooltip() {
        const template = document.createElement('div');
        template.innerHTML = this.options.template.trim();
        this.tooltip = template.firstChild;

        // Add custom class
        if (this.options.customClass) {
            this.tooltip.classList.add(...this.options.customClass.split(' '));
        }

        // Add placement class
        this.tooltip.classList.add(`tooltip-${this.options.placement}`);

        // Set content
        const inner = this.tooltip.querySelector('.tooltip-inner');
        if (inner) {
            if (this.options.html) {
                inner.innerHTML = this.options.content;
            } else {
                inner.textContent = this.options.content;
            }
        }

        // Generate unique ID
        const id = `tooltip-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        this.tooltip.id = id;
        this.element.setAttribute('aria-describedby', id);

        // Add to container
        this.options.container.appendChild(this.tooltip);
    }

    show() {
        if (this.isVisible) return;

        // Clear any pending hide
        if (this.hideTimeout) {
            clearTimeout(this.hideTimeout);
            this.hideTimeout = null;
        }

        // Apply show delay
        this.showTimeout = setTimeout(() => {
            this.doShow();
        }, this.options.delay.show || 0);
    }

    doShow() {
        // Create tooltip if needed
        if (!this.tooltip) {
            this.createTooltip();
        }

        // Dispatch before show event
        const beforeEvent = new CustomEvent('tooltip:beforeShow', {
            detail: { tooltip: this },
            cancelable: true
        });
        this.element.dispatchEvent(beforeEvent);

        if (beforeEvent.defaultPrevented) {
            return;
        }

        this.isVisible = true;

        // Position and show
        this.tooltip.style.visibility = 'hidden';
        this.tooltip.style.display = 'block';
        this.position();
        this.tooltip.style.visibility = '';
        this.tooltip.classList.add('show');

        // Dispatch show event
        this.element.dispatchEvent(new CustomEvent('tooltip:show', {
            detail: { tooltip: this }
        }));
    }

    hide() {
        if (!this.isVisible) return;

        // Clear any pending show
        if (this.showTimeout) {
            clearTimeout(this.showTimeout);
            this.showTimeout = null;
        }

        // Apply hide delay
        this.hideTimeout = setTimeout(() => {
            this.doHide();
        }, this.options.delay.hide || 0);
    }

    doHide() {
        if (!this.tooltip) return;

        // Dispatch before hide event
        const beforeEvent = new CustomEvent('tooltip:beforeHide', {
            detail: { tooltip: this },
            cancelable: true
        });
        this.element.dispatchEvent(beforeEvent);

        if (beforeEvent.defaultPrevented) {
            return;
        }

        this.isVisible = false;
        this.tooltip.classList.remove('show');

        // Remove after transition
        setTimeout(() => {
            if (this.tooltip && !this.isVisible) {
                this.tooltip.remove();
                this.tooltip = null;
                this.element.setAttribute('aria-describedby', '');
            }
        }, 150);

        // Dispatch hide event
        this.element.dispatchEvent(new CustomEvent('tooltip:hide', {
            detail: { tooltip: this }
        }));
    }

    toggle() {
        if (this.isVisible) {
            this.hide();
        } else {
            this.show();
        }
    }

    position() {
        if (!this.tooltip) return;

        const elementRect = this.element.getBoundingClientRect();
        const tooltipRect = this.tooltip.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        let top, left;
        const offset = this.options.offset;

        // Calculate position based on placement
        switch (this.options.placement) {
            case 'top':
                top = elementRect.top + scrollTop - tooltipRect.height - offset;
                left = elementRect.left + scrollLeft + (elementRect.width - tooltipRect.width) / 2;
                break;

            case 'bottom':
                top = elementRect.bottom + scrollTop + offset;
                left = elementRect.left + scrollLeft + (elementRect.width - tooltipRect.width) / 2;
                break;

            case 'left':
                top = elementRect.top + scrollTop + (elementRect.height - tooltipRect.height) / 2;
                left = elementRect.left + scrollLeft - tooltipRect.width - offset;
                break;

            case 'right':
                top = elementRect.top + scrollTop + (elementRect.height - tooltipRect.height) / 2;
                left = elementRect.right + scrollLeft + offset;
                break;
        }

        // Viewport boundary checks
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        // Flip if necessary
        if (this.options.placement === 'top' && elementRect.top - tooltipRect.height - offset < 0) {
            top = elementRect.bottom + scrollTop + offset;
            this.tooltip.classList.remove('tooltip-top');
            this.tooltip.classList.add('tooltip-bottom');
        } else if (this.options.placement === 'bottom' && elementRect.bottom + tooltipRect.height + offset > viewportHeight) {
            top = elementRect.top + scrollTop - tooltipRect.height - offset;
            this.tooltip.classList.remove('tooltip-bottom');
            this.tooltip.classList.add('tooltip-top');
        } else if (this.options.placement === 'left' && elementRect.left - tooltipRect.width - offset < 0) {
            left = elementRect.right + scrollLeft + offset;
            this.tooltip.classList.remove('tooltip-left');
            this.tooltip.classList.add('tooltip-right');
        } else if (this.options.placement === 'right' && elementRect.right + tooltipRect.width + offset > viewportWidth) {
            left = elementRect.left + scrollLeft - tooltipRect.width - offset;
            this.tooltip.classList.remove('tooltip-right');
            this.tooltip.classList.add('tooltip-left');
        }

        // Keep within horizontal bounds
        if (left < scrollLeft) {
            left = scrollLeft + offset;
        } else if (left + tooltipRect.width > scrollLeft + viewportWidth) {
            left = scrollLeft + viewportWidth - tooltipRect.width - offset;
        }

        // Apply position
        this.tooltip.style.top = `${top}px`;
        this.tooltip.style.left = `${left}px`;
    }

    updateContent(content) {
        this.options.content = content;

        if (this.tooltip) {
            const inner = this.tooltip.querySelector('.tooltip-inner');
            if (inner) {
                if (this.options.html) {
                    inner.innerHTML = content;
                } else {
                    inner.textContent = content;
                }
            }

            if (this.isVisible) {
                this.position();
            }
        }
    }

    destroy() {
        if (this.showTimeout) clearTimeout(this.showTimeout);
        if (this.hideTimeout) clearTimeout(this.hideTimeout);

        if (this.tooltip) {
            this.tooltip.remove();
            this.tooltip = null;
        }

        this.element.removeAttribute('aria-describedby');
    }
}

/**
 * Popover Component
 * Extended tooltip with title and more content
 */
class Popover extends Tooltip {
    constructor(element, options = {}) {
        super(element, {
            trigger: 'click',
            template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><div class="popover-header"></div><div class="popover-body"></div></div>',
            title: '',
            ...options
        });
    }

    init() {
        // Get title from attribute if not provided
        if (!this.options.title) {
            this.options.title = this.element.getAttribute('data-popover-title') || '';
        }

        // Get content from attribute if not provided
        if (!this.options.content) {
            this.options.content = this.element.getAttribute('data-popover-content') ||
                this.element.getAttribute('data-content') || '';
        }

        super.init();

        // Close on click outside
        document.addEventListener('click', (e) => {
            if (this.isVisible && !this.element.contains(e.target) && !this.tooltip?.contains(e.target)) {
                this.hide();
            }
        });

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (this.isVisible && e.key === 'Escape') {
                this.hide();
            }
        });
    }

    createTooltip() {
        super.createTooltip();

        // Set title
        const header = this.tooltip.querySelector('.popover-header');
        if (header) {
            if (this.options.title) {
                if (this.options.html) {
                    header.innerHTML = this.options.title;
                } else {
                    header.textContent = this.options.title;
                }
            } else {
                header.style.display = 'none';
            }
        }

        // Set content (use popover-body instead of tooltip-inner)
        const body = this.tooltip.querySelector('.popover-body');
        if (body) {
            if (this.options.html) {
                body.innerHTML = this.options.content;
            } else {
                body.textContent = this.options.content;
            }
        }

        // Update placement class
        this.tooltip.classList.remove(`tooltip-${this.options.placement}`);
        this.tooltip.classList.add(`popover-${this.options.placement}`);
    }

    updateContent(content, title = null) {
        this.options.content = content;
        if (title !== null) {
            this.options.title = title;
        }

        if (this.tooltip) {
            const header = this.tooltip.querySelector('.popover-header');
            const body = this.tooltip.querySelector('.popover-body');

            if (header && title !== null) {
                if (this.options.html) {
                    header.innerHTML = this.options.title;
                } else {
                    header.textContent = this.options.title;
                }
                header.style.display = this.options.title ? '' : 'none';
            }

            if (body) {
                if (this.options.html) {
                    body.innerHTML = content;
                } else {
                    body.textContent = content;
                }
            }

            if (this.isVisible) {
                this.position();
            }
        }
    }
}

// Auto-initialize
function initTooltips() {
    // Initialize tooltips
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        if (!element.tooltipInstance) {
            element.tooltipInstance = new Tooltip(element);
        }
    });

    // Initialize popovers
    document.querySelectorAll('[data-popover], [data-toggle="popover"]').forEach(element => {
        if (!element.popoverInstance) {
            element.popoverInstance = new Popover(element);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTooltips);
} else {
    initTooltips();
}

export { Popover, Tooltip };

