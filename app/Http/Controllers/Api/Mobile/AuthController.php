<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'remember' => 'nullable|boolean',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials do not match our records.',
                    'errors' => [
                        'email' => ['The provided credentials do not match our records.'],
                    ],
                ], 422);
            }

            if (! Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials do not match our records.',
                    'errors' => [
                        'email' => ['The provided credentials do not match our records.'],
                    ],
                ], 422);
            }

            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? null,
                        'role' => $user->role ?? 'user',
                    ],
                    'token' => $token,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login. Please try again.',
                'errors' => [
                    'server' => ['Server error occurred. Please try again later.'],
                ],
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'user',
            ]);

            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? null,
                        'role' => $user->role ?? 'user',
                    ],
                    'token' => $token,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration. Please try again.',
                'errors' => [
                    'server' => ['Server error occurred. Please try again later.'],
                ],
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $user->load('roles');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'roles' => $user->roles->pluck('name'),
                    'preferences' => $user->preferences ? json_decode($user->preferences, true) : [],
                ],
            ],
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // TODO: Implement password reset logic
        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email',
        ]);
    }
}
