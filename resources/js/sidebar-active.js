/**
 * Nexus — sidebar active-state.
 * The template ships with "Dashboard" statically highlighted; this module
 * clears that and highlights whichever sidebar link matches the current URL
 * (longest-prefix match, so /tickets/5 highlights Tickets).
 */
const ACTIVE = ['bg-brand-50', 'dark:bg-brand-950/40', 'text-brand-600', 'dark:text-brand-400', 'font-semibold'];
const INACTIVE_TEXT = ['text-slate-700', 'dark:text-slate-300', 'text-slate-600', 'dark:text-dark-300'];

function applySidebarActive() {
    const sidebar = document.getElementById('sidebar') || document.querySelector('aside');
    if (!sidebar) return;

    const links = [...sidebar.querySelectorAll('a[href]')]
        .filter((a) => a.origin === location.origin && a.getAttribute('href') !== '#');

    // Reset every link to the inactive look
    links.forEach((a) => {
        a.classList.remove(...ACTIVE, 'dark:bg-brand-950/50');
        if (!INACTIVE_TEXT.some((c) => a.classList.contains(c))) {
            a.classList.add('text-slate-700', 'dark:text-slate-300');
        }
    });

    // Longest matching href wins
    const path = location.pathname.replace(/\/$/, '') || '/';
    let best = null;

    links.forEach((a) => {
        const href = a.pathname.replace(/\/$/, '') || '/';
        const exact = href === path;
        const prefix = href !== '/' && path.startsWith(href + '/');
        if (exact || prefix) {
            if (!best || href.length > (best.pathname.replace(/\/$/, '') || '/').length) best = a;
        }
    });

    if (best) {
        best.classList.remove(...INACTIVE_TEXT, 'hover:bg-slate-50', 'dark:hover:bg-dark-700');
        best.classList.add(...ACTIVE);
    }
}

document.addEventListener('DOMContentLoaded', applySidebarActive);
