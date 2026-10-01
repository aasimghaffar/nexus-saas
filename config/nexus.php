<?php

return [

    'version' => '1.0.0',

    // Public demo server switch — enables the hourly nexus:demo-reset command.
    'demo_mode' => env('NEXUS_DEMO', false),
    // Fallback seat limit when the workspace has no active subscription.
    // With an active plan, seats come from the plan definition below.
    'seats' => env('NEXUS_SEATS', 25),

    /*
    | Subscription plans. Create matching Products/Prices in your Stripe
    | dashboard (monthly recurring) and put the price IDs in .env.
    */
    'plans' => [
        'starter' => [
            'name'     => 'Starter Core',
            'price'    => 19,
            'interval' => 'month',
            'stripe_price' => env('STRIPE_PRICE_STARTER'),
            'seats'    => 5,
            'features' => ['5 team seats', '10 active projects', 'Community support', 'Basic analytics'],
        ],
        'team' => [
            'name'     => 'Team Growth',
            'price'    => 49,
            'interval' => 'month',
            'stripe_price' => env('STRIPE_PRICE_TEAM'),
            'seats'    => 25,
            'featured' => true,
            'features' => ['25 team seats', 'Unlimited projects', 'Priority email support', 'Advanced analytics', 'API access'],
        ],
        'enterprise' => [
            'name'     => 'Enterprise Pro',
            'price'    => 99,
            'interval' => 'month',
            'stripe_price' => env('STRIPE_PRICE_ENTERPRISE'),
            'seats'    => 100,
            'features' => ['100 team seats', 'Unlimited everything', 'Dedicated support & SLA', 'SSO & audit logs', 'Custom integrations'],
        ],
    ],

    'roles' => [
        'super-admin' => ['label' => 'Super Admin', 'badge' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/40'],
        'admin'       => ['label' => 'Admin',       'badge' => 'bg-brand-50 text-brand-700 dark:bg-brand-950/60 dark:text-brand-400 border border-brand-200 dark:border-brand-800/40'],
        'editor'      => ['label' => 'Editor',      'badge' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-400 border border-sky-200 dark:border-sky-800/40'],
        'viewer'      => ['label' => 'Viewer',      'badge' => 'bg-slate-100 text-slate-600 dark:bg-dark-800 dark:text-dark-300 border border-slate-200 dark:border-dark-700'],
    ],
];
