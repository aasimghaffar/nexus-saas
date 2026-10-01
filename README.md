<p align="center">
  <img src="public/assets/images/logo-light.svg" alt="Nexus SaaS" height="42">
</p>

<h1 align="center">Nexus SaaS — Laravel Admin & Starter Kit</h1>

<p align="center">
  Production-ready Laravel SaaS starter kit — auth + 2FA, roles, Stripe billing, kanban, tickets, chat, REST API. Livewire 3 + Tailwind CSS, one-page installer.
</p>

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?logo=laravel&logoColor=white">
  <img alt="Livewire" src="https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white">
  <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white">
  <img alt="License" src="https://img.shields.io/badge/license-commercial-blue">
</p>

---

Nexus SaaS is a **complete, working application** — not a static template. All 41 screens are functional and database-driven, and it installs in minutes with a one-page web installer. Use it as a ready-made team workspace, or as a clean Laravel starter kit with authentication, roles, billing and CRUD patterns already solved in idiomatic TALL-stack code.

<!-- Screenshots: add to /screenshots and un-comment
<p align="center">
  <img src="screenshots/dashboard-light.png" width="49%">
  <img src="screenshots/dashboard-dark.png" width="49%">
</p>
-->

## Features

**Authentication & security** — login, registration, email verification, password reset (Laravel Fortify) · two-factor authentication with TOTP QR + recovery codes · session lock screen · active-sessions list with "log out other devices" · full audit trail of sign-ins and key actions

**Team & roles** — Super Admin / Admin / Editor / Viewer out of the box (Spatie Permissions) · email invitations · account suspension · per-plan seat limits · profile with avatar upload and notification preferences

**Stripe billing** — subscription checkout, plan switching with proration, cancel & resume (Laravel Cashier) · Billing Portal · invoice list, printable view and PDF download · safe preview mode when Stripe isn't configured

**Work management** — projects with live progress · drag-and-drop kanban with persisted column and card order · support tickets with customer/agent views, assignment and an open → pending → resolved workflow

**Communication & storage** — team chat with unread badges and online status (no websocket server required) · internal mailbox (inbox / sent / starred / trash) · calendar with color-coded events · private file manager with folders, quotas and ownership-checked downloads

**Platform** — REST API with Sanctum API keys and read/write abilities · integrations hub (Slack, Discord, Zapier, Mailchimp webhooks with test delivery) · FAQ / help center with admin editor · global search (⌘K) · three live dashboards · in-app documentation · themed 404 / 500 / maintenance pages · 10-page UI component kit · dark & light mode, fully responsive

## Quick start

Requirements: PHP 8.2+, Composer 2. Node.js is optional (compiled assets are included).

```bash
git clone https://github.com/YOUR-ORG/nexus-saas.git
cd nexus-saas
composer install
php artisan serve
```

Open http://localhost:8000 — the one-page installer checks server requirements, connects your database (MySQL/MariaDB or SQLite), and creates your admin account. Tick **Install demo content** for a sample workspace.

Prefer the terminal? `php artisan nexus:install --demo`

**Demo accounts** (with demo content, password `password`): `sarah@example.com` (admin) · `liam@example.com` (viewer)

## Tech stack

Laravel 11/12 · Livewire 3 · Tailwind CSS 3 · Alpine.js · Laravel Fortify · Laravel Sanctum · Laravel Cashier (Stripe) · Spatie Permissions & Activitylog · Vite · ApexCharts

## Configuration

| What | Where |
|---|---|
| Mail (SMTP) | `MAIL_*` in `.env` — defaults to the `log` driver |
| Stripe | `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`, `STRIPE_PRICE_*` in `.env`; plan names & seat limits in `config/nexus.php` |
| Roles & permissions | `database/seeders/RolesAndPermissionsSeeder.php` |
| Brand colour / logo | `tailwind.config.js` (`brand` palette), `public/assets/images/logo-*.svg`, then `npm run build` |
| Demo server | `NEXUS_DEMO=true` + schedule `php artisan nexus:demo-reset` hourly |

Full guide: [DOCUMENTATION.md](DOCUMENTATION.md) — also rendered inside the app at `/documentation`.

## REST API

Generate a token under **API Keys**, then:

```bash
curl https://your-app.test/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" -H "Accept: application/json"
```

Endpoints: `GET /api/v1/projects` · `GET /api/v1/tasks` · `POST /api/v1/tasks` (write ability) · `GET /api/v1/tickets`

## Development

```bash
npm install
npm run dev      # Vite with live reload
npm run build    # production assets → public/build
php artisan test
```

## Support & license

Nexus SaaS is commercial software. One license covers one end product; see [LICENSE](LICENSE) for terms. Bug reports and questions: open an issue or contact [CubixSol](https://cubixsol.com).

---

<p align="center">Built by <a href="https://cubixsol.com">CubixSol</a></p>
