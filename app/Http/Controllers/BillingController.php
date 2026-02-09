<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        // Temporarily route upgrade clicks back to dashboard instead of Stripe checkout.
        return redirect()->route('dashboard');
    }

    public function portal(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        $returnUrl = $request->get('return_url', route('dashboard'));

        return $user->redirectToBillingPortal($returnUrl);
    }
}
