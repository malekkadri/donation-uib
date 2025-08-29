<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Donation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CheckoutApiController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiVersion('2023-10-16'); // Using latest stable API version
    }

    public function createSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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
                            ],
                            'unit_amount' => (int) round($request->amount * 100),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => url('api/payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('api/payment-failed') . '?session_id={CHECKOUT_SESSION_ID}',
                'metadata' => [
                    'donor_name' => $request->first_name . ' ' . $request->last_name,
                    'donor_email' => $request->email,
                    'locale' => 'in',
                ],
                'payment_intent_data' => [
                    'description' => 'Donation from ' . $request->first_name . ' ' . $request->last_name,
                ],
            ]);

            $donation = Donation::create([
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

            return response()->json([
                'session_id' => $session->id,
                'url' => $session->url,
                'amount' => $request->amount,
                'currency' => env('DONATION_CURRENCY', 'INR'),
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'Unable to process checkout',
                'message' => $e->getMessage()
            ], 500);
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

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'donation' => $donation,
                'receipt_url' => $session->payment_intent->receipt_url ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Success Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);

            return response()->json([
                'error' => 'Payment verification failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function paymentFailed(Request $request)
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

            return response()->json([
                'success' => false,
                'message' => 'Payment failed or was cancelled',
                'session_id' => $sessionId,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Failed Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);

            return response()->json([
                'error' => 'Payment verification failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}