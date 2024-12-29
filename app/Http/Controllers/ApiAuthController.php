<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        // Створення користувача без валідації
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Перевірка, чи успішно створений користувач
        if (! $user) {
            return response()->json([
                'message' => 'User registration failed',
            ], 500);
        }

        return UserResource::make($user)->resolve();
    }


    public function login(Request $request)
    {
//        $request->validate([
//            'email' => 'required|string|email|max:255',
//            'password' => 'required|string|min:8',
//        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Видалення токену з сесії користувача
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        // Повертаємо відповідь, що користувач вийшов з системи
        return response()->json(['message' => 'Successfully logged out']);
    }
}
