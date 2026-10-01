/**
 * Nexus SaaS Admin - Main Application Controller
 * Handles mobile drawer, desktop sidebar collapse, dropdowns, modals,
 * global search modal (Cmd+K), toast notifications, tabs, and copy actions.
 */

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initDropdowns();
  initModals();
  initGlobalSearch();
  initTabs();
  initTooltipsAndCopy();
  initNotificationsCenter();
});

/* ==========================================================================
   1. Sidebar Navigation (Desktop Collapse & Mobile Drawer)
   ========================================================================== */
function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebar-overlay');
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const desktopCollapseBtn = document.getElementById('desktop-collapse-btn');
  const mainContent = document.getElementById('main-content');

  // Mobile Drawer Toggle
  if (mobileMenuBtn && sidebar) {
    mobileMenuBtn.addEventListener('click', () => {
      openMobileSidebar();
    });
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
      closeMobileSidebar();
    });
  }

  function openMobileSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('-translate-x-full');
    sidebar.classList.add('translate-x-0');
    if (sidebarOverlay) {
      sidebarOverlay.classList.remove('hidden', 'opacity-0');
      sidebarOverlay.classList.add('opacity-100');
    }
    document.body.classList.add('overflow-hidden');
  }

  function closeMobileSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('translate-x-0');
    sidebar.classList.add('-translate-x-full');
    if (sidebarOverlay) {
      sidebarOverlay.classList.remove('opacity-100');
      sidebarOverlay.classList.add('opacity-0');
      setTimeout(() => sidebarOverlay.classList.add('hidden'), 200);
    }
    document.body.classList.remove('overflow-hidden');
  }

  window.NexusApp = window.NexusApp || {};
  window.NexusApp.openMobileSidebar = openMobileSidebar;
  window.NexusApp.closeMobileSidebar = closeMobileSidebar;

  // Desktop Mini Sidebar Toggle
  if (desktopCollapseBtn && sidebar) {
    desktopCollapseBtn.addEventListener('click', () => {
      const isMini = sidebar.classList.contains('w-20');
      if (isMini) {
        // Expand
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');
        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('.sidebar-heading').forEach(el => el.classList.remove('hidden'));
        if (mainContent) {
          mainContent.classList.remove('lg:pl-20');
          mainContent.classList.add('lg:pl-64');
        }
        localStorage.setItem('nexus_sidebar_collapsed', 'false');
      } else {
        // Collapse to Mini
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');
        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.sidebar-heading').forEach(el => el.classList.add('hidden'));
        if (mainContent) {
          mainContent.classList.remove('lg:pl-64');
          mainContent.classList.add('lg:pl-20');
        }
        localStorage.setItem('nexus_sidebar_collapsed', 'true');
      }
    });

    // Check saved state
    if (localStorage.getItem('nexus_sidebar_collapsed') === 'true' && window.innerWidth >= 1024) {
      sidebar.classList.remove('w-64');
      sidebar.classList.add('w-20');
      document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.sidebar-heading').forEach(el => el.classList.add('hidden'));
      if (mainContent) {
        mainContent.classList.remove('lg:pl-64');
        mainContent.classList.add('lg:pl-20');
      }
    }
  }

  // Sidebar Submenus (Accordion)
  const menuToggles = document.querySelectorAll('[data-submenu-toggle]');
  menuToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = toggle.getAttribute('data-submenu-toggle');
      const targetMenu = document.getElementById(targetId);
      const chevron = toggle.querySelector('.submenu-chevron');

      if (targetMenu) {
        const isHidden = targetMenu.classList.contains('hidden');
        if (isHidden) {
          targetMenu.classList.remove('hidden');
          if (chevron) chevron.classList.add('rotate-90');
        } else {
          targetMenu.classList.add('hidden');
          if (chevron) chevron.classList.remove('rotate-90');
        }
      }
    });
  });
}

/* ==========================================================================
   2. Dropdowns Management
   ========================================================================== */
function initDropdowns() {
  // Delegated: works for elements rendered later (Livewire updates included)
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-dropdown-toggle]');
    if (!trigger) return;

    e.stopPropagation();
    const targetDropdown = document.getElementById(trigger.getAttribute('data-dropdown-toggle'));
    if (!targetDropdown) return;

    const isClosed = targetDropdown.classList.contains('hidden');
    closeAllDropdowns();
    if (isClosed) {
      targetDropdown.classList.remove('hidden');
    }
  });

  // Hover-open dropdowns: any [data-dropdown-hover="menu-id"] wrapper opens its
  // menu on hover and closes it shortly after the pointer leaves (grace delay
  // so moving from the trigger into the menu doesn't close it). Click toggling
  // above still works, which keeps touch devices covered.
  let hoverCloseTimer = null;
  document.querySelectorAll('[data-dropdown-hover]').forEach((wrap) => {
    const menu = document.getElementById(wrap.getAttribute('data-dropdown-hover'));
    if (!menu) return;

    wrap.addEventListener('mouseenter', () => {
      clearTimeout(hoverCloseTimer);
      closeAllDropdowns();
      menu.classList.remove('hidden');
    });

    wrap.addEventListener('mouseleave', () => {
      hoverCloseTimer = setTimeout(() => menu.classList.add('hidden'), 200);
    });
  });

  // Close when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-dropdown-menu]') && !e.target.closest('[data-dropdown-toggle]')) {
      closeAllDropdowns();
    }
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllDropdowns();
    }
  });

  function closeAllDropdowns() {
    document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
      menu.classList.add('hidden');
    });
  }
}

/* ==========================================================================
   3. Modal Dialogs
   ========================================================================== */
