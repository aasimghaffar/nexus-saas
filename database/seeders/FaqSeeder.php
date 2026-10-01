<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['Billing', 'How do I upgrade or downgrade my plan?', "Open Billing → Change Plan and pick a tier. Upgrades are prorated instantly; downgrades apply from the next billing cycle."],
            ['Billing', 'Where can I download invoices?', 'Every invoice is listed under Billing → Invoices with a printable view and a PDF download.'],
            ['Security', 'How do I enable two-factor authentication?', 'Go to Account Settings → Password & 2FA → Enable 2FA, scan the QR code with your authenticator app, and confirm the 6-digit code. Store your recovery codes safely.'],
            ['Security', 'What happens when an account is suspended?', 'Suspended members cannot sign in until an administrator re-activates them. Their data and history remain intact.'],
            ['Workspace', 'How do team invitations work?', 'Admins invite members from Users & Team. The invitee receives an email link to set their own password and lands directly in the workspace.'],
            ['Workspace', 'Can I use the REST API?', 'Yes — generate a personal access token under API Keys and call the /api/v1 endpoints with a Bearer header. Write operations require the write ability.'],
        ];

        foreach ($faqs as $i => [$category, $question, $answer]) {
            Faq::firstOrCreate(['question' => $question], [
                'category' => $category,
                'answer'   => $answer,
                'position' => $i,
            ]);
        }
    }
}
