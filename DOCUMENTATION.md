# Nexus SaaS — Laravel Admin & Starter Kit

Welcome! This guide walks you from download to a
running product, then covers configuration and customization. No Laravel
experience is needed for installation; customization sections assume
basic PHP familiarity.

---

## 1. Requirements

- PHP 8.2 or newer with extensions: pdo, mbstring, openssl, tokenizer,
  ctype, json, curl, fileinfo, gd (all standard on cPanel/Plesk hosts)
- MySQL 5.7+ / MariaDB 10.3+ (or SQLite for small installs)
- Composer 2 (ask your host — nearly all shared hosts have it). This package is a complete Laravel project — no separate Laravel installation is needed.
- HTTPS strongly recommended in production

Node.js is NOT required on your server — compiled CSS/JS ships in
`public/build`. You only need Node if you customize styles (section 7).

## 2. Installation (5 minutes)

1. Upload the `nexus-saas` folder from `Main File/` to your server.
2. Point your domain's web root at `nexus-saas/public`.
   - cPanel: set the document root of the (sub)domain to `.../nexus-saas/public`.
   - Plain VPS (nginx): root `/var/www/nexus-saas/public;` with the
     standard Laravel `try_files` rule.
3. In a terminal (or your host's "Terminal" feature), inside `nexus-saas`:
   ```bash
   composer install --no-dev
   ```
4. Visit your domain. You'll be redirected to the one-page installer:
   - Server requirements are checked live — everything must be green.
   - Enter your app name and URL.
   - Enter database credentials (create an empty database first in
     cPanel → MySQL Databases). The installer tests the connection
     before writing anything.
   - Create your super-admin account.
   - Optionally tick "Install demo content" to explore with sample data.
5. Click Install — you'll land on the sign-in page. Done.

The installer locks itself after success. To deliberately re-install,
delete `storage/installed.json` and refresh.

## 3. First steps after installing

- **Sign in** with the admin account you created.
- **Users & Team** → invite your teammates and set roles.
- **Roles**: super-admin (everything), admin (manage users/tickets/
  projects/settings), editor (work with projects & tickets), viewer
  (read-only style access).
- **Settings → Integrations**: connect Slack/Discord/Zapier/Mailchimp
  webhooks and use Send Test to verify.
- **FAQ**: replace the seeded questions with your own (drafts supported).

## 4. Email sending

Set these in `.env` (installer leaves mail on the safe `log` driver):

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

Then `php artisan config:clear`. Email is used for verification,
password resets, and team invitations.

## 5. Stripe billing (optional)

Billing pages work in a safe preview mode until Stripe is configured.

1. Create three recurring Prices in your Stripe dashboard.
2. Add to `.env`:
   ```
   STRIPE_KEY=pk_live_...
   STRIPE_SECRET=sk_live_...
   STRIPE_WEBHOOK_SECRET=whsec_...
   STRIPE_PRICE_STARTER=price_...
   STRIPE_PRICE_TEAM=price_...
   STRIPE_PRICE_ENTERPRISE=price_...
   ```
3. Add a webhook endpoint in Stripe pointing to
   `https://yourdomain.com/stripe/webhook` (events: the Cashier default
   set — customer.subscription.*, invoice.*).
4. Plan names, prices shown, and seat limits are editable in
   `config/nexus.php`.

Seat limits: each plan's `seats` value caps how many members can be
invited while that plan is active.

## 6. Scheduled tasks & queues (recommended)

Add one cron entry (cPanel → Cron Jobs):

```
* * * * * php /path/to/nexus-saas/artisan schedule:run >> /dev/null 2>&1
```

The default queue driver is synchronous, so no worker is required. For
faster invitations/emails on busy sites set `QUEUE_CONNECTION=database`
and run a worker (`php artisan queue:work`).

## 7. Customizing the look

- Brand color: edit the `brand` palette in `tailwind.config.js`.
- Logo: replace `public/assets/images/logo-light.svg` and
  `logo-dark.svg` (same names, your artwork).
- Then rebuild CSS locally: `npm install && npm run build`, and upload
  the regenerated `public/build` folder.
- All screens live in `resources/views` — standard Blade + Livewire.

## 8. The REST API

Generate a token under **API Keys** (read and/or write abilities), then:

```
curl https://yourdomain.com/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN" -H "Accept: application/json"
```

Endpoints: `GET /api/v1/projects`, `GET /api/v1/tasks`,
`POST /api/v1/tasks` (write ability), `GET /api/v1/tickets`.

## 9. Running a public demo (optional)

On a demo-only server set `NEXUS_DEMO=true` in `.env` and schedule:

```
0 * * * * php /path/to/artisan nexus:demo-reset
```

This restores pristine demo data hourly. The command refuses to run
when `NEXUS_DEMO` is not true, so it can never wipe a real install.

## 10. Updating

1. Back up your `.env`, `storage/`, and database.
2. Upload the new release over the old files (never overwrite `.env`
   or `storage/`).
3. Run `composer install --no-dev && php artisan migrate --force
   && php artisan optimize:clear`.
The changelog lists anything release-specific.

## 11. Troubleshooting

- **500 after moving servers** → `php artisan optimize:clear`, check
  `storage/` is writable.
- **Styles look broken** → `public/build` missing; re-upload it or run
  `npm run build`.
- **"CSRF token mismatch"** → your `APP_URL` doesn't match the domain,
  or the site runs behind a proxy — set the URL correctly in `.env`.
- **Emails not arriving** → check section 4 credentials; while
  `MAIL_MAILER=log`, mail is written to `storage/logs/laravel.log`.
- **2FA locked out** → sign in with a recovery code (shown when 2FA was
  enabled), then regenerate codes in Account Settings.

## 12. Support

Support covers bugs and installation questions per the Envato support
policy. Please include your PHP version, host type, and any message
from `storage/logs/laravel.log`.
