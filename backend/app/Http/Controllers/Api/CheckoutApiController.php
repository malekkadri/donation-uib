<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe; // Import Stripe
use Stripe\Checkout\Session; // Import Stripe Checkout Session

class CheckoutApiController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key and version from environment variables
        Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiVersion('2023-10-16'); // Using latest stable API version
    }

    /**
     * Create checkout session
     */
    public function createSession(Request $request)
    {
        try {
            Log::info('Checkout session request received', $request->all());
            
            // Validate the request - 'name' field for full name
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:100', // Full name
                'email' => 'required|email',
                'amount' => 'required|numeric|min:'.env('MIN_DONATION_AMOUNT', 1), // Use env for min amount
                'country' => 'required|integer',
                'state' => 'required|integer',
                'city' => 'required|integer',
                'mobile' => 'nullable|string',
                'street_address' => 'nullable|string',
                'add_to_leaderboard' => 'nullable|string|in:yes,no', // 'yes' or 'no'
            ]);
            
            if ($validator->fails()) {
                Log::warning('Checkout session validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            Log::info('Validation passed');

            // Create donation record with 'unpaid' status and no session_id yet
            $donation = Donation::create([
                'name' => $request->name, 
                'email' => $request->email,
                'mobile' => $request->mobile ?? '',
                'amount' => $request->amount,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'street_address' => $request->street_address ?? '',
                'add_to_leaderboard' => $request->add_to_leaderboard ?? 'no',
                'status' => 'unpaid', // Initial status as 'unpaid'
            ]);
            
            Log::info('Donation record created with unpaid status', ['id' => $donation->id]);

            // Create Stripe Checkout Session
            $stripeSession = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $request->email,
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => env('DONATION_CURRENCY', 'USD'), // Use env for currency
                            'product_data' => [
                                'name' => env('TRUST_NAME', 'Charity Trust') . ', ' . env('TRUST_CITY', 'City'),
                                'description' => 'Donation by ' . $request->name,
                            ],
                            'unit_amount' => (int) round($request->amount * 100), // Amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                // Use Laravel's url() helper for full URLs
                'success_url' => url('api/payment-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('api/payment-failed') . '?session_id={CHECKOUT_SESSION_ID}',
                'metadata' => [
                    'donation_id' => $donation->id, // Link Stripe session to your donation record
                    'donor_name' => $request->name,
                    'donor_email' => $request->email,
                    'locale' => 'en', // Or dynamically set based on request
                ],
                'payment_intent_data' => [
                    'description' => 'Donation from ' . $request->name,
                ],
            ]);

            // Update donation record with Stripe session ID
            $donation->session_id = $stripeSession->id;
            $donation->save();

            Log::info('Stripe session created and linked to donation', ['stripe_session_id' => $stripeSession->id, 'donation_id' => $donation->id]);

            // Return the Stripe checkout URL to the frontend
            return response()->json([
                'success' => true,
                'url' => $stripeSession->url,
                'donation_id' => $donation->id,
                'message' => 'Stripe checkout session created successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while creating the checkout session.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment success callback from Stripe
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            Log::info('Payment success callback received', ['session_id' => $sessionId]);

            if (!$sessionId) {
                Log::warning('Payment success callback: No session_id provided.');
                return response()->json(['success' => false, 'message' => 'No session ID provided.'], 400);
            }

            $stripeSession = Session::retrieve($sessionId);

            if (!$stripeSession) {
                Log::error('Payment success callback: Stripe session not found.', ['session_id' => $sessionId]);
                return response()->json(['success' => false, 'message' => 'Stripe session not found.'], 404);
            }

            $donation = Donation::where('session_id', $stripeSession->id)
                ->where('status', 'unpaid')
                ->first();

            if (!$donation) {
                Log::warning('Payment success callback: Donation not found or already paid.', ['session_id' => $sessionId]);
                return response()->json(['success' => false, 'message' => 'Donation not found or already processed.'], 404);
            }

            // Update donation status to 'paid'
            $donation->update(['status' => 'paid']);
            Log::info('Donation status updated to paid.', ['donation_id' => $donation->id, 'session_id' => $sessionId]);

            // Return JSON response for API endpoint
            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Your donation has been processed.',
                'donation_id' => $donation->id
            ], 200);

        } catch (\Exception $e) {
            Log::error('Payment Success Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment success: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment failed/cancelled callback from Stripe
     */
    public function paymentFailed(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            Log::info('Payment failed callback received', ['session_id' => $sessionId]);

            if (!$sessionId) {
                Log::warning('Payment failed callback: No session_id provided.');
                return response()->json(['success' => false, 'message' => 'No session ID provided.'], 400);
            }

            $stripeSession = Session::retrieve($sessionId);

            if (!$stripeSession) {
                Log::error('Payment failed callback: Stripe session not found.', ['session_id' => $sessionId]);
                return response()->json(['success' => false, 'message' => 'Stripe session not found.'], 404);
            }

            $donation = Donation::where('session_id', $stripeSession->id)
                ->where('status', 'unpaid')
                ->first();

            if ($donation) {
                // Update donation status to 'failed'
                $donation->update(['status' => 'failed']);
                Log::info('Donation status updated to failed.', ['donation_id' => $donation->id, 'session_id' => $sessionId]);
            } else {
                Log::warning('Payment failed callback: Donation not found or already processed.', ['session_id' => $sessionId]);
            }

            // Return JSON response for API endpoint
            return response()->json([
                'success' => true,
                'message' => 'Payment was cancelled or failed. Please try again.',
                'donation_id' => $donation ? $donation->id : null
            ], 200);

        } catch (\Exception $e) {
            Log::error('Payment Failed Error', [
                'error' => $e->getMessage(),
                'session_id' => $request->get('session_id')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment failure: ' . $e->getMessage()
            ], 500);
        }
    }
}
