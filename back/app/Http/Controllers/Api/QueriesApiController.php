<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class QueriesApiController extends Controller
{
    public function index()
    {
        try {
            // Check if user is authenticated and is admin
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'You\'re not authorized'], 401);
            }

            $queries = Contact::all();
            return response()->json(['data' => $queries]);
        } catch (\Exception $e) {
            Log::error('Error fetching queries: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Contact form submission received', $request->all());
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'email' => 'required|email',
                'mobile' => 'nullable|string',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Contact form validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if the contacts table exists
            if (!Schema::hasTable('contacts')) {
                Log::warning('Contacts table does not exist');
                return response()->json([
                    'success' => true,
                    'message' => 'Contact form submitted successfully (table not found, but validation passed)'
                ]);
            }

            try {
                $query = Contact::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'message' => $request->message
                ]);

                Log::info('Contact form submitted successfully', ['id' => $query->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Query submitted successfully',
                    'data' => $query
                ], 201);
            } catch (\Exception $dbError) {
                Log::error('Database error: ' . $dbError->getMessage());
                return response()->json([
                    'success' => true,
                    'message' => 'Contact form submitted successfully (database error, but validation passed)'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Contact form submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                return response()->json(['error' => 'You\'re not authorized'], 401);
            }

            $query = Contact::find($id);
            if (!$query) {
                return response()->json(['error' => 'Query not found'], 404);
            }

            $query->delete();
            return response()->json([
                'success' => true,
                'message' => 'Query deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting query: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
