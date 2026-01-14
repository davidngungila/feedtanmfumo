<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $preferences = $user->preferences ? json_decode($user->preferences, true) : [];

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'preferences' => $preferences,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'language' => 'nullable|string|in:en,sw',
        ]);

        $user->update($validated);

        // Update language preference
        if ($request->has('language')) {
            $preferences = $user->preferences ? json_decode($user->preferences, true) : [];
            $preferences['language'] = $request->input('language');
            $user->update(['preferences' => json_encode($preferences)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ]);
    }

    public function documents(Request $request)
    {
        $user = $request->user();

        // TODO: Return user documents (statements, receipts, etc.)
        return response()->json([
            'success' => true,
            'data' => [
                'documents' => [],
            ],
        ]);
    }
}
