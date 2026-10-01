# Nexus SaaS (Laravel) — Local Setup

This package is a COMPLETE Laravel project. Do NOT create a separate
Laravel project or merge folders — just unzip and install dependencies.

Requirements: PHP 8.2+ and Composer 2. (Node.js is optional — compiled
CSS/JS ships in public/build.)

## 1. Unzip

Unzip so you have a `nexus-saas` folder, e.g.
`/Applications/XAMPP/xamppfiles/htdocs/nexus-saas`.

## 2. Install PHP dependencies

```bash
cd nexus-saas
composer install
```

(composer install copies .env from .env.example automatically. Even if
that's skipped, the app self-creates .env on first visit and the
installer generates the APP_KEY — no manual step needed.)

## 3. Run

```bash
php artisan serve
```

Open http://localhost:8000 — you are redirected to the one-page
installer:

- Database driver: SQLite (quickest) or MySQL (create an empty DB first)
- Create your super-admin account
- Tick "Install demo content" to get sample team/projects/tickets

Click Install → sign in. Done.

## Demo accounts (password: `password`)

- sarah@example.com — admin (ticket agent view)
- liam@example.com — viewer (customer view, limited menus)

## Using XAMPP's Apache instead of `php artisan serve`

Point a VirtualHost's DocumentRoot at `nexus-saas/public` (never the
project root). With the default http://localhost/nexus-saas/public URL,
Laravel works too — set APP_URL accordingly in .env.

## Customizing styles (optional)

```bash
npm install
npm run dev     # live reload during development
npm run build   # compile for production
```

## Test checklist

1. Dashboard shows live numbers (members, projects, tickets).
2. Users & Team → invite someone; the email lands in
   `storage/logs/laravel.log` (MAIL_MAILER=log).
3. Profile → enable 2FA with any authenticator app; log out and back in.
4. Projects → create one; Kanban → drag its tasks to Done; refresh —
   positions persist; project progress bar updates.
5. Tickets as admin vs liam@example.com (agent vs customer views).
6. Chat/Mail between two browsers; Calendar events; Files upload/download.
7. API Keys → generate token →
   `curl -H "Authorization: Bearer TOKEN" http://localhost:8000/api/v1/projects`
8. /error-preview/404, /error-preview/500, /error-preview/503.

## Recovering from a failed install attempt

If an earlier attempt crashed midway, reset to a clean slate:

```bash
rm -f storage/installed.json database/database.sqlite .env
php artisan optimize:clear
php artisan serve
```

(.env self-recreates from .env.example on the next visit.) Then run the
installer again — or use the CLI installer, which never depends on the
web server:

```bash
php artisan nexus:install --demo
```

## Troubleshooting

- Most cache-related weirdness: `php artisan optimize:clear`
- "Vite manifest not found": public/build missing — re-unzip or run
  `npm install && npm run build`.
- To re-run the installer: delete `storage/installed.json`.
