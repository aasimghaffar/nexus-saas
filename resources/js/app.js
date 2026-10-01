/**
 * Nexus SaaS — Vite entry point
 * Order matters: theme first (zero-flicker), then ApexCharts global,
 * then the Nexus UI controller and chart configs.
 * Livewire 3 ships its own Alpine instance — do NOT import alpinejs here.
 */
import './theme.js';

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import './nexus-app.js';
import './charts.js';
import './datatables.js';
import './kanban-livewire.js';
import './sidebar-active.js';

/* ---- Alpine components used by auth pages ----
   Registered on the alpine:init event fired by Livewire's bundled Alpine. */
document.addEventListener('alpine:init', () => {
    // Register page: live password strength meter (4 bars)
    Alpine.data('passwordStrength', () => ({
        strength: 0,
        label: 'Use 8+ characters with mixed case, numbers & symbols',
        barColor: 'bg-slate-300',
        textColor: 'text-slate-400',
        score(value) {
            let s = 0;
            if (value.length >= 8) s++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) s++;
            if (/\d/.test(value)) s++;
            if (/[^A-Za-z0-9]/.test(value)) s++;
            this.strength = s;
            const map = {
                0: ['bg-slate-300', 'text-slate-400', 'Too short'],
                1: ['bg-rose-500', 'text-rose-500', 'Weak password'],
                2: ['bg-amber-500', 'text-amber-500', 'Fair — add numbers or symbols'],
                3: ['bg-emerald-500', 'text-emerald-500', 'Good password'],
                4: ['bg-emerald-500', 'text-emerald-500', 'Strong password (8+ characters, symbols included)'],
            };
            [this.barColor, this.textColor, this.label] = map[s];
        },
    }));

    // 2FA challenge: six OTP boxes -> hidden combined "code" field
    Alpine.data('otpInput', () => ({
        digits: ['', '', '', '', '', ''],
        get combined() {
            return this.digits.join('');
        },
        onInput(i, e) {
            const v = e.target.value.replace(/\D/g, '').slice(-1);
            this.digits[i] = v;
            if (v && i < 5) {
                e.target.parentElement.querySelectorAll('input[type=text]')[i + 1]?.focus();
            }
        },
        onBackspace(i, e) {
            if (!this.digits[i] && i > 0) {
                e.target.parentElement.querySelectorAll('input[type=text]')[i - 1]?.focus();
            }
        },
        onPaste(e) {
            const text = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
            text.split('').forEach((c, idx) => (this.digits[idx] = c));
        },
    }));
});
