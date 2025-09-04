<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiVersion('2023-10-16'); // Using latest stable API version
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|email',
            'mobile' => 'nullable|string|regex:/^[6-9][0-9]{9}$/',
            'amount' => 'required|numeric|min:'.env('MIN_DONATION_AMOUNT', 1),
            'country' => 'required|numeric',
            'state' => 'required|numeric',
            'city' => 'required|numeric',
            'street_address' => 'nullable|string',
            'add_to_leaderboard' => 'nullable|string|in:yes,no',
        ]);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $request->email,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => env('DONATION_CURRENCY', 'INR'),
                            'product_data' => [
                                'name' => env('TRUST_NAME', 'Charity Trust') . ', ' . env('TRUST_CITY', 'City'),
                                'description' => 'Donation by ' . $request->first_name . ' ' . $request->last_name,
                            ],
                            'unit_amount' => (int) round($request->amount * 100),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => url('payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('failed-payment') . '?session_id={CHECKOUT_SESSION_ID}',
                'metadata' => [
                    'donor_name' => $request->first_name . ' ' . $request->last_name,
                    'donor_email' => $request->email,
                    'locale' => 'in',
                ],
                'payment_intent_data' => [
                    'description' => 'Donation from ' . $request->first_name . ' ' . $request->last_name,
                ],
            ]);

            Donation::create([
                'status' => 'unpaid',
                'amount' => $request->amount,
                'mobile' => $request->mobile,
                'street_address' => $request->street_address,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'email' => $request->email,
                'name' => $request->first_name . ' ' . $request->last_name,
                'session_id' => $session->id,
                'add_to_leaderboard' => $request->add_to_leaderboard ?: 'no',
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()
                ->back()
                ->with(['error' => 'Unable to process checkout: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function paymentSuccess(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            $session = Session::retrieve($sessionId);

            if (!$session) {
                throw new \Exception("No checkout session found.");
            }

            $donation = Donation::where('session_id', $session->id)
                ->where('status', 'unpaid')
                ->firstOrFail();

            $donation->update(['status' => 'paid']);

            return redirect('donate')
                ->with(['success' => 'Thanks for your donation. Your contribution has been successfully processed.']);

        } catch (\Exception $e) {
            Log::error('Payment Success Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);

            return redirect('donate')
                ->with(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }

    public function handleFailedPayment(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            $session = Session::retrieve($sessionId);

            $donation = Donation::where('session_id', $session->id)
                ->where('status', 'unpaid')
                ->first();

            if ($donation) {
                $donation->update(['status' => 'failed']);
            }

            return redirect('donate')
                ->with(['error' => 'Payment was cancelled or failed. Please try again.']);

        } catch (\Exception $e) {
            Log::error('Payment Failed Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);

            return redirect('donate')
                ->with(['error' => 'Error processing payment status: ' . $e->getMessage()]);
        }
    }
}