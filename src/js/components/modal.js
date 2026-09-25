/**
 * Modal Component
 * Handles modal dialogs with accessibility support
 */

class Modal {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            backdrop: true,
            keyboard: true,
            focus: true,
            backdropClass: 'modal-backdrop',
            openClass: 'show',
            bodyOpenClass: 'modal-open',
            duration: 200,
            ...options
        };

        this.isOpen = false;
        this.backdrop = null;
        this.previousActiveElement = null;
        this.focusableElements = null;

        this.init();
    }

    init() {
        // Set ARIA attributes
        this.element.setAttribute('role', 'dialog');
        this.element.setAttribute('aria-modal', 'true');
        this.element.setAttribute('aria-hidden', 'true');
        this.element.setAttribute('tabindex', '-1');

        // Find close buttons
        this.closeButtons = this.element.querySelectorAll('[data-modal-close], .modal-close');
        this.closeButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.close();
            });
        });

        // Backdrop click
        this.element.addEventListener('click', (e) => {
            if (this.options.backdrop && e.target === this.element) {
                this.close();
            }
        });

        // Keyboard events
        this.element.addEventListener('keydown', (e) => this.handleKeydown(e));
    }

    open() {
        if (this.isOpen) return;

        this.previousActiveElement = document.activeElement;
        this.isOpen = true;

        // Create backdrop
        if (this.options.backdrop) {
            this.createBackdrop();
        }

        // Show modal
        this.element.style.display = 'block';
        this.element.setAttribute('aria-hidden', 'false');
        document.body.classList.add(this.options.bodyOpenClass);

        // Force reflow
        this.element.offsetHeight;

        // Add show class
        this.element.classList.add(this.options.openClass);
        if (this.backdrop) {
            this.backdrop.classList.add(this.options.openClass);
        }

        // Focus management
        if (this.options.focus) {
            setTimeout(() => {
                this.setFocus();
            }, this.options.duration);
        }

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('modal:open', {
            detail: { modal: this }
        }));
    }

    close() {
        if (!this.isOpen) return;

        this.isOpen = false;

        // Remove show class
        this.element.classList.remove(this.options.openClass);
        if (this.backdrop) {
            this.backdrop.classList.remove(this.options.openClass);
        }

        // Hide after animation
        setTimeout(() => {
            this.element.style.display = 'none';
            this.element.setAttribute('aria-hidden', 'true');
            document.body.classList.remove(this.options.bodyOpenClass);

            // Remove backdrop
            this.removeBackdrop();

            // Restore focus
            if (this.previousActiveElement) {
                this.previousActiveElement.focus();
            }
        }, this.options.duration);

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('modal:close', {
            detail: { modal: this }
        }));
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    createBackdrop() {
        this.backdrop = document.createElement('div');
        this.backdrop.className = this.options.backdropClass;
        document.body.appendChild(this.backdrop);
    }

    removeBackdrop() {
        if (this.backdrop) {
            this.backdrop.remove();
            this.backdrop = null;
        }
    }

    setFocus() {
        const focusableSelectors = [
            'button:not([disabled])',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            'a[href]',
            '[tabindex]:not([tabindex="-1"])'
        ].join(',');

        this.focusableElements = this.element.querySelectorAll(focusableSelectors);

        if (this.focusableElements.length > 0) {
            this.focusableElements[0].focus();
        } else {
            this.element.focus();
        }
    }

    handleKeydown(e) {
        if (!this.isOpen) return;

        // Close on Escape
        if (this.options.keyboard && e.key === 'Escape') {
            e.preventDefault();
            this.close();
            return;
        }

        // Trap focus
        if (e.key === 'Tab' && this.focusableElements && this.focusableElements.length > 0) {
            const firstFocusable = this.focusableElements[0];
            const lastFocusable = this.focusableElements[this.focusableElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
        }
    }

    destroy() {
        this.close();
        this.closeButtons.forEach(btn => {
            btn.removeEventListener('click', this.close);
        });
    }
}

// Auto-initialize triggers
function initModals() {
    // Modal triggers
    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSelector = trigger.getAttribute('data-modal-target');
            const modal = document.querySelector(targetSelector);

            if (modal) {
                if (!modal.modalInstance) {
                    modal.modalInstance = new Modal(modal, {
                        backdrop: trigger.getAttribute('data-backdrop') !== 'false',
                        keyboard: trigger.getAttribute('data-keyboard') !== 'false'
                    });
                }
                modal.modalInstance.open();
            }
        });
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initModals);
} else {
    initModals();
}

export default Modal;