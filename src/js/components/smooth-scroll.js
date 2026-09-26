/**
 * Smooth Scroll for In-Page Anchor Links
 *
 * Ported from the PodNest theme's smooth-scroll module. Intercepts clicks on
 * links pointing at a section on this page, relative or absolute, and scrolls
 * smoothly to it, accounting for the sticky header height.
 */

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
            const top = target.getBoundingClientRect().top + window.pageYOffset - offset;

            window.scrollTo({ top, behavior: 'smooth' });
        });
    });
}