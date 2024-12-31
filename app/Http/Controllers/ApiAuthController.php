<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (! $user) {
            return response()->json([
                'message' => 'User registration failed',
            ], 500);
        }

        return UserResource::make($user)->resolve();
    }


    public function login(Request $request)
    {
        // Валідація запиту
        // $request->validate([
        //     'email' => 'required|string|email|max:255',
        //     'password' => 'required|string|min:8',
        // ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('Personal Access Token', ['*'])->plainTextToken;

            $refreshToken = bin2hex(random_bytes(64));

            $user->refreshTokens()->create(['token' => $refreshToken]);

            $expirationTime = Carbon::now()->addMinutes(5);

            $user->tokens()->create([
                'name' => 'Personal Access Token',
                'token' => hash('sha256', $token),
                'expires_at' => $expirationTime,
            ]);

            return response()->json([
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'expires_at' => $expirationTime,
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $user = User::whereHas('refreshTokens', function($query) use ($refreshToken) {
            $query->where('token', $refreshToken);
        })->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        $user->refreshTokens()->where('token', $refreshToken)->delete();

        $newAccessToken = $user->createToken('Personal Access Token', ['*'])
            ->expiresAt(Carbon::now()->addMinutes(5))
            ->plainTextToken;

        $newRefreshToken = bin2hex(random_bytes(64));

        $user->refreshTokens()->create(['token' => $newRefreshToken]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }
}
