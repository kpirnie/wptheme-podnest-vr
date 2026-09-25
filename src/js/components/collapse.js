/**
 * Collapse Component
 * Generic expand/collapse functionality
 */

class Collapse {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            duration: 300,
            easing: 'ease',
            openClass: 'show',
            collapsingClass: 'collapsing',
            ...options
        };

        this.isOpen = this.element.classList.contains(this.options.openClass);
        this.isAnimating = false;
        this.triggers = [];

        this.init();
    }

    init() {
        // Find all triggers for this collapse
        const id = this.element.id;
        if (id) {
            this.triggers = document.querySelectorAll(
                `[data-collapse-target="#${id}"], [data-toggle="collapse"][href="#${id}"], [data-toggle="collapse"][data-target="#${id}"]`
            );
        }

        // Set initial state
        if (this.isOpen) {
            this.element.style.height = 'auto';
            this.element.setAttribute('aria-hidden', 'false');
        } else {
            this.element.style.height = '0';
            this.element.style.overflow = 'hidden';
            this.element.setAttribute('aria-hidden', 'true');
        }

        // Update trigger states
        this.updateTriggers();
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        if (this.isOpen || this.isAnimating) return;

        this.isAnimating = true;

        // Dispatch before open event
        const beforeEvent = new CustomEvent('collapse:beforeOpen', {
            detail: { collapse: this },
            cancelable: true
        });
        this.element.dispatchEvent(beforeEvent);

        if (beforeEvent.defaultPrevented) {
            this.isAnimating = false;
            return;
        }

        // Prepare for animation
        this.element.classList.remove(this.options.openClass);
        this.element.classList.add(this.options.collapsingClass);
        this.element.style.height = '0';
        this.element.style.overflow = 'hidden';
        this.element.style.display = 'block';

        // Get target height
        const targetHeight = this.element.scrollHeight;

        // Force reflow
        this.element.offsetHeight;

        // Apply transition
        this.element.style.transition = `height ${this.options.duration}ms ${this.options.easing}`;
        this.element.style.height = targetHeight + 'px';

        // Cleanup after animation
        setTimeout(() => {
            this.element.classList.remove(this.options.collapsingClass);
            this.element.classList.add(this.options.openClass);
            this.element.style.height = 'auto';
            this.element.style.overflow = '';
            this.element.style.transition = '';
            this.element.setAttribute('aria-hidden', 'false');

            this.isOpen = true;
            this.isAnimating = false;
            this.updateTriggers();

            // Dispatch open event
            this.element.dispatchEvent(new CustomEvent('collapse:open', {
                detail: { collapse: this }
            }));
        }, this.options.duration);
    }

    close() {
        if (!this.isOpen || this.isAnimating) return;

        this.isAnimating = true;

        // Dispatch before close event
        const beforeEvent = new CustomEvent('collapse:beforeClose', {
            detail: { collapse: this },
            cancelable: true
        });
        this.element.dispatchEvent(beforeEvent);

        if (beforeEvent.defaultPrevented) {
            this.isAnimating = false;
            return;
        }

        // Get current height
        const currentHeight = this.element.scrollHeight;

        // Set explicit height for animation
        this.element.style.height = currentHeight + 'px';
        this.element.style.overflow = 'hidden';

        // Force reflow
        this.element.offsetHeight;

        // Apply transition
        this.element.classList.remove(this.options.openClass);
        this.element.classList.add(this.options.collapsingClass);
        this.element.style.transition = `height ${this.options.duration}ms ${this.options.easing}`;
        this.element.style.height = '0';

        // Cleanup after animation
        setTimeout(() => {
            this.element.classList.remove(this.options.collapsingClass);
            this.element.style.display = '';
            this.element.style.overflow = 'hidden';
            this.element.style.transition = '';
            this.element.setAttribute('aria-hidden', 'true');

            this.isOpen = false;
            this.isAnimating = false;
            this.updateTriggers();

            // Dispatch close event
            this.element.dispatchEvent(new CustomEvent('collapse:close', {
                detail: { collapse: this }
            }));
        }, this.options.duration);
    }

    updateTriggers() {
        this.triggers.forEach(trigger => {
            trigger.setAttribute('aria-expanded', this.isOpen);

            if (this.isOpen) {
                trigger.classList.remove('collapsed');
            } else {
                trigger.classList.add('collapsed');
            }
        });
    }

    destroy() {
        this.element.style.height = '';
        this.element.style.overflow = '';
        this.element.style.transition = '';
        this.element.classList.remove(this.options.openClass, this.options.collapsingClass);
    }
}

// Auto-initialize triggers
function initCollapse() {
    // Click handlers for triggers
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-collapse-target], [data-toggle="collapse"]');

        if (trigger) {
            e.preventDefault();

            const targetSelector = trigger.getAttribute('data-collapse-target') ||
                trigger.getAttribute('data-target') ||
                trigger.getAttribute('href');

            if (targetSelector) {
                const target = document.querySelector(targetSelector);

                if (target) {
                    if (!target.collapseInstance) {
                        target.collapseInstance = new Collapse(target);
                    }
                    target.collapseInstance.toggle();
                }
            }
        }
    });

    // Initialize elements with show class
    document.querySelectorAll('.collapse.show').forEach(element => {
        if (!element.collapseInstance) {
            element.collapseInstance = new Collapse(element);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCollapse);
} else {
    initCollapse();
}

export default Collapse;