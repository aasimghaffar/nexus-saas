# Changelog — Nexus SaaS (Laravel)

## v1.0.0 — Initial release
- 41 fully functional pages on Laravel 11 + Livewire 3 + Tailwind CSS 3
- Fortify authentication: login, register, email verification, password
  reset, 2FA (TOTP + recovery codes), lock screen
- Roles & permissions (super-admin / admin / editor / viewer), user
  management with invitations, suspension, seat limits
- Stripe billing via Cashier: checkout, plan swap with proration, cancel/
  resume, billing portal, invoice list + printable view + PDF download
- Projects, drag-and-drop Kanban board with persistence, support tickets
  with agent workflow
- Team chat (polling), internal mailbox, calendar, private file manager
  with quota
- Sanctum API keys + documented REST API (/api/v1)
- Integrations hub (Slack, Discord, Zapier, Mailchimp webhooks with test
  delivery), FAQ manager, activity log
- Three live-data dashboards, themed 404/500/maintenance pages, 10-page
  UI kit
- One-page web installer with requirements check and purchase-code entry
