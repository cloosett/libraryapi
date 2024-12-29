<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $unhashedtoken = $request->bearerToken();
        $token = explode('|', $unhashedtoken)[1];

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $personalAccessToken = PersonalAccessToken::where('token', hash('sha256', $token))->first();

        if (!$personalAccessToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $personalAccessToken->tokenable;

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return UserResource::make($user)->resolve();
    }
}
