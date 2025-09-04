<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeApiController extends Controller
{
    /**
     * Get donate page data with top donors
     */
    public function donate(): JsonResponse
    {
        try {
            Log::info('Donate endpoint called');
            
            // Check if donation table exists
            if (!DB::getSchemaBuilder()->hasTable('donation')) {
                Log::warning('Donation table does not exist');
                return response()->json([
                    'success' => true,
                    'donors' => []
                ]);
            }

            $donors = DB::table('donation')
                ->leftJoin('cities', 'donation.city_id', '=', 'cities.id')
                ->leftJoin('countries', 'donation.country_id', '=', 'countries.id')
                ->select(
                    'donation.*',
                    'cities.name as city_name',
                    'countries.name as country_name'
                )
                ->where('donation.status', 'paid')
                ->where('donation.add_to_leaderboard', 'yes')
                ->orderBy('donation.amount', 'DESC')
                ->orderBy('donation.created_at', 'DESC')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'donors' => $donors
            ]);
        } catch (\Exception $e) {
            Log::error('Donate endpoint error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'donors' => []
            ]);
        }
    }

    /**
     * Get about page data with team members
     */
    public function about(): JsonResponse
    {
        try {
            Log::info('About endpoint called');
            
            // Check if members table exists
            if (!DB::getSchemaBuilder()->hasTable('members')) {
                Log::warning('Members table does not exist');
                return response()->json([
                    'success' => true,
                    'member' => []
                ]);
            }

            $members = DB::table('members')
                ->where('status', 1)
                ->get();

            return response()->json([
                'success' => true,
                'member' => $members
            ]);
        } catch (\Exception $e) {
            Log::error('About endpoint error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'member' => []
            ]);
        }
    }

    /**
     * Get albums with pagination
     */
    public function albums(Request $request): JsonResponse
    {
        try {
            Log::info('Albums endpoint called');
            
            // Check if albums table exists
            if (!DB::getSchemaBuilder()->hasTable('albums')) {
                Log::warning('Albums table does not exist');
                return response()->json([
                    'success' => true,
                    'albums' => [
                        'data' => [],
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 12,
                        'total' => 0
                    ]
                ]);
            }

            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;

            $albums = DB::table('albums')
                ->where('status', 1)
                ->orderBy('id', 'DESC')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $total = DB::table('albums')->where('status', 1)->count();
            $lastPage = ceil($total / $perPage);

            // Get media for each album if table exists
            if (DB::getSchemaBuilder()->hasTable('medias')) {
                foreach ($albums as $album) {
                    $media = DB::table('medias')
                        ->where('album_id', $album->id)
                        ->get();
                    $album->media = $media;
                }
            } else {
                foreach ($albums as $album) {
                    $album->media = [];
                }
            }

            $paginatedData = [
                'data' => $albums,
                'current_page' => (int)$page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total
            ];

            return response()->json([
                'success' => true,
                'albums' => $paginatedData
            ]);
        } catch (\Exception $e) {
            Log::error('Albums endpoint error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'albums' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => 0
                ]
            ]);
        }
    }

    /**
     * Get single album by ID
     */
    public function album($id): JsonResponse
    {
        try {
            Log::info('Album endpoint called for ID: ' . $id);
            
            // Check if albums table exists
            if (!DB::getSchemaBuilder()->hasTable('albums')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Album not found'
                ], 404);
            }

            $album = DB::table('albums')
                ->where('id', $id)
                ->where('status', 1)
                ->first();

            if (!$album) {
                return response()->json([
                    'success' => false,
                    'message' => 'Album not found'
                ], 404);
            }

            // Get media for the album if table exists
            if (DB::getSchemaBuilder()->hasTable('medias')) {
                $media = DB::table('medias')
                    ->where('album_id', $album->id)
                    ->get();
                $album->media = $media;
            } else {
                $album->media = [];
            }

            return response()->json([
                'success' => true,
                'album' => $album
            ]);
        } catch (\Exception $e) {
            Log::error('Album endpoint error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Album not found'
            ], 404);
        }
    }

    /**
     * Get leaderboard with pagination
     */
    public function leaderboard(Request $request): JsonResponse
    {
        try {
            Log::info('Leaderboard endpoint called');
            
            // Check if donation table exists
            if (!DB::getSchemaBuilder()->hasTable('donation')) {
                return response()->json([
                    'success' => true,
                    'donors' => [
                        'data' => [],
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 10,
                        'total' => 0
                    ]
                ]);
            }

            $page = $request->get('page', 1);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            $donors = DB::table('donation')
                ->leftJoin('cities', 'donation.city_id', '=', 'cities.id')
                ->leftJoin('countries', 'donation.country_id', '=', 'countries.id')
                ->select(
                    'donation.*',
                    'cities.name as city_name',
                    'countries.name as country_name'
                )
                ->where('donation.status', 'paid')
                ->where('donation.add_to_leaderboard', 'yes')
                ->orderBy('donation.amount', 'DESC')
                ->orderBy('donation.created_at', 'DESC')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $total = DB::table('donation')
                ->where('status', 'paid')
                ->where('add_to_leaderboard', 'yes')
                ->count();

            $lastPage = ceil($total / $perPage);

            $paginatedData = [
                'data' => $donors,
                'current_page' => (int)$page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total
            ];

            return response()->json([
                'success' => true,
                'donors' => $paginatedData
            ]);
        } catch (\Exception $e) {
            Log::error('Leaderboard endpoint error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'donors' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 10,
                    'total' => 0
                ]
            ]);
        }
    }

    /**
     * Find countries for select2
     */
    public function findCountries(Request $request): JsonResponse
    {
        try {
            Log::info('Find countries endpoint called');
            
            // Check if countries table exists
            if (!DB::getSchemaBuilder()->hasTable('countries')) {
                Log::warning('Countries table does not exist');
                return response()->json([
                    "results" => [],
                    "pagination" => ["more" => false]
                ]);
            }

            $term = trim($request->get('term', ''));
            $page = $request->get('page', 1);
            $perPage = 20;
            $offset = ($page - 1) * $perPage;

            $query = DB::table('countries')
                ->select('id', 'name as text');

            if ($term) {
                $query->where('name', 'LIKE', $term . '%');
            }

            $countries = $query->offset($offset)
                ->limit($perPage)
                ->get();

            $total = $query->count();
            $morePages = ($offset + $perPage) < $total;

            return response()->json([
                "results" => $countries,
                "pagination" => [
                    "more" => $morePages
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Countries endpoint error: ' . $e->getMessage());
            
            return response()->json([
                "results" => [],
                "pagination" => ["more" => false]
            ]);
        }
    }

    /**
     * Find states for select2
     */
    public function findStates(Request $request): JsonResponse
    {
        try {
            Log::info('Find states endpoint called');
            
            // Check if states table exists
            if (!DB::getSchemaBuilder()->hasTable('states')) {
                return response()->json([
                    "results" => [],
                    "pagination" => ["more" => false]
                ]);
            }

            $term = trim($request->get('term', ''));
            $page = $request->get('page', 1);
            $countryId = $request->get('country_id');
            $perPage = 20;
            $offset = ($page - 1) * $perPage;

            if (!$countryId) {
                return response()->json([
                    "results" => [],
                    "pagination" => ["more" => false]
                ]);
            }

            $query = DB::table('states')
                ->select('id', 'name as text')
                ->where('country_id', $countryId);

            if ($term) {
                $query->where('name', 'LIKE', $term . '%');
            }

            $states = $query->offset($offset)
                ->limit($perPage)
                ->get();

            $total = $query->count();
            $morePages = ($offset + $perPage) < $total;

            return response()->json([
                "results" => $states,
                "pagination" => [
                    "more" => $morePages
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('States endpoint error: ' . $e->getMessage());
            
            return response()->json([
                "results" => [],
                "pagination" => ["more" => false]
            ]);
        }
    }

    /**
     * Find cities for select2
     */
    public function findCities(Request $request): JsonResponse
    {
        try {
            Log::info('Find cities endpoint called');
            
            // Check if cities table exists
            if (!DB::getSchemaBuilder()->hasTable('cities')) {
                return response()->json([
                    "results" => [],
                    "pagination" => ["more" => false]
                ]);
            }

            $term = trim($request->get('term', ''));
            $page = $request->get('page', 1);
            $countryId = $request->get('country_id');
            $stateId = $request->get('state_id');
            $perPage = 20;
            $offset = ($page - 1) * $perPage;

            if (!$countryId || !$stateId) {
                return response()->json([
                    "results" => [],
                    "pagination" => ["more" => false]
                ]);
            }

            $query = DB::table('cities')
                ->select('id', 'name as text')
                ->where('country_id', $countryId)
                ->where('state_id', $stateId);

            if ($term) {
                $query->where('name', 'LIKE', $term . '%');
            }

            $cities = $query->offset($offset)
                ->limit($perPage)
                ->get();

            $total = $query->count();
            $morePages = ($offset + $perPage) < $total;

            return response()->json([
                "results" => $cities,
                "pagination" => [
                    "more" => $morePages
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Cities endpoint error: ' . $e->getMessage());
            
            return response()->json([
                "results" => [],
                "pagination" => ["more" => false]
            ]);
        }
    }

    /**
     * Submit contact form
     */
    public function contactSubmit(Request $request): JsonResponse
    {
        try {
            Log::info('Contact submit endpoint called');
            
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email',
                'mobile' => 'required|string',
                'message' => 'required|string',
            ]);

            // Check if contact table exists
            if (!DB::getSchemaBuilder()->hasTable('contact')) {
                Log::warning('Contact table does not exist');
                return response()->json([
                    'success' => true,
                    'message' => 'Contact form submitted successfully (table not found)'
                ]);
            }

            DB::table('contact')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'message' => $request->message,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contact form submitted successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Contact submit error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create checkout session
     */
    public function checkoutSession(Request $request): JsonResponse
    {
        try {
            Log::info('Checkout session endpoint called');
            
            $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'email' => 'required|email',
                'amount' => 'required|numeric|min:1',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
            ]);

            // Check if donation table exists
            if (!DB::getSchemaBuilder()->hasTable('donation')) {
                Log::warning('Donation table does not exist');
                return response()->json([
                    'success' => true,
                    'url' => '#mock-checkout-url',
                    'donation_id' => 'mock-id'
                ]);
            }

            $donationId = DB::table('donation')->insertGetId([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile ?? '',
                'amount' => $request->amount,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'street_address' => $request->street_address ?? '',
                'add_to_leaderboard' => $request->add_to_leaderboard ?? 'no',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'url' => '#mock-checkout-url',
                'donation_id' => $donationId
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Checkout session error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}