/**
 * Contact Form Component
 * AJAX submission for the contact form, with an optional reCAPTCHA v3 token
 *
 * Reads its config from window.pnvrContact, added by PNVR_Contact::render_form()
 */

class ContactForm {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            config: window.pnvrContact || null,
            networkError: 'Network error. Please check your connection and try again.',
            genericError: 'An error occurred. Please try again.',
            ...options
        };

        this.form = this.element.querySelector('form');
        this.button = this.form?.querySelector('[type="submit"]');
        this.label = this.button?.querySelector('.pnvr-contact-label');
        this.spinner = this.button?.querySelector('.pnvr-contact-spinner');
        this.error = this.element.querySelector('.pnvr-contact-error');
        this.success = this.element.querySelector('.pnvr-contact-success');
        this.successMsg = this.element.querySelector('.pnvr-contact-success-msg');

        this.init();
    }

    init() {
        if (!this.form || !this.options.config) return;

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submit();
        });
    }

    setLoading(loading) {
        if (!this.button) return;
        this.button.disabled = loading;
        this.label?.toggleAttribute('hidden', loading);
        this.spinner?.toggleAttribute('hidden', !loading);
    }

    showError(message) {
        if (!this.error) return;
        this.error.textContent = message;
        this.error.removeAttribute('hidden');
    }

    hideError() {
        this.error?.setAttribute('hidden', '');
    }

    async submit() {
        const config = this.options.config;

        this.hideError();
        this.setLoading(true);

        try {
            const data = new FormData(this.form);
            data.append('action', config.action);
            data.append('nonce', config.nonce);

            // Attach a reCAPTCHA v3 token when configured
            if (config.recaptchaKey && window.grecaptcha) {
                await new Promise(resolve => window.grecaptcha.ready(resolve));
                const token = await window.grecaptcha.execute(config.recaptchaKey, { action: 'contact' });
                data.append('recaptcha_token', token);
            }

            const response = await fetch(config.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' });
            const json = await response.json();

            if (json.success) {
                this.form.setAttribute('hidden', '');
                if (this.success && this.successMsg) {
                    this.successMsg.textContent = json.data.message;
                    this.success.removeAttribute('hidden');
                    this.success.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

                // Dispatch event
                this.element.dispatchEvent(new CustomEvent('contactform:sent', {
                    detail: { form: this }
                }));
            } else {
                this.showError(json.data?.message ?? this.options.genericError);
            }
        } catch {
            this.showError(this.options.networkError);
        } finally {
            this.setLoading(false);
        }
    }
}

// Auto-initialize
function initContactForms() {
    document.querySelectorAll('[data-contact-form]').forEach(element => {
        if (!element.contactFormInstance) {
            element.contactFormInstance = new ContactForm(element);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initContactForms);
} else {
    initContactForms();
}

export default ContactForm;
