<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsSetting;
use Illuminate\Http\Request;

class SmsSettingsController extends Controller
{
    public function index()
    {
        $categories = SmsSetting::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $settingsByCategory = [];
        foreach ($categories as $category) {
            $settingsByCategory[$category] = SmsSetting::where('category', $category)
                ->orderBy('key')
                ->get();
        }

        return view('admin.sms.settings', compact('settingsByCategory', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.category' => 'required|string|max:255',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'nullable|string',
            'settings.*.description' => 'nullable|string',
        ]);

        foreach ($request->settings as $settingData) {
            SmsSetting::updateOrCreate(
                [
                    'key' => $settingData['key'],
                ],
                [
                    'category' => $settingData['category'],
                    'value' => $settingData['value'] ?? '',
                    'description' => $settingData['description'] ?? null,
                    'last_updated' => now(),
                ]
            );
        }

        return back()->with('success', 'SMS settings updated successfully.');
    }

    public function update(Request $request, SmsSetting $smsSetting)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $smsSetting->update([
            'category' => $request->category,
            'key' => $request->key,
            'value' => $request->value ?? '',
            'description' => $request->description,
            'last_updated' => now(),
        ]);

        return back()->with('success', 'Setting updated successfully.');
    }

    public function destroy(SmsSetting $smsSetting)
    {
        $smsSetting->delete();

        return back()->with('success', 'Setting deleted successfully.');
    }
}
