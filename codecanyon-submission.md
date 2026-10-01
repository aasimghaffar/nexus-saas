# CodeCanyon Submission Kit — Nexus SaaS (Laravel)

Everything to copy-paste into the Envato upload form, in form order.

---

## 1. Item Name (paste exactly)

Nexus SaaS - Laravel Admin & Starter Kit

## 2. Category

PHP Scripts → Project Management Tools

(There is no "Laravel" category — Laravel buyers find items via tags and
search, and this category matches the projects/kanban/tickets core.)

## 3. Price

- Regular License: $39 at launch. Raise to $49 after ~10–15 sales and a
  couple of reviews.
- Extended License: accept Envato's suggested multiple (~$195–245).
- Support: 6 months included; leave the extension price at Envato's default.

## 4. Tags (15 — paste as comma list)

laravel, livewire, tailwind css, saas, admin dashboard, starter kit,
stripe, subscription billing, kanban, support tickets, user management,
roles and permissions, rest api, admin panel, laravel boilerplate

## 5. Attributes

- High Resolution: Yes
- Compatible Browsers: IE11 unticked; tick Chrome, Firefox, Safari, Edge, Opera
- Software Version: tick PHP 8.2, 8.3, and any newer PHP versions offered
  (the app runs on PHP 8.2 – 8.5)
- Files Included: PHP Files, JavaScript JS, CSS Files, HTML Files
- Database: MySQL 5.7+ / MariaDB 10.3+ / SQLite (type into the field if free-text)

## 6. Item Description (paste into the description editor)

**Nexus SaaS** is a complete, production-ready SaaS application built on
Laravel, Livewire 3 and Tailwind CSS — not a static template. Every one
of its 41 screens is fully functional and database-driven, and it
installs in minutes with a one-page web installer (no Laravel knowledge
required).

**Use it two ways:** launch it as a ready-made team workspace, or strip
it for parts as the cleanest Laravel starter kit you've bought — auth,
roles, billing and CRUD patterns already solved in idiomatic TALL-stack
code.

FEATURES

Authentication & Security
- Login, registration, email verification, password reset (Laravel Fortify)
- Two-factor authentication (TOTP QR + recovery codes)
- Session lock screen, active session list with "log out other devices"
- Full audit trail: every sign-in, failed attempt, and key action logged

Team & Roles
- Four roles out of the box: Super Admin, Admin, Editor, Viewer
  (Spatie Permissions — add your own in one seeder)
- Invite members by email, suspend accounts, per-plan seat limits
- Profile with avatar upload, password change, notification preferences

Stripe Billing (Laravel Cashier)
- Subscription checkout, plan switching with proration, cancel/resume
- Stripe Billing Portal, invoice list, printable invoices + PDF download
- Safe preview mode when Stripe isn't configured — nothing ever crashes

Work Management
- Projects with live progress tracking
- Drag-and-drop Kanban board — column and card order persist instantly
- Support ticket system with customer/agent views, assignment, and a
  proper status workflow (open → pending → resolved)

Communication & Storage
- Team chat with unread badges and online status (no websocket server needed)
- Internal mailbox: inbox / sent / starred / trash
- Calendar with month view and color-coded events
- Private file manager with folders, quotas, and secure downloads

Platform
- REST API with Sanctum API keys (read/write abilities) — documented endpoints
- Integrations hub: Slack, Discord, Zapier, Mailchimp webhooks with
  one-click test delivery
- Searchable FAQ / help center with admin editor
- Global search (Cmd/Ctrl+K) across projects, tasks, tickets, members, FAQs
- Three live dashboards fed by real queries
- Themed 404 / 500 / maintenance pages, 10-page UI component kit
- Dark & light mode throughout, fully responsive

TECH STACK

Laravel 11/12 · Livewire 3 · Tailwind CSS 3 · Alpine.js · Laravel
Fortify · Laravel Sanctum · Laravel Cashier (Stripe) · Spatie
Permissions & Activitylog · Vite

INSTALLATION

1. Upload, point your web root at /public
2. Run: composer install
3. Open your site — a one-page installer checks requirements, connects
   your database (MySQL or SQLite), and creates your admin account
4. Optional demo content with one checkbox

Node.js is NOT required on your server — compiled assets are included.

WHAT'S INCLUDED

- Full source code (no encryption, no ionCube)
- Plain-English documentation for non-developers
- One-page web installer + CLI installer
- Demo data seeder
- 6 months support

CHANGELOG — see the Changelog tab. v1.0.0 initial release.

## 7. Message to the Review Team (paste into reviewer field)

This is an original, complete Laravel application (Laravel 11/12,
Livewire 3, Fortify, Cashier, Sanctum, Spatie packages). The frontend
design is my own work — I also sell it separately as a static HTML
template on ThemeForest under the same author account (shared design
system, disclosed for transparency).

Install for review: upload, run "composer install", then visit the site
— a one-page installer handles everything (SQLite option = zero DB
setup). Tick "Install demo content" for sample data. Purchase-code field
is format-validated (UUID) in this release; any UUID works for review.
Demo account passwords are "password". A CLI installer is also included:
php artisan nexus:install --demo.

No encrypted code, no external CDN dependencies at runtime, compiled
assets included, APP_DEBUG=false written by the installer.

## 8. Files to Upload

Run: bash build-release.sh 1.0.0  →  upload dist/nexus-saas-laravel-v1.0.0.zip
as the Main File. It contains Main File/nexus-saas/, Documentation/, README.txt.

- Thumbnail: thumbnail-80x80.png (provided)
- Inline preview: main-preview-590x300.png (provided — first image, exactly 590x300)
- Screenshots zip: number them 01_….png/jpg (01 = the 590x300 preview),
  then full-page captures at 1920px wide, in this order:
  02 dashboard (light), 03 dashboard (dark), 04 kanban board,
  05 tickets agent view, 06 ticket thread, 07 users & team,
  08 billing/plans, 09 invoices, 10 chat, 11 calendar, 12 file manager,
  13 api keys, 14 integrations, 15 installer page, 16 login (dark)

## 9. Live Demo (strongly recommended — items with demos sell far better)

- Any $5 VPS or a subdomain (e.g. nexus-demo.cubixsol.com)
- Install normally, then in .env: NEXUS_DEMO=true
- Cron: 0 * * * * php /path/artisan nexus:demo-reset   (hourly reset)
- Cron: * * * * * php /path/artisan schedule:run
- Create a dedicated demo super-admin (e.g. demo@example.com / demo1234)
  and put the credentials in the item description under a "Demo" heading:
  URL, email, password. Never expose your real admin.

## 10. Pre-Submission Checklist

- [ ] Fresh install on clean MySQL via /install — full walkthrough
- [ ] Fresh install with SQLite
- [ ] Wrong DB password → friendly error, form values kept
- [ ] All 41 pages open as super-admin; viewer role properly restricted
- [ ] Stripe preview mode shows notice, nothing crashes
- [ ] build-release.sh zip: contains public/build, README.txt,
      Documentation/DOCUMENTATION.md + CHANGELOG.md; no .env, no
      node_modules, no vendor, no installed.json
- [ ] Screenshots taken from the DEMO content (example.com emails visible — good)
- [ ] Live demo up with hourly reset + demo credentials in description

## 11. After Approval

- Enable item comments notifications — first 48h questions decide reviews
- Add the live demo link + demo credentials at the TOP of the description
- Plan v1.1 (already scoped): real Envato purchase-code verification via
  a small license endpoint on your own server — announce it in the
  changelog to show active development
