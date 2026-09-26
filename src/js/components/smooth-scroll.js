/**
 * Smooth Scroll for In-Page Anchor Links
 *
 * Intercepts clicks on links pointing at a section on this page, relative or absolute,
 * and animates the scroll frame by frame, like the scroll-to-top button, so it does not
 * depend on the browser's own smooth scrolling. Accounts for the sticky header height.
 */

/** How long the scroll takes, in milliseconds. */
const DURATION = 800;

/**
 * Ease in and out, cubic.
 *
 * @param {number} t Progress, 0 to 1
 * @returns {number}
 */
const easeInOutCubic = t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;

/**
 * Animates the window to a vertical position.
 *
 * @param {number} top The position to scroll to
 * @returns {void}
 */
function animateTo(top) {
    const start = window.pageYOffset;
    const distance = top - start;
    const startTime = performance.now();

    const step = now => {
        const progress = Math.min((now - startTime) / DURATION, 1);

        window.scrollTo({ top: start + distance * easeInOutCubic(progress), left: 0, behavior: 'instant' });

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };

    requestAnimationFrame(step);
}

/**
 * Initialises smooth scrolling for all in-page anchor links.
 *
 * @returns {void}
 */
export function initSmoothScroll() {
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', event => {
            if (!anchor.hash || anchor.origin !== window.location.origin || anchor.pathname !== window.location.pathname) {
                return;
            }

            const target = document.getElementById(decodeURIComponent(anchor.hash.slice(1)));

            if (!target) {
                return;
            }

            event.preventDefault();

            const header = document.getElementById('masthead');
            const offset = (header ? header.offsetHeight : 80) + 16;

            animateTo(target.getBoundingClientRect().top + window.pageYOffset - offset);

            history.pushState(null, '', anchor.hash);
        });
    });
}