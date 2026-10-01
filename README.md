<p align="center">
  <img src="public/assets/images/logo-light.svg" alt="Nexus SaaS" height="42">
</p>

<h1 align="center">Nexus SaaS — Laravel Admin & Starter Kit</h1>

<p align="center">
  A complete, database-driven team workspace and SaaS starter kit: auth + 2FA, roles, Stripe billing, projects, kanban, tickets, chat, mail, calendar, files and a REST API — built on Laravel, Livewire 3 and Tailwind CSS, with a one-page installer.
</p>

<p align="center">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?logo=laravel&logoColor=white">
  <img alt="Livewire" src="https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire&logoColor=white">
  <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white">
  <img alt="License" src="https://img.shields.io/badge/license-commercial-blue">
</p>

<p align="center">
  <img src="screenshots/03-dashboards/overview.png" width="49%" alt="Overview dashboard, light mode">
  <img src="screenshots/10-dark-mode/overview-dark.png" width="49%" alt="Overview dashboard, dark mode">
</p>

---

## Contents

1. [About the project](#about-the-project)
2. [Features](#features)
3. [Requirements](#requirements)
4. [Setup from scratch](#setup-from-scratch)
5. [Configuration](#configuration)
6. [System architecture](#system-architecture)
7. [Project structure](#project-structure)
8. [REST API](#rest-api)
9. [Screenshots](#screenshots)
10. [Development](#development)
11. [Troubleshooting](#troubleshooting)
12. [Support & license](#support--license)

---

## About the project

Nexus SaaS is a **working application, not a static template**. Every screen is backed by the database, and it can be used two ways:

- **As a ready-made team workspace** — install it, invite your team and start using projects, the kanban board, tickets, chat, mail, calendar and file storage.
- **As a Laravel starter kit** — authentication, two-factor, roles and permissions, subscription billing, an audit trail, an API and CRUD patterns are already solved in idiomatic Laravel + Livewire code, so you build your own product on top.

It is a single Laravel application (no separate frontend project, no websocket server, no queue worker required) that installs through a one-page web installer or a single Artisan command.

## Features

| Area | What you get |
|---|---|
| **Authentication & security** | Login, registration, email verification, password reset (Laravel Fortify) · two-factor authentication with TOTP QR code and recovery codes · session lock screen · active-sessions list with "log out other devices" · login rate limiting · audit trail of sign-ins and key actions |
| **Team & roles** | Super Admin / Admin / Editor / Viewer (Spatie Permissions) · email invitations · account suspension · per-plan seat limits · profile with avatar upload and notification preferences |
| **Stripe billing** | Subscription checkout, plan switching with proration, cancel and resume (Laravel Cashier) · Billing Portal · invoice list, printable view and PDF download · safe preview mode when Stripe is not configured |
| **Work management** | Projects with live progress · drag-and-drop kanban with persisted column and card order · support tickets with customer and agent views, assignment, and an open → pending → resolved workflow |
| **Communication & storage** | Team chat with unread badges and online status (polling, no websocket server) · internal mailbox (inbox / sent / starred / trash) · calendar with colour-coded events · private file manager with folders, quotas and ownership-checked downloads |
| **Platform** | REST API with Sanctum API keys and read/write abilities · integrations hub (Slack, Discord, Zapier, Mailchimp webhooks with test delivery) · FAQ / help centre with admin editor · global search (Ctrl/⌘ + K) · three live dashboards · in-app documentation · themed 404 / 500 / maintenance pages · 10-page UI component kit · dark and light mode · fully responsive |

## Requirements

### Server

| Requirement | Version / notes |
|---|---|
| **PHP** | 8.2 or newer |
| **PHP extensions** | `pdo`, `mbstring`, `openssl`, `tokenizer`, `ctype`, `json`, `curl`, `fileinfo`, `gd` — plus `pdo_sqlite` or `pdo_mysql` for your chosen database |
| **Composer** | 2.x |
| **Database** | SQLite (quickest, good for local and small installs) **or** MySQL 5.7+ / MariaDB 10.3+ |
| **Web server** | `php artisan serve` for local use; Apache or nginx in production, with the document root pointed at `public/` |
| **Writable paths** | `storage/`, `bootstrap/cache/`, and `.env` (or the project root, so `.env` can be created) |
| **HTTPS** | Strongly recommended in production |

The installer checks all of the above live and refuses to continue until everything is green.

### Optional

| Requirement | When you need it |
|---|---|
| **Node.js 18+ and npm** | Only if you change styles or JavaScript. Compiled assets ship in `public/build`, so Node is **not** needed to run the app. |
| **SMTP account** | To send real email (verification, password reset, invitations). Without it, mail is written to `storage/logs/laravel.log`. |
| **Stripe account** | To take real payments. Without it, billing pages run in preview mode. |
| **Cron** | For the scheduler — only used by the hourly demo reset on public demo servers. |

### Main dependencies

| Package | Purpose |
|---|---|
| `laravel/framework` ^11.31 \| ^12.0 | Application framework |
| `livewire/livewire` ^3.5 | Reactive server-rendered components (bundles Alpine.js) |
| `laravel/fortify` ^1.24 | Headless authentication backend, 2FA |
| `laravel/sanctum` ^4.0 | API tokens with abilities |
| `laravel/cashier` ^15.4 | Stripe subscriptions and invoices |
| `spatie/laravel-permission` ^6.9 | Roles and permissions |
| `spatie/laravel-activitylog` ^4.8 | Audit trail |
| `dompdf/dompdf` | Invoice PDF generation |
| `bacon/bacon-qr-code` | 2FA QR codes |
| `tailwindcss` ^3.4, `vite` ^5.4, `apexcharts` ^3.54 | Front-end build, styling and charts |

## Setup from scratch

### 1. Get the code and install PHP dependencies

```bash
git clone https://github.com/YOUR-ORG/nexus-saas.git
cd nexus-saas
composer install
```

`composer install` creates `.env` from `.env.example` and generates the `APP_KEY`. If that step is skipped (for example on a direct upload), the app creates both on the first request — there is no manual step.

### 2. (MySQL only) create an empty database

```sql
CREATE DATABASE nexus_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Skip this for SQLite — the installer creates `database/database.sqlite` itself.

### 3. Start the server

```bash
php artisan serve --port=8001
```

Any port works; use the same one as your application URL in the next step.

### 4. Install — choose one

**Option A — web installer.** Open `http://127.0.0.1:8001`. You are redirected to `/install`, which:

1. checks server requirements,
2. asks for the application name and URL,
3. tests your database connection before writing anything,
4. creates your super-admin account,
5. optionally installs demo content (sample team, projects, board tasks, tickets).

<p align="center"><img src="screenshots/01-installer/installer.png" width="60%" alt="One-page installer"></p>

**Option B — command line.**

```bash
# SQLite + demo content
php artisan nexus:install --demo \
  --app-name="Nexus SaaS" --app-url=http://127.0.0.1:8001 \
  --admin-name="Your Name" --admin-email=you@example.com --admin-password=change-me-please

# MySQL
php artisan nexus:install --db=mysql \
  --db-host=127.0.0.1 --db-port=3306 --db-database=nexus_saas \
  --db-username=root --db-password=secret \
  --app-url=http://127.0.0.1:8001 \
  --admin-name="Your Name" --admin-email=you@example.com --admin-password=change-me-please
```

Any option you leave out is asked for interactively.

Both installers do the same thing: run the migrations, seed roles, permissions and FAQs, create the super-admin, optionally seed demo content, write your settings to `.env`, and create `storage/installed.json` to lock the installer.

> **Note:** the installer sets `APP_ENV=production` and `APP_DEBUG=false` in `.env`. For local development, change them back to `local` / `true` and run `php artisan config:clear`.

### 5. Sign in

Go to `/login` and sign in with the admin account you created.

With demo content installed, these accounts are also available (password `password`):

| Account | Role | What it shows |
|---|---|---|
| `sarah@example.com` | Admin | Ticket agent view, user management |
| `marcus@example.com`, `elena@example.com` | Editor | Projects and tickets |
| `liam@example.com`, `dana@example.com` | Viewer | Customer ticket view, limited menus |

### 6. Recommended finishing steps

```bash
php artisan storage:link     # makes uploaded avatars publicly reachable
```

Add the scheduler to cron only if you run a public demo (see [Configuration](#configuration)):

```
* * * * * php /path/to/nexus-saas/artisan schedule:run >> /dev/null 2>&1
```

### Production deployment

1. Upload the project and point the domain's document root at `nexus-saas/public` — never the project root.
2. Run `composer install --no-dev`.
3. Visit the domain and complete the installer.

On XAMPP, either create a VirtualHost whose `DocumentRoot` is `nexus-saas/public`, or browse to `http://localhost/nexus-saas/public` and use that as the application URL.

### Reinstalling or recovering from a failed install

```bash
rm -f storage/installed.json database/database.sqlite .env
php artisan optimize:clear
php artisan nexus:install --demo        # or revisit /install
```

To re-run the installer over an existing database, delete only `storage/installed.json`, or pass `--fresh` to `nexus:install`.

## Configuration

| What | Where |
|---|---|
| App name, URL, environment | `APP_*` in `.env` |
| Database | `DB_*` in `.env` (written by the installer) |
| Mail (SMTP) | `MAIL_*` in `.env` — defaults to the `log` driver |
| Stripe | `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`, `STRIPE_PRICE_STARTER`, `STRIPE_PRICE_TEAM`, `STRIPE_PRICE_ENTERPRISE` in `.env`; webhook endpoint `https://your-domain/stripe/webhook` |
| Plans, prices, seat limits, role labels | `config/nexus.php` |
| Roles and permissions | `database/seeders/RolesAndPermissionsSeeder.php` |
| Brand colour and logo | `brand` palette in `tailwind.config.js`, `public/assets/images/logo-*.svg`, then `npm run build` |
| Queues | `QUEUE_CONNECTION=sync` by default (no worker). For busy sites use `database` and run `php artisan queue:work`. |
| Public demo server | `NEXUS_DEMO=true`, plus the cron entry above — runs `nexus:demo-reset` hourly. The command refuses to run unless `NEXUS_DEMO` is true. |

After changing `.env`, run `php artisan config:clear`. The full operations guide is in [DOCUMENTATION.md](DOCUMENTATION.md), also rendered in the app at `/documentation`.

## System architecture

Nexus SaaS is a server-rendered monolith on the TALL stack. The browser receives Blade-rendered HTML; interactive screens are Livewire components that re-render over AJAX; a small Sanctum-protected JSON API sits alongside for external clients.

```mermaid
flowchart TB
    subgraph Client
        B[Browser<br/>Blade HTML + Tailwind CSS<br/>Alpine.js · ApexCharts]
        X[External API client<br/>Bearer token]
    end

    subgraph App["Laravel application"]
        direction TB
        MW[Middleware pipeline<br/>RedirectIfNotInstalled → auth → verified → unlocked → lastseen]
        subgraph Web["Web layer"]
            C[Controllers<br/>Dashboard · Profile · Billing · Search · Installer]
            L[Livewire components<br/>Users · Kanban · Projects · Tickets · Chat · Mail<br/>Calendar · Files · API Keys · Integrations · FAQ · Activity]
        end
        API[API layer<br/>/api/v1 — auth:sanctum + abilities]
        AUTH[Fortify actions<br/>login · register · reset · 2FA]
        AZ[Authorization<br/>Spatie roles/permissions · Policies · Gate::before]
        M[Eloquent models]
    end

    subgraph Data
        DB[(SQLite or MySQL)]
        FS[(Local disks<br/>public: avatars<br/>private: user files)]
    end

    subgraph External["External services (optional)"]
        ST[Stripe]
        SM[SMTP]
        WH[Slack · Discord · Zapier · Mailchimp webhooks]
    end

    B -->|HTTP / Livewire AJAX| MW --> Web
    X -->|JSON| API
    B --> AUTH
    Web --> AZ --> M
    API --> AZ
    AUTH --> M
    M --> DB
    Web --> FS
    C <-->|Cashier| ST
    ST -->|/stripe/webhook| App
    App --> SM
    L --> WH
```

### Layers

| Layer | Location | Responsibility |
|---|---|---|
| **Routing** | `routes/web.php`, `routes/api.php`, `routes/console.php` | Web pages, the versioned API, and the scheduled demo reset |
| **Middleware** | `app/Http/Middleware`, `bootstrap/app.php` | `RedirectIfNotInstalled` runs first on every web request and sends un-installed apps to `/install`; `unlocked` enforces the lock screen; `lastseen` powers online status |
| **Controllers** | `app/Http/Controllers` | Conventional request/response pages: dashboards, profile, billing and invoices, search, docs, UI kit, installer |
| **Livewire components** | `app/Livewire` + `resources/views/livewire` | Every interactive screen — each is a full-page component mapped directly to a route |
| **Authentication** | `app/Actions/Fortify`, `app/Providers/FortifyServiceProvider.php` | Fortify handles the auth flows headlessly; the provider binds the Blade views, blocks suspended accounts and rate-limits login |
| **Authorization** | `app/Policies`, Spatie tables | Permissions are checked with `can:` route middleware, `@can` in views, and policies. Super Admin bypasses all checks through `Gate::before` |
| **Domain models** | `app/Models` | Eloquent models; `User` composes billing, API tokens, roles, 2FA and activity logging through traits |
| **Installer** | `app/Support/Installer.php`, `InstallRunner.php`, `app/Console/Commands/NexusInstall.php` | One shared engine behind both the web installer and `nexus:install` |
| **Presentation** | `resources/views`, `resources/css`, `resources/js` | Blade layouts and partials, Tailwind, and plain-JS modules (theme, charts, kanban drag-and-drop) bundled by Vite into `public/build` |

### Request lifecycle

```mermaid
sequenceDiagram
    participant U as Browser
    participant MW as Middleware
    participant LW as Livewire component
    participant G as Gate / Policy
    participant DB as Database

    U->>MW: GET /kanban
    MW->>MW: installed? authenticated? verified? unlocked?
    MW->>LW: mount + render
    LW->>DB: load columns and tasks
    LW-->>U: full HTML page
    U->>LW: drag card (Livewire AJAX call)
    LW->>G: authorize
    LW->>DB: persist new column / position
    LW-->>U: HTML diff, DOM patched in place
```

### Roles and permissions

| Permission | Super Admin | Admin | Editor | Viewer |
|---|:-:|:-:|:-:|:-:|
| `users.view` | ✔ | ✔ | ✔ | — |
| `users.manage` | ✔ | ✔ | — | — |
| `activity.view` | ✔ | ✔ | — | — |
| `billing.manage` | ✔ | ✔ | — | — |
| `projects.manage` | ✔ | ✔ | ✔ | — |
| `tickets.manage` | ✔ | ✔ | ✔ | — |
| `settings.manage` | ✔ | ✔ | — | — |

Admins cannot edit, suspend or delete a Super Admin. Users without `tickets.manage` see only their own tickets, in both the UI and the API.

### Data model

```mermaid
erDiagram
    USERS ||--o{ PROJECTS : owns
    PROJECTS ||--o{ TASKS : contains
    KANBAN_COLUMNS ||--o{ TASKS : holds
    USERS ||--o{ TASKS : "assigned to"
    USERS ||--o{ TICKETS : "raises / is assigned"
    TICKETS ||--o{ TICKET_REPLIES : has
    USERS ||--o{ CHAT_MESSAGES : "sends / receives"
    USERS ||--o{ MAIL_MESSAGES : "sends / receives"
    USERS ||--o{ EVENTS : creates
    USERS ||--o{ FOLDERS : owns
    FOLDERS ||--o{ FILES : contains
    USERS ||--o{ FILES : owns
    USERS ||--o{ PERSONAL_ACCESS_TOKENS : "API keys"
    USERS ||--o{ SUBSCRIPTIONS : "Stripe (Cashier)"
    USERS }o--o{ ROLES : has
    ROLES }o--o{ PERMISSIONS : grants
    USERS ||--o{ ACTIVITY_LOG : causes
```

Standalone tables: `settings` (key/value, used by integrations), `faqs`, plus Laravel's `sessions`, `cache`, `jobs` and `password_reset_tokens`.

### Design decisions worth knowing

- **No websocket server.** Chat refreshes with Livewire polling every five seconds, so the app runs on ordinary shared hosting.
- **No queue worker by default.** `QUEUE_CONNECTION=sync`; switch to `database` when volume calls for it.
- **Installer-first boot.** Sessions start on the `file` driver so the app can serve `/install` before any database exists, then switch to the `database` driver once installed.
- **Billing degrades gracefully.** Without Stripe keys the billing screens render in preview mode instead of failing.
- **Private files stay private.** User uploads go to the private disk and are streamed through an ownership check, never linked directly.

## Project structure

```
nexus-saas/
├── app/
│   ├── Actions/Fortify/      # registration, password and profile actions
│   ├── Console/Commands/     # nexus:install, nexus:demo-reset
│   ├── Http/
│   │   ├── Controllers/      # dashboards, profile, billing, API, installer, …
│   │   └── Middleware/       # install redirect, lock screen, last-seen
│   ├── Listeners/            # auth events → activity log
│   ├── Livewire/             # one folder per interactive screen
│   ├── Models/               # Eloquent models
│   ├── Policies/             # User, Project, Ticket
│   ├── Providers/            # App + Fortify service providers
│   └── Support/              # Installer engine
├── bootstrap/app.php         # routing + middleware registration
├── config/nexus.php          # plans, seats, role labels, demo switch
├── database/
│   ├── migrations/           # framework, packages, then Nexus tables
│   └── seeders/              # roles, admin, demo users, workspace, FAQs
├── public/
│   ├── assets/images/        # logos, avatars, brand icons, illustrations
│   └── build/                # compiled CSS/JS (committed)
├── resources/
│   ├── css/app.css           # Tailwind entry
│   ├── js/                   # theme, charts, kanban, UI controller
│   └── views/                # layouts, partials, auth, billing, livewire, ui, errors
├── routes/                   # web.php, api.php, console.php
├── screenshots/              # every screen, grouped by area
└── tests/                    # PHPUnit (Feature, Unit)
```

## REST API

Generate a token under **API Keys & Tokens**, choosing read and/or write abilities, then send it as a Bearer token:

```bash
curl http://127.0.0.1:8001/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" -H "Accept: application/json"
```

| Method | Endpoint | Ability | Returns |
|---|---|---|---|
| `GET` | `/api/v1/projects` | read | Projects with task counts, 25 per page |
| `GET` | `/api/v1/tasks` | read | Tasks with column, assignee and project, 50 per page |
| `POST` | `/api/v1/tasks` | write | Creates a task — `title` and `kanban_column_id` required; `description`, `project_id`, `priority` (`low`/`medium`/`high`) optional |
| `GET` | `/api/v1/tickets` | read | Tickets with reply counts, 25 per page; limited to your own unless you hold `tickets.manage` |

<p align="center"><img src="screenshots/07-platform/api-keys.png" width="80%" alt="API keys"></p>

## Screenshots

All screenshots are in [screenshots/](screenshots/), grouped by area. A selection:

### Dashboards

| Overview | CRM & Sales | Traffic Analytics |
|---|---|---|
| ![Overview](screenshots/03-dashboards/overview.png) | ![CRM](screenshots/03-dashboards/crm-sales.png) | ![Analytics](screenshots/03-dashboards/traffic-analytics.png) |

### Work management

| Projects | Kanban board | Tickets | Ticket detail |
|---|---|---|---|
| ![Projects](screenshots/04-applications/projects.png) | ![Kanban](screenshots/04-applications/kanban-board.png) | ![Tickets](screenshots/04-applications/tickets-list.png) | ![Ticket](screenshots/04-applications/ticket-detail.png) |

### Communication & storage

| Chat | Mailbox | Calendar | File manager |
|---|---|---|---|
| ![Chat](screenshots/04-applications/chat.png) | ![Mail](screenshots/04-applications/mailbox.png) | ![Calendar](screenshots/04-applications/calendar.png) | ![Files](screenshots/04-applications/file-manager.png) |

### Team, account & audit

| Users & Team | Profile & security | Activity logs |
|---|---|---|
| ![Users](screenshots/05-management/users-and-team.png) | ![Profile](screenshots/05-management/profile-account-settings.png) | ![Activity](screenshots/05-management/activity-logs.png) |

### Billing

| Pricing | Billing & plans | Invoices |
|---|---|---|
| ![Pricing](screenshots/06-billing/pricing.png) | ![Billing](screenshots/06-billing/billing-and-plans.png) | ![Invoices](screenshots/06-billing/invoices.png) |

### Authentication

| Sign in | Register | Two-factor challenge | Lock screen |
|---|---|---|---|
| ![Login](screenshots/02-auth/login.png) | ![Register](screenshots/02-auth/register.png) | ![2FA](screenshots/02-auth/two-factor-challenge.png) | ![Lock](screenshots/02-auth/lock-screen.png) |

### Platform

| Integrations | FAQ / help centre | Global search |
|---|---|---|
| ![Integrations](screenshots/07-platform/integrations.png) | ![FAQ](screenshots/07-platform/faq-help-center.png) | ![Search](screenshots/07-platform/global-search-modal.png) |

### Dark mode and mobile

| Kanban (dark) | Users (dark) | Mobile dashboard | Mobile navigation |
|---|---|---|---|
| ![Kanban dark](screenshots/10-dark-mode/kanban-board-dark.png) | ![Users dark](screenshots/10-dark-mode/users-and-team-dark.png) | ![Mobile](screenshots/11-mobile/overview-mobile.png) | ![Drawer](screenshots/11-mobile/sidebar-drawer-mobile.png) |

### Full index

| Folder | Contents |
|---|---|
| [01-installer](screenshots/01-installer/) | One-page web installer |
| [02-auth](screenshots/02-auth/) | Login, register, forgot/reset password, verify email, confirm password, 2FA challenge, lock screen |
| [03-dashboards](screenshots/03-dashboards/) | Overview, CRM & Sales, Traffic Analytics |
| [04-applications](screenshots/04-applications/) | Projects, kanban, tickets, ticket detail, chat, mailbox, calendar, file manager |
| [05-management](screenshots/05-management/) | Users & Team, profile and account settings, activity logs |
| [06-billing](screenshots/06-billing/) | Pricing, billing and plans, invoices |
| [07-platform](screenshots/07-platform/) | API keys, integrations, FAQ, search, in-app docs, blank starter page |
| [08-ui-kit](screenshots/08-ui-kit/) | Components, buttons, alerts, badges, cards, forms, modals, tabs, tables, typography |
| [09-error-pages](screenshots/09-error-pages/) | 404, 500, 503 maintenance |
| [10-dark-mode](screenshots/10-dark-mode/) | Key screens in dark mode |
| [11-mobile](screenshots/11-mobile/) | Login, dashboard, kanban, tickets and navigation drawer at phone width |
| [12-roles](screenshots/12-roles/) | The same app as a Viewer: reduced menu, customer ticket view, 403 on restricted pages |

## Development

```bash
npm install
npm run dev        # Vite dev server with live reload
npm run build      # production assets → public/build
php artisan test   # PHPUnit
```

`public/build` is committed, so run `npm run build` and commit the result whenever you change anything under `resources/css` or `resources/js`, or Tailwind classes in the views.

## Troubleshooting

| Symptom | Fix |
|---|---|
| Redirected to `/install` on every page | The app is not installed — complete the installer, or check that `storage/installed.json` exists and `storage/` is writable. |
| "Already installed" from `nexus:install` | Delete `storage/installed.json` or pass `--fresh`. |
| "Vite manifest not found" or unstyled pages | `public/build` is missing — run `npm install && npm run build`. |
| 500 error after moving servers or editing `.env` | `php artisan optimize:clear`, and check `storage/` is writable. |
| "CSRF token mismatch" / logged out immediately | `APP_URL` does not match the address in your browser — fix it in `.env`, then `php artisan config:clear`. |
| Emails not arriving | With `MAIL_MAILER=log`, mail is written to `storage/logs/laravel.log`. Set real SMTP credentials to send. |
| Uploaded avatar does not display | Run `php artisan storage:link`. |
| Locked out by 2FA | Sign in with a recovery code, then regenerate codes in Account Settings. |
| Install failed part-way | See `storage/logs/installer.log`, then follow [Reinstalling](#reinstalling-or-recovering-from-a-failed-install). |

## Support & license

Nexus SaaS is commercial software. One license covers one end product; see [LICENSE](LICENSE) for terms. Bug reports and questions: open an issue or contact [CubixSol](https://cubixsol.com). Release notes are in [CHANGELOG.md](CHANGELOG.md).

---

<p align="center">Built by <a href="https://cubixsol.com">CubixSol</a></p>
