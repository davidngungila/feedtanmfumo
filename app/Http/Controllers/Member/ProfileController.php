<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Issue;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('roles');

        // Get user statistics
        $stats = [
            'loans' => Loan::where('user_id', $user->id)->count(),
            'savings' => SavingsAccount::where('user_id', $user->id)->count(),
            'investments' => Investment::where('user_id', $user->id)->count(),
            'issues' => Issue::where('user_id', $user->id)->count(),
        ];

        return view('member.profile.index', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('member.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            
            // Delete old profile image if exists
            if ($user->profile_image) {
                $oldImagePath = storage_path('app/public/' . $user->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('profile_images', $imageName, 'public');
            $validated['profile_image'] = $imagePath;
        }

        $user->update($validated);

        return redirect()->route('member.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('member.profile.index')->with('success', 'Password updated successfully.');
    }

    public function settings()
    {
        $user = Auth::user();
        $preferences = $user->preferences ? json_decode($user->preferences, true) : [];

        return view('member.profile.settings', compact('user', 'preferences'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email_notifications' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'in:en,sw'],
            'timezone' => ['nullable', 'string'],
        ]);

        // Merge with existing preferences
        $existing = $user->preferences ? json_decode($user->preferences, true) : [];
        $preferences = array_merge($existing, array_filter($validated));

        $user->update([
            'preferences' => json_encode($preferences),
        ]);

        return redirect()->route('member.profile.settings')->with('success', 'Settings updated successfully.');
    }
}
