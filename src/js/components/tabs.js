/**
 * Tabs Component
 * Handles tab switching and content display
 */

class Tabs {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            activeClass: 'active',
            fadeEffect: true,
            fadeDuration: 150,
            history: false,
            ...options
        };

        this.tabList = this.element.querySelector('.tab-list');
        this.tabLinks = this.element.querySelectorAll('.tab-link');
        this.tabContent = this.element.querySelector('.tab-content');
        this.tabPanes = this.element.querySelectorAll('.tab-pane');

        this.init();
    }

    init() {
        // Set up click handlers
        this.tabLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.activate(link);
            });

            // Keyboard navigation
            link.addEventListener('keydown', (e) => {
                this.handleKeydown(e, link);
            });
        });

        // Handle hash on load
        if (this.options.history && window.location.hash) {
            const hashTarget = this.element.querySelector(
                `.tab-link[href="${window.location.hash}"], .tab-link[data-target="${window.location.hash}"]`
            );
            if (hashTarget) {
                this.activate(hashTarget, false);
            }
        }

        // Listen for hash changes
        if (this.options.history) {
            window.addEventListener('hashchange', () => {
                if (window.location.hash) {
                    const hashTarget = this.element.querySelector(
                        `.tab-link[href="${window.location.hash}"], .tab-link[data-target="${window.location.hash}"]`
                    );
                    if (hashTarget) {
                        this.activate(hashTarget, false);
                    }
                }
            });
        }

        // Set ARIA attributes
        this.setupAccessibility();
    }

    setupAccessibility() {
        if (this.tabList) {
            this.tabList.setAttribute('role', 'tablist');
        }

        this.tabLinks.forEach((link, index) => {
            const target = this.getTargetPane(link);
            const id = link.id || `tab-${index}`;
            const panelId = target ? target.id || `tab-panel-${index}` : `tab-panel-${index}`;

            link.setAttribute('role', 'tab');
            link.setAttribute('id', id);
            link.setAttribute('aria-controls', panelId);
            link.setAttribute('aria-selected', link.classList.contains(this.options.activeClass));
            link.setAttribute('tabindex', link.classList.contains(this.options.activeClass) ? '0' : '-1');

            if (target) {
                target.setAttribute('role', 'tabpanel');
                target.setAttribute('id', panelId);
                target.setAttribute('aria-labelledby', id);
                target.setAttribute('tabindex', '0');
            }
        });
    }

    getTargetPane(link) {
        const targetSelector = link.getAttribute('href') || link.getAttribute('data-target');
        if (targetSelector && targetSelector !== '#') {
            return this.element.querySelector(targetSelector);
        }
        return null;
    }

    activate(link, updateHistory = true) {
        if (link.classList.contains('disabled') || link.hasAttribute('disabled')) {
            return;
        }

        const targetPane = this.getTargetPane(link);
        if (!targetPane) return;

        // Deactivate current tab
        const currentActive = this.element.querySelector(`.tab-link.${this.options.activeClass}`);
        const currentPane = this.element.querySelector(`.tab-pane.${this.options.activeClass}`);

        if (currentActive) {
            currentActive.classList.remove(this.options.activeClass);
            currentActive.setAttribute('aria-selected', 'false');
            currentActive.setAttribute('tabindex', '-1');
        }

        if (currentPane) {
            if (this.options.fadeEffect) {
                currentPane.style.opacity = '0';
                setTimeout(() => {
                    currentPane.classList.remove(this.options.activeClass);
                    currentPane.style.opacity = '';
                }, this.options.fadeDuration);
            } else {
                currentPane.classList.remove(this.options.activeClass);
            }
        }

        // Activate new tab
        link.classList.add(this.options.activeClass);
        link.setAttribute('aria-selected', 'true');
        link.setAttribute('tabindex', '0');

        if (this.options.fadeEffect) {
            setTimeout(() => {
                targetPane.classList.add(this.options.activeClass);
                targetPane.style.opacity = '0';
                targetPane.offsetHeight; // Force reflow
                targetPane.style.transition = `opacity ${this.options.fadeDuration}ms ease`;
                targetPane.style.opacity = '1';

                setTimeout(() => {
                    targetPane.style.opacity = '';
                    targetPane.style.transition = '';
                }, this.options.fadeDuration);
            }, this.options.fadeDuration);
        } else {
            targetPane.classList.add(this.options.activeClass);
        }

        // Update URL hash
        if (this.options.history && updateHistory) {
            const targetId = link.getAttribute('href') || link.getAttribute('data-target');
            if (targetId && targetId !== '#') {
                history.pushState(null, null, targetId);
            }
        }

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('tabs:change', {
            detail: {
                tab: link,
                pane: targetPane
            }
        }));
    }

    handleKeydown(e, currentLink) {
        const links = Array.from(this.tabLinks).filter(link =>
            !link.classList.contains('disabled') && !link.hasAttribute('disabled')
        );
        const currentIndex = links.indexOf(currentLink);
        let newIndex;

        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = links.length - 1;
                links[newIndex].focus();
                this.activate(links[newIndex]);
                break;

            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                newIndex = currentIndex + 1;
                if (newIndex >= links.length) newIndex = 0;
                links[newIndex].focus();
                this.activate(links[newIndex]);
                break;

            case 'Home':
                e.preventDefault();
                links[0].focus();
                this.activate(links[0]);
                break;

            case 'End':
                e.preventDefault();
                links[links.length - 1].focus();
                this.activate(links[links.length - 1]);
                break;
        }
    }

    goTo(index) {
        const links = Array.from(this.tabLinks);
        if (index >= 0 && index < links.length) {
            this.activate(links[index]);
        }
    }

    next() {
        const links = Array.from(this.tabLinks);
        const currentIndex = links.findIndex(link =>
            link.classList.contains(this.options.activeClass)
        );
        const nextIndex = (currentIndex + 1) % links.length;
        this.activate(links[nextIndex]);
    }

    prev() {
        const links = Array.from(this.tabLinks);
        const currentIndex = links.findIndex(link =>
            link.classList.contains(this.options.activeClass)
        );
        const prevIndex = currentIndex - 1 < 0 ? links.length - 1 : currentIndex - 1;
        this.activate(links[prevIndex]);
    }

    destroy() {
        this.tabLinks.forEach(link => {
            link.removeEventListener('click', this.activate);
            link.removeEventListener('keydown', this.handleKeydown);
        });
    }
}

// Auto-initialize
function initTabs() {
    document.querySelectorAll('.tabs').forEach(element => {
        if (!element.tabsInstance) {
            element.tabsInstance = new Tabs(element, {
                fadeEffect: element.hasAttribute('data-fade'),
                history: element.hasAttribute('data-history')
            });
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTabs);
} else {
    initTabs();
}

export default Tabs;