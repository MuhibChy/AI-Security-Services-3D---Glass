/**
 * service-cards.js — Compact click-to-expand for home page service cards.
 *
 * Each service category card starts collapsed (≈50% of full height). Clicking
 * "Show all services" expands it to reveal the complete description and every
 * service tag. Only one card at a time expands per click; the control label
 * flips to "Show less".
 *
 * Fully keyboard-accessible: Enter and Space toggle the active card.
 * Respects prefers-reduced-motion.
 */

(function () {
    'use strict';

    const CHEVRON = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';

    function init() {
        const cards = document.querySelectorAll('.service-card');
        if (!cards.length) return;

        // Inject toggle icons once, then wire clicks.
        cards.forEach(function (card) {
            var btn = card.querySelector('.service-card-toggle');
            if (!btn) return;

            // Append chevron so the CSS transition has an element to rotate.
            var icon = btn.querySelector('svg');
            if (!icon) {
                btn.insertAdjacentHTML('beforeend', CHEVRON);
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                toggleCard(card);
            });

            btn.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleCard(card);
                }
            });
        });
    }

    function toggleCard(card) {
        var isExpanded = card.classList.contains('expanded');
        var btn = card.querySelector('.service-card-toggle');
        var body = card.querySelector('.service-card-body');
        var expandedAttr = btn ? btn.getAttribute('aria-expanded') : null;

        if (isExpanded) {
            card.classList.remove('expanded');
            card.classList.add('collapsed');
            if (body) {
                body.setAttribute('aria-hidden', 'true');
                body.style.maxHeight = '0px';
            }
            if (btn) {
                btn.setAttribute('aria-expanded', 'false');
                btn.textContent = btn.textContent.replace(/Show less/i, 'Show all services');
                btn.insertAdjacentHTML('beforeend', CHEVRON);
            }
        } else {
            card.classList.remove('collapsed');
            card.classList.add('expanded');
            if (body) {
                body.setAttribute('aria-hidden', 'false');
                body.style.maxHeight = (body.scrollHeight + 300) + 'px';
            }
            if (btn) {
                btn.setAttribute('aria-expanded', 'true');
                btn.textContent = btn.textContent.replace(/Show all services/i, 'Show less');
                btn.insertAdjacentHTML('beforeend', CHEVRON);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
