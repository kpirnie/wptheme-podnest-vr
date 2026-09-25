/**
 * Compare Slider Component
 * Before/after image comparison with a draggable divider
 *
 * Markup:
 * <div class="compare-slider" data-compare-slider data-start="50">
 *     <div class="compare-slider-before">...</div>
 *     <div class="compare-slider-after">...</div>
 *     <span class="compare-slider-handle" aria-hidden="true"></span>
 *     <input type="range" class="compare-slider-range" min="0" max="100" value="50" aria-label="...">
 * </div>
 */

class CompareSlider {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            start: 50,
            rangeSelector: '.compare-slider-range',
            activeClass: 'is-dragging',
            ...options
        };

        this.range = this.element.querySelector(this.options.rangeSelector);

        this.init();
    }

    init() {
        if (!this.range) return;

        // Starting position
        this.set(this.range.value !== '' ? this.range.value : this.options.start);

        // The range input sits over the whole slider, so dragging anywhere moves the divider
        this.range.addEventListener('input', () => {
            this.set(this.range.value);
        });

        // Dragging state, for styling
        this.range.addEventListener('pointerdown', () => {
            this.element.classList.add(this.options.activeClass);
        });

        ['pointerup', 'pointercancel', 'blur'].forEach(type => {
            this.range.addEventListener(type, () => {
                this.element.classList.remove(this.options.activeClass);
            });
        });
    }

    set(value) {
        const position = Math.min(100, Math.max(0, parseFloat(value) || 0));

        this.range.value = position;
        this.element.style.setProperty('--compare-position', position + '%');

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('compareslider:change', {
            detail: { position, slider: this }
        }));
    }
}

// Auto-initialize
function initCompareSliders() {
    document.querySelectorAll('[data-compare-slider]').forEach(element => {
        if (!element.compareSliderInstance) {
            element.compareSliderInstance = new CompareSlider(element, {
                start: parseFloat(element.getAttribute('data-start')) || 50
            });
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCompareSliders);
} else {
    initCompareSliders();
}

export default CompareSlider;
