import './bootstrap';

/**
 * FAQ Accordion – exclusive group + icon sync.
 *
 * When any .faq-accordion <details> is opened, every other .faq-accordion
 * <details> on the page is automatically closed. Icon opacity is also
 * synced directly via JS (element.style.opacity), which always wins over
 * hardcoded inline style HTML attributes regardless of CSS specificity.
 */

/** Sync the +/- icon opacity for a single <details> element. */
function faqSyncIcons(details) {
    var isOpen = details.hasAttribute('open');
    var plus  = details.querySelector('.faq-icon-plus');
    var minus = details.querySelector('.faq-icon-minus');
    if (plus)  plus.style.opacity  = isOpen ? '0' : '1';
    if (minus) minus.style.opacity = isOpen ? '1' : '0';
}

/** Initial sync for every FAQ on the page (handles pre-opened widgets). */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.faq-accordion details').forEach(faqSyncIcons);
});

/** Click handler: let the browser toggle, then close others + sync all icons. */
document.addEventListener('click', function (e) {

    // Walk up from the click target to find a <summary> element
    var el = e.target;
    while (el && el !== document.body) {
        if (el.tagName === 'SUMMARY') break;
        el = el.parentElement;
    }
    if (!el || el.tagName !== 'SUMMARY') return;

    // Confirm the <summary> lives inside a .faq-accordion wrapper
    var details = el.parentElement;
    if (!details || details.tagName !== 'DETAILS') return;

    var wrapper = details.parentElement;
    while (wrapper && wrapper !== document.body) {
        if (wrapper.classList && wrapper.classList.contains('faq-accordion')) break;
        wrapper = wrapper.parentElement;
    }
    if (!wrapper || !wrapper.classList.contains('faq-accordion')) return;

    // After the browser finishes its own open/close toggle:
    //  1. Close every other .faq-accordion <details>
    //  2. Sync icons for ALL FAQs on the page
    setTimeout(function () {
        document.querySelectorAll('.faq-accordion details').forEach(function (d) {
            if (d !== details) d.removeAttribute('open');
            faqSyncIcons(d);
        });
        faqSyncIcons(details); // sync the clicked one too
    }, 0);

});
