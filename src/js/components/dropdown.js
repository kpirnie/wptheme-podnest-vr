/**
 * Dropdown Component
 * Handles dropdown menus with keyboard navigation
 */

class Dropdown {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            openClass: 'show',
            toggleSelector: '.dropdown-toggle',
            menuSelector: '.dropdown-menu',
            itemSelector: '.dropdown-item:not(.disabled)',
            closeOnClickOutside: true,
            closeOnSelect: true,
            placement: 'bottom-start',
            offset: 4,
            ...options
        };

        this.toggle = this.element.querySelector(this.options.toggleSelector);
        this.menu = this.element.querySelector(this.options.menuSelector);
        this.items = [];
        this.isOpen = false;
        this.currentIndex = -1;

        if (this.toggle && this.menu) {
            this.init();
        }
    }

    init() {
        // Set ARIA attributes
        this.toggle.setAttribute('aria-haspopup', 'true');
        this.toggle.setAttribute('aria-expanded', 'false');
        this.menu.setAttribute('role', 'menu');

        // Get menu items
        this.updateItems();

        // Toggle click
        this.toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleMenu();
        });

        // Keyboard navigation on toggle
        this.toggle.addEventListener('keydown', (e) => this.handleToggleKeydown(e));

        // Keyboard navigation on menu
        this.menu.addEventListener('keydown', (e) => this.handleMenuKeydown(e));

        // Close on click outside
        if (this.options.closeOnClickOutside) {
            document.addEventListener('click', (e) => {
                if (this.isOpen && !this.element.contains(e.target)) {
                    this.close();
                }
            });
        }

        // Close on item select
        if (this.options.closeOnSelect) {
            this.menu.addEventListener('click', (e) => {
                const item = e.target.closest(this.options.itemSelector);
                if (item && !item.classList.contains('disabled')) {
                    this.close();
                }
            });
        }
    }

    updateItems() {
        this.items = Array.from(this.menu.querySelectorAll(this.options.itemSelector));
        this.items.forEach((item, index) => {
            item.setAttribute('role', 'menuitem');
            item.setAttribute('tabindex', '-1');
        });
    }

    toggleMenu() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        if (this.isOpen) return;

        // Close other open dropdowns
        document.querySelectorAll('.dropdown.show').forEach(dropdown => {
            if (dropdown !== this.element && dropdown.dropdownInstance) {
                dropdown.dropdownInstance.close();
            }
        });

        this.isOpen = true;
        this.currentIndex = -1;
        this.element.classList.add(this.options.openClass);
        this.menu.classList.add(this.options.openClass);
        this.toggle.setAttribute('aria-expanded', 'true');

        // Position menu
        this.positionMenu();

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('dropdown:open', {
            detail: { dropdown: this }
        }));
    }

    close() {
        if (!this.isOpen) return;

        this.isOpen = false;
        this.currentIndex = -1;
        this.element.classList.remove(this.options.openClass);
        this.menu.classList.remove(this.options.openClass);
        this.toggle.setAttribute('aria-expanded', 'false');

        // Return focus to toggle
        this.toggle.focus();

        // Dispatch event
        this.element.dispatchEvent(new CustomEvent('dropdown:close', {
            detail: { dropdown: this }
        }));
    }

    positionMenu() {
        // Reset styles
        this.menu.style.top = '';
        this.menu.style.bottom = '';
        this.menu.style.left = '';
        this.menu.style.right = '';

        const toggleRect = this.toggle.getBoundingClientRect();
        const menuRect = this.menu.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const viewportWidth = window.innerWidth;

        // Check if menu would overflow bottom
        const spaceBelow = viewportHeight - toggleRect.bottom;
        const spaceAbove = toggleRect.top;

        if (spaceBelow < menuRect.height && spaceAbove > spaceBelow) {
            // Position above
            this.menu.style.bottom = '100%';
            this.menu.style.marginBottom = this.options.offset + 'px';
        } else {
            // Position below (default)
            this.menu.style.top = '100%';
            this.menu.style.marginTop = this.options.offset + 'px';
        }

        // Check horizontal overflow
        if (this.options.placement.includes('end')) {
            this.menu.style.right = '0';
        } else {
            this.menu.style.left = '0';
        }

        // Adjust if overflowing viewport
        const updatedRect = this.menu.getBoundingClientRect();
        if (updatedRect.right > viewportWidth) {
            this.menu.style.left = 'auto';
            this.menu.style.right = '0';
        }
        if (updatedRect.left < 0) {
            this.menu.style.left = '0';
            this.menu.style.right = 'auto';
        }
    }

    handleToggleKeydown(e) {
        switch (e.key) {
            case 'ArrowDown':
            case 'Down':
                e.preventDefault();
                if (!this.isOpen) {
                    this.open();
                }
                this.focusItem(0);
                break;

            case 'ArrowUp':
            case 'Up':
                e.preventDefault();
                if (!this.isOpen) {
                    this.open();
                }
                this.focusItem(this.items.length - 1);
                break;

            case 'Enter':
            case ' ':
                e.preventDefault();
                this.toggleMenu();
                break;

            case 'Escape':
                e.preventDefault();
                this.close();
                break;
        }
    }

    handleMenuKeydown(e) {
        switch (e.key) {
            case 'ArrowDown':
            case 'Down':
                e.preventDefault();
                this.focusNextItem();
                break;

            case 'ArrowUp':
            case 'Up':
                e.preventDefault();
                this.focusPrevItem();
                break;

            case 'Home':
                e.preventDefault();
                this.focusItem(0);
                break;

            case 'End':
                e.preventDefault();
                this.focusItem(this.items.length - 1);
                break;

            case 'Escape':
                e.preventDefault();
                this.close();
                break;

            case 'Tab':
                this.close();
                break;

            case 'Enter':
            case ' ':
                e.preventDefault();
                if (this.currentIndex >= 0 && this.items[this.currentIndex]) {
                    this.items[this.currentIndex].click();
                }
                break;

            default:
                // Type-ahead search
                if (e.key.length === 1 && !e.ctrlKey && !e.metaKey) {
                    this.typeAhead(e.key);
                }
                break;
        }
    }

    focusItem(index) {
        if (index < 0) index = 0;
        if (index >= this.items.length) index = this.items.length - 1;

        this.currentIndex = index;
        this.items[index].focus();
    }

    focusNextItem() {
        let nextIndex = this.currentIndex + 1;
        if (nextIndex >= this.items.length) {
            nextIndex = 0;
        }
        this.focusItem(nextIndex);
    }

    focusPrevItem() {
        let prevIndex = this.currentIndex - 1;
        if (prevIndex < 0) {
            prevIndex = this.items.length - 1;
        }
        this.focusItem(prevIndex);
    }

    typeAhead(char) {
        const searchChar = char.toLowerCase();
        const startIndex = this.currentIndex + 1;

        // Search from current position
        for (let i = startIndex; i < this.items.length; i++) {
            if (this.items[i].textContent.trim().toLowerCase().startsWith(searchChar)) {
                this.focusItem(i);
                return;
            }
        }

        // Wrap around and search from beginning
        for (let i = 0; i < startIndex; i++) {
            if (this.items[i].textContent.trim().toLowerCase().startsWith(searchChar)) {
                this.focusItem(i);
                return;
            }
        }
    }

    destroy() {
        this.close();
        this.toggle.removeEventListener('click', this.toggleMenu);
        this.toggle.removeEventListener('keydown', this.handleToggleKeydown);
        this.menu.removeEventListener('keydown', this.handleMenuKeydown);
    }
}

// Auto-initialize
function initDropdowns() {
    document.querySelectorAll('.dropdown').forEach(element => {
        if (!element.dropdownInstance) {
            element.dropdownInstance = new Dropdown(element);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDropdowns);
} else {
    initDropdowns();
}

export default Dropdown;