<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Iluminate\Support\Facades\Auth;
use Iluminate\Support\Facades\Hash;
use Iluminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'required' => 'required|string|min:8|confirmed',
        ]);

        $user = user::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if(!Auth::attempt($credentials)){
                throw ValidationException::withMessages([
                    'email' => ["The provided credentials are incorrect."],
                ]);
            }

            $user = $request->user();

            // Revoke old tokens (single-device login)
            $user->tokens()->delete();

            $token = $user->createToken('mobile')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();
            return response()->json([null, 204]);
        }
    }
}
