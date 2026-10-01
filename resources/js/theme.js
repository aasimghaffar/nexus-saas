/**
 * Nexus SaaS Admin - Theme Controller (Dark / Light Mode)
 * Handles instant theme initialization, persistence via localStorage,
 * and dynamic theme changes without page flickering.
 */

(function () {
  const THEME_KEY = 'nexus_theme';

  function getPreferredTheme() {
    const savedTheme = localStorage.getItem(THEME_KEY);
    if (savedTheme) {
      return savedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    localStorage.setItem(THEME_KEY, theme);

    // Update UI Icons on all theme toggles
    const moonIcons = document.querySelectorAll('.theme-moon-icon');
    const sunIcons = document.querySelectorAll('.theme-sun-icon');
    
    if (theme === 'dark') {
      moonIcons.forEach(el => el.classList.add('hidden'));
      sunIcons.forEach(el => el.classList.remove('hidden'));
    } else {
      moonIcons.forEach(el => el.classList.remove('hidden'));
      sunIcons.forEach(el => el.classList.add('hidden'));
    }

    // Dispatch global custom event for charts or components to react
    window.dispatchEvent(new CustomEvent('nexusThemeChanged', { detail: { theme } }));
  }

  // Instant apply on load
  const initialTheme = getPreferredTheme();
  applyTheme(initialTheme);

  // Expose global methods
  window.NexusTheme = {
    getTheme: () => (document.documentElement.classList.contains('dark') ? 'dark' : 'light'),
    setTheme: (theme) => applyTheme(theme),
    toggle: () => {
      const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      const next = current === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      return next;
    }
  };

  // Attach click listener on DOMContentLoaded
  document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('[data-toggle="theme"]');
    toggleButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        window.NexusTheme.toggle();
      });
    });
    // Ensure icons match current theme state
    applyTheme(window.NexusTheme.getTheme());
  });
})();