function initModals() {
  // Delegated open/close: survives Livewire DOM updates
  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-modal-open]');
    if (opener) {
      e.preventDefault();
      openModal(opener.getAttribute('data-modal-open'));
      return;
    }
    const closer = e.target.closest('[data-modal-close]');
    if (closer) {
      e.preventDefault();
      closeModal(closer.getAttribute('data-modal-close'));
    }
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const openModal = document.querySelector('.modal:not(.hidden)');
      if (openModal) {
        closeModal(openModal.id);
      }
    }
  });
}

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  document.body.classList.remove('overflow-hidden');
}

window.NexusApp = window.NexusApp || {};
window.NexusApp.openModal = openModal;
window.NexusApp.closeModal = closeModal;

/* ==========================================================================
   4. Global Search Modal (Cmd+K / Ctrl+K)
   ========================================================================== */
function initGlobalSearch() {
  const searchModal = document.getElementById('search-modal');
  const searchInput = document.getElementById('global-search-input');
  const searchTriggers = document.querySelectorAll('[data-trigger="search"]');

  if (!searchModal) return;

  searchTriggers.forEach(btn => {
    btn.addEventListener('click', () => {
      openModal('search-modal');
      setTimeout(() => searchInput && searchInput.focus(), 50);
    });
  });

  // Keyboard shortcut listener
  document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
      e.preventDefault();
      if (searchModal.classList.contains('hidden')) {
        openModal('search-modal');
        setTimeout(() => searchInput && searchInput.focus(), 50);
      } else {
        closeModal('search-modal');
      }
    }
  });

  // Quick filter inside search modal
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase().trim();
      const items = searchModal.querySelectorAll('.search-result-item');
      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query) || query === '') {
          item.classList.remove('hidden');
        } else {
          item.classList.add('hidden');
        }
      });
    });
  }
}

/* ==========================================================================
   5. Toast Notification System
   ========================================================================== */
function showToast({ title = 'Notification', message = '', type = 'info', duration = 4000 }) {
  let container = document.getElementById('nexus-toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'nexus-toast-container';
    container.className = 'fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none';
    document.body.appendChild(container);
  }

  const icons = {
    success: `<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
    error: `<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
    warning: `<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
    info: `<svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
  };

  const toast = document.createElement('div');
  toast.className = 'pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-white dark:bg-dark-800 shadow-xl border border-slate-200 dark:border-dark-700 text-slate-800 dark:text-slate-100 transform transition-all duration-300 translate-y-2 opacity-0';
  toast.innerHTML = `
    <div class="flex-shrink-0 mt-0.5">${icons[type] || icons.info}</div>
    <div class="flex-1">
      <h4 class="text-sm font-semibold">${title}</h4>
      ${message ? `<p class="text-xs text-slate-500 dark:text-dark-300 mt-0.5">${message}</p>` : ''}
    </div>
    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" onclick="this.parentElement.remove()">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
  `;

  container.appendChild(toast);

  // Trigger animation
  requestAnimationFrame(() => {
    toast.classList.remove('translate-y-2', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
  });

  // Auto remove
  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-y-2');
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

window.NexusApp = window.NexusApp || {};
window.NexusApp.showToast = showToast;

/* ==========================================================================
   6. Tab Switcher
   ========================================================================== */
function initTabs() {
  // Delegated: tab groups keep working after any Livewire DOM update
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-tab-target]');
    if (!btn) return;
    const group = btn.closest('[data-tabs-group]');
    if (!group) return;

    e.preventDefault();
    const targetPanel = document.querySelector(btn.getAttribute('data-tab-target'));
    const buttons = group.querySelectorAll('[data-tab-target]');

    buttons.forEach(b => {
      b.classList.remove('text-brand-600', 'border-brand-600', 'bg-brand-50', 'dark:bg-brand-950/40', 'dark:text-brand-400');
      b.classList.add('text-slate-500', 'dark:text-dark-400');
    });

    btn.classList.add('text-brand-600', 'border-brand-600', 'bg-brand-50', 'dark:bg-brand-950/40', 'dark:text-brand-400');
    btn.classList.remove('text-slate-500', 'dark:text-dark-400');

    if (targetPanel && targetPanel.parentElement) {
      targetPanel.parentElement.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
      });
      targetPanel.classList.remove('hidden');
    }
  });
}

/* ==========================================================================
   7. Copy to Clipboard Utility
   ========================================================================== */
function initTooltipsAndCopy() {
  // Delegated: copy buttons work even when rendered after page load
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-copy-text]');
    if (!btn) return;
    {
      const textToCopy = btn.getAttribute('data-copy-text');
      try {
        await navigator.clipboard.writeText(textToCopy);
        showToast({
          title: 'Copied!',
          message: 'Text copied to your clipboard.',
          type: 'success',
          duration: 2500
        });
      } catch (err) {
        showToast({
          title: 'Copy failed',
          message: 'Unable to copy to clipboard.',
          type: 'error',
          duration: 2500
        });
      }
    }
  });
}

/* ==========================================================================
   8. Notifications Center
   ========================================================================== */
function initNotificationsCenter() {
  const markAllReadBtn = document.getElementById('mark-all-read-btn');
  const notifBadge = document.getElementById('notif-unread-badge');

  if (markAllReadBtn) {
    markAllReadBtn.addEventListener('click', (e) => {
      e.preventDefault();
      document.querySelectorAll('.notification-item.unread').forEach(item => {
        item.classList.remove('unread', 'bg-brand-50/40', 'dark:bg-brand-950/20');
        const unreadDot = item.querySelector('.unread-dot');
        if (unreadDot) unreadDot.remove();
      });
      if (notifBadge) {
        notifBadge.classList.add('hidden');
      }
      showToast({
        title: 'All caught up!',
        message: 'All notifications marked as read.',
        type: 'info',
        duration: 2000
      });
    });
  }
}
