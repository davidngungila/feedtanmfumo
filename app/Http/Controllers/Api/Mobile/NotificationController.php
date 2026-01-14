<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            'device_token' => 'required|string|max:255',
            'device_type' => 'nullable|string|in:mobile,web',
            'platform' => 'nullable|string|in:ios,android,web',
            'app_version' => 'nullable|string|max:20',
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'device_token' => $validated['device_token'],
            ],
            [
                'device_type' => $validated['device_type'] ?? 'mobile',
                'platform' => $validated['platform'] ?? null,
                'app_version' => $validated['app_version'] ?? null,
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device registered successfully',
            'data' => [
                'device_id' => $deviceToken->id,
            ],
        ]);
    }

    public function unregisterDevice(Request $request, string $deviceToken)
    {
        $device = DeviceToken::where('user_id', Auth::id())
            ->where('device_token', $deviceToken)
            ->first();

        if ($device) {
            $device->update(['is_active' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device unregistered successfully',
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $preferences = $user->preferences ? json_decode($user->preferences, true) : [];

        return response()->json([
            'success' => true,
            'data' => [
                'email_notifications' => $preferences['email_notifications'] ?? true,
                'sms_notifications' => $preferences['sms_notifications'] ?? false,
                'push_notifications' => $preferences['push_notifications'] ?? true,
                'registered_devices' => DeviceToken::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->get()
                    ->map(function ($device) {
                        return [
                            'id' => $device->id,
                            'platform' => $device->platform,
                            'device_type' => $device->device_type,
                            'last_used' => $device->last_used_at?->format('Y-m-d H:i:s'),
                        ];
                    }),
            ],
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $existing = $user->preferences ? json_decode($user->preferences, true) : [];
        $preferences = array_merge($existing, array_filter($validated));

        $user->update([
            'preferences' => json_encode($preferences),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated',
            'data' => [
                'preferences' => $preferences,
            ],
        ]);
    }
}
