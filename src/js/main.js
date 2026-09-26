/**
 * PodNest Visual Regressor - Main JavaScript Entry Point
 * 
 * @package PodNest Visual Regressor
 * @author Kevin Pirnie <me@kpirnie.com>
 * @version 1.0.1
 */

// Import components
import Accordion from './components/accordion.js';
import { Alert, AlertFactory } from './components/alert.js';
import Collapse from './components/collapse.js';
import CompareSlider from './components/compare-slider.js';
import ContactForm from './components/contact-form.js';
import Dropdown from './components/dropdown.js';
import Modal from './components/modal.js';
import { Navigation, ScrollSpy } from './components/navigation.js';
import ScrollToTop from './components/scroll-to-top.js';
import SmoothScroll, { BackToTop, InfiniteScroll, ScrollProgress, ScrollReveal } from './components/scroll.js';
import { initSmoothScroll as smoothScroll } from './components/smooth-scroll.js';
import Tabs from './components/tabs.js';
import { Popover, Tooltip } from './components/tooltip.js';

/**
 * PNVR Global Object
 * Exposes components to global scope for external use
 */
const PNVR = {
    // Components
    Accordion,
    Tabs,
    Modal,
    Dropdown,
    Collapse,
    CompareSlider,
    ContactForm,
    Alert,
    AlertFactory,
    Tooltip,
    Popover,
    Navigation,
    ScrollSpy,
    SmoothScroll,
    BackToTop,
    ScrollReveal,
    ScrollProgress,
    InfiniteScroll,
    ScrollToTop,

    // Version
    version: '1.0.1',

    /**
     * Initialize all components
     */
    init() {
        this.initAccordions();
        this.initTabs();
        this.initModals();
        this.initDropdowns();
        this.initCollapse();
        this.initCompareSliders();
        this.initContactForms();
        this.initAlerts();
        this.initTooltips();
        this.initPopovers();
        this.initNavigation();
        this.initScroll();
        this.initScrollToTop();
        this.initSmoothScroll();

        // Dispatch ready event
        document.dispatchEvent(new CustomEvent('pnvr:ready', {
            detail: { PNVR: this }
        }));
    },

    /**
     * Initialize the before/after compare sliders
     */
    initCompareSliders() {
        document.querySelectorAll('[data-compare-slider]').forEach(element => {
            if (!element.compareSliderInstance) {
                element.compareSliderInstance = new CompareSlider(element, {
                    start: parseFloat(element.getAttribute('data-start')) || 50
                });
            }
        });
    },

    /**
     * Initialize the contact forms
     */
    initContactForms() {
        document.querySelectorAll('[data-contact-form]').forEach(element => {
            if (!element.contactFormInstance) {
                element.contactFormInstance = new ContactForm(element);
            }
        });
    },

    /**
     * Initialize the scroll-to-top
     */
    initScrollToTop() {
        const button = document.querySelector('.back-to-top');
        if (button && !button.scrollToTopInstance) {
            button.scrollToTopInstance = new ScrollToTop({
                showProgress: button.classList.contains('back-to-top-progress')
            });
        }
    },

    /**
     * Initialize smooth scrolling for in-page anchor links
     */
    initSmoothScroll() {
        smoothScroll();
    },

    /**
     * Initialize accordions
     */
    initAccordions() {
        document.querySelectorAll('.accordion').forEach(element => {
            if (!element.accordionInstance) {
                element.accordionInstance = new Accordion(element, {
                    allowMultiple: element.hasAttribute('data-allow-multiple')
                });
            }
        });
    },

    /**
     * Initialize tabs
     */
    initTabs() {
        document.querySelectorAll('.tabs').forEach(element => {
            if (!element.tabsInstance) {
                element.tabsInstance = new Tabs(element, {
                    fadeEffect: element.hasAttribute('data-fade'),
                    history: element.hasAttribute('data-history')
                });
            }
        });
    },

    /**
     * Initialize modals
     */
    initModals() {
        document.querySelectorAll('[data-modal-target]').forEach(trigger => {
            if (trigger.hasAttribute('data-modal-initialized')) return;

            trigger.setAttribute('data-modal-initialized', 'true');
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
    },

    /**
     * Initialize dropdowns
     */
    initDropdowns() {
        document.querySelectorAll('.dropdown').forEach(element => {
            if (!element.dropdownInstance) {
                element.dropdownInstance = new Dropdown(element);
            }
        });
    },

    /**
     * Initialize collapse
     */
    initCollapse() {
        document.querySelectorAll('.collapse.show').forEach(element => {
            if (!element.collapseInstance) {
                element.collapseInstance = new Collapse(element);
            }
        });
    },

    /**
     * Initialize alerts
     */
    initAlerts() {
        document.querySelectorAll('.alert-dismissible').forEach(element => {
            if (!element.alertInstance) {
                element.alertInstance = new Alert(element);
            }
        });
    },

    /**
     * Initialize tooltips
     */
    initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            if (!element.tooltipInstance) {
                element.tooltipInstance = new Tooltip(element);
            }
        });
    },

    /**
     * Initialize popovers
     */
    initPopovers() {
        document.querySelectorAll('[data-popover], [data-toggle="popover"]').forEach(element => {
            if (!element.popoverInstance) {
                element.popoverInstance = new Popover(element);
            }
        });
    },

    /**
     * Initialize navigation
     */
    initNavigation() {
        document.querySelectorAll('.navbar').forEach(element => {
            if (!element.navigationInstance) {
                element.navigationInstance = new Navigation(element, {
                    hideOnScroll: element.hasAttribute('data-hide-on-scroll'),
                    stickyOffset: parseInt(element.getAttribute('data-sticky-offset')) || 0
                });
            }
        });

        document.querySelectorAll('[data-scrollspy]').forEach(element => {
            if (!element.scrollSpyInstance) {
                element.scrollSpyInstance = new ScrollSpy(element, {
                    target: element.getAttribute('data-scrollspy-target'),
                    offset: parseInt(element.getAttribute('data-scrollspy-offset')) || 100
                });
            }
        });
    },

    /**
     * Initialize scroll components
     */
    initScroll() {

        // Back to top is handled by ScrollToTop, see initScrollToTop()

        // Scroll reveal, the attribute from templates or the class from block content
        if (document.querySelector('[data-scroll-reveal], .pnvr-reveal')) {
            new ScrollReveal({ selector: '[data-scroll-reveal], .pnvr-reveal' });
        }

        // Scroll progress
        const scrollProgress = document.querySelector('[data-scroll-progress]');
        if (scrollProgress && !scrollProgress.scrollProgressInstance) {
            scrollProgress.scrollProgressInstance = new ScrollProgress();
        }
    },

    /**
     * Create alert programmatically
     */
    alert(message, options = {}) {
        return AlertFactory.create({
            message,
            container: options.container || document.body,
            ...options
        });
    },

    /**
     * Success alert shorthand
     */
    success(message, options = {}) {
        return AlertFactory.success(message, {
            container: document.body,
            duration: 5000,
            ...options
        });
    },

    /**
     * Error alert shorthand
     */
    error(message, options = {}) {
        return AlertFactory.error(message, {
            container: document.body,
            duration: 0,
            ...options
        });
    },

    /**
     * Warning alert shorthand
     */
    warning(message, options = {}) {
        return AlertFactory.warning(message, {
            container: document.body,
            duration: 5000,
            ...options
        });
    },

    /**
     * Info alert shorthand
     */
    info(message, options = {}) {
        return AlertFactory.info(message, {
            container: document.body,
            duration: 5000,
            ...options
        });
    },

    /**
     * Open modal by selector
     */
    openModal(selector) {
        const modal = document.querySelector(selector);
        if (modal) {
            if (!modal.modalInstance) {
                modal.modalInstance = new Modal(modal);
            }
            modal.modalInstance.open();
            return modal.modalInstance;
        }
        return null;
    },

    /**
     * Close modal by selector
     */
    closeModal(selector) {
        const modal = document.querySelector(selector);
        if (modal && modal.modalInstance) {
            modal.modalInstance.close();
        }
    },

    /**
     * Scroll to element
     */
    scrollTo(target, options = {}) {
        const smoothScroll = new SmoothScroll();
        smoothScroll.scrollTo(target, options);
    },

    /**
     * Scroll to top
     */
    scrollToTop(options = {}) {
        const smoothScroll = new SmoothScroll();
        smoothScroll.scrollToTop(options);
    },

    /**
     * Refresh/reinitialize components
     * Useful after dynamic content is added
     */
    refresh() {
        this.init();
    },

    /**
     * Destroy all component instances
     */
    destroy() {
        document.querySelectorAll('[class*="Instance"]').forEach(element => {
            Object.keys(element).forEach(key => {
                if (key.endsWith('Instance') && element[key] && typeof element[key].destroy === 'function') {
                    element[key].destroy();
                    element[key] = null;
                }
            });
        });
    }
};

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => PNVR.init());
} else {
    PNVR.init();
}

// Expose to global scope
window.PNVR = PNVR;

export default PNVR;
