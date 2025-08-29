<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Members;
use App\Models\Albums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DonationApiController extends Controller
{
    // Existing methods...

    /**
     * Get top donors for home page
     */
    public function getTopDonors()
    {
        try {
            Log::info('Getting top donors');
            
            $donors = Donation::where('add_to_leaderboard', 'yes')
                ->where('status', 'paid')
                ->orderBy('amount', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'donors' => $donors
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting top donors: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'donors' => []
            ]);
        }
    }

    /**
     * Get team members for about page
     */
    public function getTeamMembers()
    {
        try {
            Log::info('Getting team members');
            
            $members = Members::where('status', 1)->get();

            return response()->json([
                'success' => true,
                'member' => $members
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting team members: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'member' => []
            ]);
        }
    }

    /**
     * Get albums with pagination
     */
    public function getAlbums(Request $request)
    {
        try {
            Log::info('Getting albums');
            
            $page = $request->get('page', 1);
            $perPage = 12;
            
            $albums = Albums::where('status', 1)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
                
            // Add media to each album
            foreach ($albums as $album) {
                $album->media = $album->media ?? [];
            }

            return response()->json([
                'success' => true,
                'albums' => $albums
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting albums: ' . $e->getMessage());
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
     * Get album details
     */
    public function getAlbumDetails($id)
    {
        try {
            Log::info('Getting album details for ID: ' . $id);
            
            $album = Albums::with('media')->findOrFail($id);

            return response()->json([
                'success' => true,
                'album' => $album
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting album details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Album not found'
            ], 404);
        }
    }

    /**
     * Get leaderboard with pagination
     */
    public function getLeaderboard(Request $request)
    {
        try {
            Log::info('Getting leaderboard');
            
            $page = $request->get('page', 1);
            $perPage = 10;
            
            $donors = Donation::where('add_to_leaderboard', 'yes')
                ->where('status', 'paid')
                ->orderBy('amount', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'donors' => $donors
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting leaderboard: ' . $e->getMessage());
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
    public function findCountries(Request $request)
    {
        try {
            Log::info('Finding countries');
            
            $term = $request->get('term', '');
            $page = $request->get('page', 1);
            $perPage = 20;
            
            $query = Country::select('id', 'name as text');
            
            if ($term) {
                $query->where('name', 'LIKE', "%{$term}%");
            }
            
            $countries = $query->paginate($perPage);
            
            return response()->json([
                'results' => $countries->items(),
                'pagination' => [
                    'more' => $countries->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error finding countries: ' . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
    }

    /**
     * Find states for select2
     */
    public function findStates(Request $request)
    {
        try {
            Log::info('Finding states');
            
            $term = $request->get('term', '');
            $page = $request->get('page', 1);
            $countryId = $request->get('country_id');
            $perPage = 20;
            
            if (!$countryId) {
                return response()->json([
                    'results' => [],
                    'pagination' => ['more' => false]
                ]);
            }
            
            $query = State::select('id', 'name as text')
                ->where('country_id', $countryId);
                
            if ($term) {
                $query->where('name', 'LIKE', "%{$term}%");
            }
            
            $states = $query->paginate($perPage);
            
            return response()->json([
                'results' => $states->items(),
                'pagination' => [
                    'more' => $states->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error finding states: ' . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
    }

    /**
     * Find cities for select2
     */
    public function findCities(Request $request)
    {
        try {
            Log::info('Finding cities');
            
            $term = $request->get('term', '');
            $page = $request->get('page', 1);
            $countryId = $request->get('country_id');
            $stateId = $request->get('state_id');
            $perPage = 20;
            
            if (!$countryId || !$stateId) {
                return response()->json([
                    'results' => [],
                    'pagination' => ['more' => false]
                ]);
            }
            
            $query = City::select('id', 'name as text')
                ->where('country_id', $countryId)
                ->where('state_id', $stateId);
                
            if ($term) {
                $query->where('name', 'LIKE', "%{$term}%");
            }
            
            $cities = $query->paginate($perPage);
            
            return response()->json([
                'results' => $cities->items(),
                'pagination' => [
                    'more' => $cities->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error finding cities: ' . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
        }
    }

    /**
     * Process checkout session
     */
    public function processCheckout(Request $request)
    {
        try {
            Log::info('Processing checkout session', $request->all());
            
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'email' => 'required|email',
                'amount' => 'required|numeric|min:1',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $donation = Donation::create([
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
                'status' => 'pending'
            ]);

            // For demo purposes, we'll just return a mock checkout URL
            return response()->json([
                'success' => true,
                'url' => '#mock-checkout-url',
                'donation_id' => $donation->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing checkout: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
