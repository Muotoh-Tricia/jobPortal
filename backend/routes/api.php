<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'pong'
    ]);
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    return response()->json([
        'token' => $user->createToken('API Token')->plainTextToken
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/test', function () {
        return response()->json([
            'message' => 'Authenticated route works!'
        ]);
    });
});
