# CodeCanyon Packaging & Submission — Nexus SaaS (Laravel)

## Category & pricing
- Category: PHP Scripts → Project Management Tools (best-fit browsing
  category; "Laravel" is not a category but a search tag).
- Suggested price: $39 regular launch ($49 after early sales),
  extended per Envato's suggested multiple.
- Tags (15): laravel, laravel 11, livewire, tailwind css, admin,
  dashboard, saas, starter kit, stripe, subscription, kanban, tickets,
  user management, roles permissions, rest api

## Build the upload

```bash
npm install          # once
bash build-release.sh 1.0.0
```

Upload `dist/nexus-saas-laravel-v1.0.0.zip` as the Main File. The zip
contains:

```
README.txt
Main File/nexus-saas/       ← full app, compiled assets, no vendor/
Documentation/DOCUMENTATION.md
Documentation/CHANGELOG.md
```

vendor/ is excluded on purpose (Envato zip size + best practice);
DOCUMENTATION.md tells buyers to run composer install. Compiled
public/build IS included so Node is never required server-side.

## Screenshots / preview
Reuse the ThemeForest screenshot set where pages overlap, PLUS shots
of what the HTML version cannot do: the installer page, Users & Team
with the invite modal, ticket agent view, billing with live Stripe,
API keys with a generated token, activity log. First preview image
590x300 named 01_preview.jpg, rest 02_...jpg sequentially.

## Reviewer notes (paste into the upload form)

Demo super-admin: create via the installer, or tick "Install demo
content" for sample team/projects/tickets (all demo users' password:
"password"). The purchase-code field accepts any UUID in local/dev
mode — verification hardening is documented for buyers. The app is an
original work: Laravel 11, Livewire 3, Fortify, Cashier, Spatie
permissions; frontend is our own Tailwind design (also sold as an HTML
template by us on ThemeForest — same author, shared design system).
No CDN dependencies; all assets compiled and bundled.

## Pre-submission checklist
- [ ] Fresh install test on a clean MySQL database via /install
- [ ] Fresh install test with SQLite
- [ ] Wrong-DB-password path shows friendly error
- [ ] Demo content on + off
- [ ] npm run build committed → public/build present in zip
- [ ] APP_DEBUG=false written by installer (it is)
- [ ] All 41 sidebar pages open without error as super-admin
- [ ] Viewer role: no Users/Integrations/Billing menu, direct URLs 403
- [ ] Stripe preview mode (no keys) shows notice, nothing crashes
- [ ] DOCUMENTATION.md + CHANGELOG.md in Documentation folder
