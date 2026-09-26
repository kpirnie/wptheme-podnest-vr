/**
 * Accordion Component
 * Handles expand/collapse functionality for accordion items
 */

class Accordion {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            allowMultiple: false,
            duration: 300,
            ...options
        };

        this.items = this.element.querySelectorAll('.accordion-item');
        this.init();
    }

    init() {
        this.items.forEach(item => {
            const button = item.querySelector('.accordion-button');
            const collapse = item.querySelector('.accordion-collapse');

            if (button && collapse) {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggle(item);
                });

                // Set initial ARIA attributes
                const isExpanded = !button.classList.contains('collapsed');
                button.setAttribute('aria-expanded', isExpanded);
                collapse.setAttribute('aria-hidden', !isExpanded);

                if (isExpanded) {
                    collapse.classList.add('show');
                    collapse.style.height = 'auto';
                }
            }
        });
    }

    toggle(item) {
        const button = item.querySelector('.accordion-button');
        const collapse = item.querySelector('.accordion-collapse');
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            this.close(item);
        } else {
            if (!this.options.allowMultiple) {
                this.closeAll();
            }
            this.open(item);
        }
    }

    open(item) {
        const button = item.querySelector('.accordion-button');
        const collapse = item.querySelector('.accordion-collapse');

        button.classList.remove('collapsed');
        button.setAttribute('aria-expanded', 'true');
        collapse.setAttribute('aria-hidden', 'false');

        // Animate open
        collapse.style.display = 'block';
        const height = collapse.scrollHeight;
        collapse.style.height = '0';
        collapse.offsetHeight; // Force reflow
        collapse.style.transition = `height ${this.options.duration}ms ease`;
        collapse.style.height = height + 'px';

        setTimeout(() => {
            collapse.style.height = 'auto';
            collapse.style.transition = '';
            collapse.classList.add('show');
        }, this.options.duration);

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('accordion:open', {
            detail: { item }
        }));
    }

    close(item) {
        const button = item.querySelector('.accordion-button');
        const collapse = item.querySelector('.accordion-collapse');

        button.classList.add('collapsed');
        button.setAttribute('aria-expanded', 'false');
        collapse.setAttribute('aria-hidden', 'true');

        // Animate close
        const height = collapse.scrollHeight;
        collapse.style.height = height + 'px';
        collapse.offsetHeight; // Force reflow
        collapse.style.transition = `height ${this.options.duration}ms ease`;
        collapse.style.height = '0';

        setTimeout(() => {
            collapse.style.display = 'none';
            collapse.style.height = '';
            collapse.style.transition = '';
            collapse.classList.remove('show');
        }, this.options.duration);

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('accordion:close', {
            detail: { item }
        }));
    }

    closeAll() {
        this.items.forEach(item => {
            const button = item.querySelector('.accordion-button');
            if (button && button.getAttribute('aria-expanded') === 'true') {
                this.close(item);
            }
        });
    }

    openAll() {
        this.items.forEach(item => {
            const button = item.querySelector('.accordion-button');
            if (button && button.getAttribute('aria-expanded') === 'false') {
                this.open(item);
            }
        });
    }

    destroy() {
        this.items.forEach(item => {
            const button = item.querySelector('.accordion-button');
            if (button) {
                button.removeEventListener('click', this.toggle);
            }
        });
    }
}

// Auto-initialize
function initAccordions() {
    document.querySelectorAll('.accordion').forEach(element => {
        if (!element.accordionInstance) {
            element.accordionInstance = new Accordion(element, {
                allowMultiple: element.hasAttribute('data-allow-multiple')
            });
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAccordions);
} else {
    initAccordions();
}

export default Accordion;