<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return 'Laravel is working!';
});

Route::get('/test', function (Request $request) {
    try {
        // Check database connection
        try {
            DB::connection()->getPdo();
            $db_status = 'Connected successfully';
        } catch (\Exception $e) {
            $db_status = 'Database connection failed: ' . $e->getMessage();
        }

        // Attempt to start a session
        $session_status = '';
        try {
            $request->session()->start();
            $session_status = 'Session started successfully. Session ID: ' . $request->session()->getId();
        } catch (\Exception $e) {
            $session_status = 'Session start failed: ' . $e->getMessage();
        }

        // Return detailed diagnostic information
        return response()->json([
            'database_status' => $db_status,
            'session_status' => $session_status,
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'request_headers' => $request->headers->all()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debugging route
Route::get('/debug', function () {
    return response()->json([
        'message' => 'Web route is working',
        'routes' => [
            'web_routes' => true
        ]
    ]);
});
