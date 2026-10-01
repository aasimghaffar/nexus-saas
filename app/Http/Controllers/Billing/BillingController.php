<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Billing overview: current plan, resource meters, payment methods, history.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $plans = config('nexus.plans');

        $currentPlanKey = $this->currentPlanKey($user);
        $currentPlan = $currentPlanKey ? $plans[$currentPlanKey] : null;
        $subscription = $user->subscription('default');

        $paymentMethods = collect();
        $upcoming = null;

        if ($this->stripeConfigured() && $user->hasStripeId()) {
            $paymentMethods = $user->paymentMethods();
            try {
                $upcoming = $subscription?->upcomingInvoice();
            } catch (\Throwable) {
                $upcoming = null;
            }
        }

        return view('billing.show', [
            'user'            => $user,
            'plans'           => $plans,
            'currentPlanKey'  => $currentPlanKey,
            'currentPlan'     => $currentPlan,
            'subscription'    => $subscription,
            'paymentMethods'  => $paymentMethods,
            'upcoming'        => $upcoming,
            'seatsUsed'       => User::count(),
            'seatLimit'       => $currentPlan['seats'] ?? (int) config('nexus.seats'),
            'stripeReady'     => $this->stripeConfigured(),
        ]);
    }

    /**
     * Start a Stripe Checkout session for the chosen plan (new subscription),
     * or swap the price when already subscribed.
     */
    public function subscribe(Request $request)
    {
        abort_unless($this->stripeConfigured(), 404);
        $request->validate(['plan' => ['required', 'in:'.implode(',', array_keys(config('nexus.plans')))]]);

        $user = $request->user();
        $plan = config('nexus.plans')[$request->plan];

        abort_if(empty($plan['stripe_price']), 400, 'Stripe price ID missing for this plan — set it in .env.');

        if ($user->subscribed('default')) {
            $user->subscription('default')->swapAndInvoice($plan['stripe_price']);
            activity('billing')->causedBy($user)->log("Switched plan to {$plan['name']}");

            return redirect()->route('billing')->with('status', "You're now on {$plan['name']}.");
        }

        return $user->newSubscription('default', $plan['stripe_price'])
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('billing').'?checkout=success',
                'cancel_url'  => route('billing').'?checkout=cancelled',
            ]);
    }

    public function cancel(Request $request)
    {
        $request->user()->subscription('default')?->cancel();
        activity('billing')->causedBy($request->user())->log('Cancelled subscription (grace period)');

        return back()->with('status', 'Subscription cancelled — access continues until the end of the billing period.');
    }

    public function resume(Request $request)
    {
        $sub = $request->user()->subscription('default');

        if ($sub && $sub->onGracePeriod()) {
            $sub->resume();
            activity('billing')->causedBy($request->user())->log('Resumed subscription');
        }

        return back()->with('status', 'Subscription resumed.');
    }

    /**
     * Stripe Billing Portal (manage cards, addresses, cancellations).
     */
    public function portal(Request $request)
    {
        abort_unless($this->stripeConfigured(), 404);

        return $request->user()->redirectToBillingPortal(route('billing'));
    }

    private function currentPlanKey(User $user): ?string
    {
        if (! $this->stripeConfigured() || ! $user->subscribed('default')) {
            return null;
        }

        $stripePrice = $user->subscription('default')->stripe_price;

        foreach (config('nexus.plans') as $key => $plan) {
            if ($plan['stripe_price'] === $stripePrice) {
                return $key;
            }
        }

        return null;
    }

    private function stripeConfigured(): bool
    {
        return (bool) config('cashier.secret');
    }
}
