<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Add a simple test route to check if Laravel is working
Route::get('/test-laravel', function () {
    return response()->json([
        'message' => 'Laravel is working!',
        'timestamp' => now(),
        'routes_loaded' => true
    ]);
});
