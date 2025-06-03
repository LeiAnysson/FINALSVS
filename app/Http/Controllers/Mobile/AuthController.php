<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        // Login successful
        $user = $request->user();

        // Optional: delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        Log::info('User trying to logout:', ['user' => $user]);
        $token = $request->user()?->currentAccessToken();
        

        if ($token instanceof PersonalAccessToken) {
            $token->delete();

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'No active token found or invalid token'], 401);
    }
}
