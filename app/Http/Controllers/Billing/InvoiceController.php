<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $invoices = collect();

        if (config('cashier.secret') && $user->hasStripeId()) {
            $invoices = $user->invoicesIncludingPending();
        }

        return view('billing.invoices', ['invoices' => $invoices, 'user' => $user]);
    }

    public function show(Request $request, string $invoiceId)
    {
        $user = $request->user();
        abort_unless(config('cashier.secret') && $user->hasStripeId(), 404);

        $invoice = $user->findInvoiceOrFail($invoiceId);

        return view('billing.invoice-detail', ['invoice' => $invoice, 'user' => $user]);
    }

    public function download(Request $request, string $invoiceId)
    {
        $user = $request->user();
        abort_unless(config('cashier.secret') && $user->hasStripeId(), 404);

        return $user->downloadInvoice($invoiceId, [
            'vendor'  => config('app.name'),
            'product' => 'Subscription',
        ]);
    }
}
