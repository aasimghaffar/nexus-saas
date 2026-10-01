<?php

namespace App\Livewire\Integrations;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class IntegrationsPage extends Component
{
    /** slug => [name, description, brand image, config field label] */
    public const CATALOG = [
        'slack'     => ['Slack', 'Post workspace notifications to a Slack channel via incoming webhook.', 'slack.svg', 'Incoming Webhook URL'],
        'discord'   => ['Discord', 'Send alerts to a Discord channel through a webhook.', 'discord.svg', 'Webhook URL'],
        'zapier'    => ['Zapier', 'Trigger Zaps from Nexus events using a catch-hook URL.', 'zapier.svg', 'Catch Hook URL'],
        'stripe'    => ['Stripe', 'Subscription billing — configured via .env (see Billing).', 'stripe.svg', null],
        'github'    => ['GitHub', 'Reference commits and PRs in tasks (link-only).', 'github.svg', 'Organization URL'],
        'mailchimp' => ['Mailchimp', 'Sync new sign-ups to an audience via webhook.', 'mailchimp.svg', 'Webhook URL'],
    ];

    public ?string $configuring = null;
    public string $webhookUrl = '';

    public function configure(string $slug): void
    {
        abort_unless(array_key_exists($slug, self::CATALOG), 404);
        $this->configuring = $slug;
        $this->webhookUrl = (string) (Setting::get("integrations.{$slug}.url") ?? '');
    }

    public function save(): void
    {
        $this->validate(['webhookUrl' => ['required', 'url', 'starts_with:https://']]);

        Setting::put("integrations.{$this->configuring}.url", $this->webhookUrl);
        activity('integrations')->causedBy(auth()->user())->log("Connected {$this->configuring}");

        session()->flash('status', self::CATALOG[$this->configuring][0].' connected.');
        $this->reset('configuring', 'webhookUrl');
    }

    public function disconnect(string $slug): void
    {
        Setting::where('key', "integrations.{$slug}.url")->delete();
        activity('integrations')->causedBy(auth()->user())->log("Disconnected {$slug}");
    }

    public function sendTest(string $slug): void
    {
        $url = Setting::get("integrations.{$slug}.url");
        if (! $url) {
            return;
        }

        $payload = match ($slug) {
            'discord' => ['content' => '✅ Test notification from '.config('app.name')],
            default   => ['text' => '✅ Test notification from '.config('app.name')],
        };

        try {
            $response = Http::timeout(5)->post($url, $payload);
            session()->flash('status', $response->successful()
                ? 'Test notification delivered to '.self::CATALOG[$slug][0].'.'
                : 'The webhook responded with HTTP '.$response->status().' — check the URL.');
        } catch (\Throwable) {
            session()->flash('status', 'Could not reach the webhook — check the URL and your server connectivity.');
        }
    }

    public function render()
    {
        $connected = collect(array_keys(self::CATALOG))
            ->filter(fn ($slug) => (bool) Setting::get("integrations.{$slug}.url"))
            ->values()
            ->all();

        return view('livewire.integrations.integrations-page', [
            'catalog'   => self::CATALOG,
            'connected' => $connected,
        ])->title('Integrations');
    }
}
