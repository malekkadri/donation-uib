<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlbumApiController;
use App\Http\Controllers\Api\CheckoutApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\MembersApiController;
use App\Http\Controllers\Api\QueriesApiController;
use App\Http\Controllers\HomeApiController; // Note: This one is directly under App\Http\Controllers, not Api

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Basic test route
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'Laravel API is working!',
        'timestamp' => now()->toISOString()
    ]);
});

// Contact form submission
Route::post('/contact/submit', [QueriesApiController::class, 'store']);

// Checkout session and payment callbacks
Route::post('/checkout/session', [CheckoutApiController::class, 'createSession']);
Route::get('/payment-success', [CheckoutApiController::class, 'paymentSuccess']);
Route::get('/payment-failed', [CheckoutApiController::class, 'paymentFailed']);

// Home page data routes
Route::prefix('home')->group(function () {
    Route::get('/donate', [HomeApiController::class, 'donate']);
    Route::get('/about', [HomeApiController::class, 'about']);
    Route::get('/albums', [HomeApiController::class, 'albums']);
    Route::get('/album/{id}', [HomeApiController::class, 'album']);
    Route::get('/leaderboard', [HomeApiController::class, 'leaderboard']);
    
    // Search routes for countries, states, cities
    Route::get('/find-countries', [HomeApiController::class, 'findCountries']);
    Route::get('/find-states', [HomeApiController::class, 'findStates']);
    Route::get('/find-cities', [HomeApiController::class, 'findCities']);
});

// API routes for managing resources (requires authentication for store, update, destroy)
Route::middleware('auth:sanctum')->group(function () {
    // Queries API
    Route::get('/queries', [QueriesApiController::class, 'index']);
    Route::delete('/queries/{id}', [QueriesApiController::class, 'destroy']);

    // Albums API
    Route::get('/albums-api', [AlbumApiController::class, 'index']); // Renamed to avoid conflict with /home/albums
    Route::get('/albums-api/{id}', [AlbumApiController::class, 'show']);
    Route::post('/albums-api', [AlbumApiController::class, 'store']);
    Route::post('/albums-api/{id}', [AlbumApiController::class, 'update']); // Using POST for update with form-data
    Route::delete('/albums-api/{id}', [AlbumApiController::class, 'destroy']);
    Route::delete('/albums-api/media/{id}', [AlbumApiController::class, 'deleteMedia']);
    Route::post('/albums-api/upload-ckeditor-media', [AlbumApiController::class, 'uploadCKEditorMedia']);

    // Members API
    Route::get('/members-api', [MembersApiController::class, 'index']); // Renamed to avoid conflict
    Route::post('/members-api', [MembersApiController::class, 'store']);
    Route::post('/members-api/{id}', [MembersApiController::class, 'update']); // Using POST for update with form-data
    Route::delete('/members-api/{id}', [MembersApiController::class, 'destroy']);

    // Donation API (for admin purposes, if needed)
    Route::get('/donations-api/top-donors', [DonationApiController::class, 'getTopDonors']);
    Route::get('/donations-api/team-members', [DonationApiController::class, 'getTeamMembers']);
    Route::get('/donations-api/albums', [DonationApiController::class, 'getAlbums']);
    Route::get('/donations-api/album-details/{id}', [DonationApiController::class, 'getAlbumDetails']);
    Route::get('/donations-api/leaderboard', [DonationApiController::class, 'getLeaderboard']);
    Route::get('/donations-api/find-countries', [DonationApiController::class, 'findCountries']);
    Route::get('/donations-api/find-states', [DonationApiController::class, 'findStates']);
    Route::get('/donations-api/find-cities', [DonationApiController::class, 'findCities']);
    Route::post('/donations-api/process-checkout', [DonationApiController::class, 'processCheckout']);
});
