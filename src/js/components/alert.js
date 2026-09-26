/**
 * Alert Component
 * Handles dismissible alerts with animations
 */

class Alert {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            dismissClass: 'alert-dismissible',
            closeSelector: '.alert-close, [data-alert-close]',
            fadeClass: 'alert-fade',
            hidingClass: 'hiding',
            duration: 150,
            removeOnClose: true,
            ...options
        };

        this.closeButton = this.element.querySelector(this.options.closeSelector);
        this.isVisible = true;

        this.init();
    }

    init() {
        // Set up close button
        if (this.closeButton) {
            this.closeButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.close();
            });

            // Accessibility
            this.closeButton.setAttribute('aria-label', 'Close alert');
        }

        // Set ARIA attributes
        this.element.setAttribute('role', 'alert');
    }

    close() {
        if (!this.isVisible) return;

        // Dispatch before close event
        const beforeEvent = new CustomEvent('alert:beforeClose', {
            detail: { alert: this },
            cancelable: true
        });
        this.element.dispatchEvent(beforeEvent);

        if (beforeEvent.defaultPrevented) {
            return;
        }

        this.isVisible = false;

        // Animate out
        if (this.element.classList.contains(this.options.fadeClass)) {
            this.element.classList.add(this.options.hidingClass);

            setTimeout(() => {
                this.remove();
            }, this.options.duration);
        } else {
            this.remove();
        }
    }

    remove() {
        // Dispatch close event
        this.element.dispatchEvent(new CustomEvent('alert:close', {
            detail: { alert: this }
        }));

        if (this.options.removeOnClose) {
            this.element.remove();
        } else {
            this.element.style.display = 'none';
        }

        // Dispatch closed event
        document.dispatchEvent(new CustomEvent('alert:closed', {
            detail: { alert: this }
        }));
    }

    show() {
        if (this.isVisible) return;

        this.isVisible = true;
        this.element.style.display = '';
        this.element.classList.remove(this.options.hidingClass);

        // Dispatch show event
        this.element.dispatchEvent(new CustomEvent('alert:show', {
            detail: { alert: this }
        }));
    }

    destroy() {
        if (this.closeButton) {
            this.closeButton.removeEventListener('click', this.close);
        }
    }
}

/**
 * Alert Factory
 * Creates alerts dynamically
 */
class AlertFactory {
    static defaults = {
        type: 'info',
        message: '',
        title: '',
        dismissible: true,
        icon: true,
        duration: 0,
        container: null,
        position: 'prepend',
        fadeClass: 'alert-fade'
    };

    static icons = {
        success: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        danger: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
        warning: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        info: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
    };

    static create(options = {}) {
        const config = { ...AlertFactory.defaults, ...options };

        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${config.type}`;

        if (config.dismissible) {
            alert.classList.add('alert-dismissible');
        }

        if (config.fadeClass) {
            alert.classList.add(config.fadeClass);
        }

        // Build content
        let content = '';

        // Icon
        if (config.icon && AlertFactory.icons[config.type]) {
            content += `<span class="alert-icon">${AlertFactory.icons[config.type]}</span>`;
        }

        // Content wrapper
        content += '<div class="alert-content">';

        // Title
        if (config.title) {
            content += `<div class="alert-title">${config.title}</div>`;
        }

        // Message
        content += `<div class="alert-message">${config.message}</div>`;

        content += '</div>';

        // Close button
        if (config.dismissible) {
            content += '<button type="button" class="alert-close" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>';
        }

        alert.innerHTML = content;

        // Initialize Alert instance
        const alertInstance = new Alert(alert, {
            removeOnClose: true
        });
        alert.alertInstance = alertInstance;

        // Add to container
        if (config.container) {
            const container = typeof config.container === 'string'
                ? document.querySelector(config.container)
                : config.container;

            if (container) {
                if (config.position === 'prepend') {
                    container.prepend(alert);
                } else {
                    container.append(alert);
                }
            }
        }

        // Auto-dismiss
        if (config.duration > 0) {
            setTimeout(() => {
                alertInstance.close();
            }, config.duration);
        }

        return alertInstance;
    }

    static success(message, options = {}) {
        return AlertFactory.create({ ...options, type: 'success', message });
    }

    static danger(message, options = {}) {
        return AlertFactory.create({ ...options, type: 'danger', message });
    }

    static error(message, options = {}) {
        return AlertFactory.danger(message, options);
    }

    static warning(message, options = {}) {
        return AlertFactory.create({ ...options, type: 'warning', message });
    }

    static info(message, options = {}) {
        return AlertFactory.create({ ...options, type: 'info', message });
    }
}

// Auto-initialize
function initAlerts() {
    document.querySelectorAll('.alert-dismissible').forEach(element => {
        if (!element.alertInstance) {
            element.alertInstance = new Alert(element);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAlerts);
} else {
    initAlerts();
}

export { Alert, AlertFactory };

