<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailProviderController extends Controller
{
    /**
     * Show the form for creating a new email provider
     */
    public function create()
    {
        return view('admin.settings.email-provider.create');
    }

    /**
     * Store a newly created email provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mailer' => 'required|string|in:smtp,sendmail',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'encryption' => 'required|string|in:tls,ssl',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
        ]);

        try {
            $provider = EmailProvider::create([
                'name' => $validated['name'],
                'mailer' => $validated['mailer'],
                'host' => $validated['host'],
                'port' => $validated['port'],
                'encryption' => $validated['encryption'],
                'username' => $validated['username'] ?? null,
                'password' => $validated['password'] ?? null,
                'from_address' => $validated['from_address'],
                'from_name' => $validated['from_name'] ?? null,
                'description' => $validated['description'] ?? null,
                'active' => $request->has('active'),
                'is_primary' => $request->has('is_primary'),
            ]);

            // If set as primary, ensure it's the only primary
            if ($request->has('is_primary')) {
                $provider->setAsPrimary();
            }

            return redirect()->route('admin.settings.communication')
                ->with('success', 'Email provider created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create email provider: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create email provider: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified email provider
     */
    public function edit(EmailProvider $emailProvider)
    {
        return view('admin.settings.email-provider.edit', compact('emailProvider'));
    }

    /**
     * Update the specified email provider
     */
    public function update(Request $request, EmailProvider $emailProvider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mailer' => 'required|string|in:smtp,sendmail',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'encryption' => 'required|string|in:tls,ssl',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
            'from_address' => 'required|email',
            'from_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'mailer' => $validated['mailer'],
                'host' => $validated['host'],
                'port' => $validated['port'],
                'encryption' => $validated['encryption'],
                'username' => $validated['username'] ?? null,
                'from_address' => $validated['from_address'],
                'from_name' => $validated['from_name'] ?? null,
                'description' => $validated['description'] ?? null,
                'active' => $request->has('active'),
                'is_primary' => $request->has('is_primary'),
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = $validated['password'];
            }

            $emailProvider->update($updateData);

            // If set as primary, ensure it's the only primary
            if ($request->has('is_primary')) {
                $emailProvider->setAsPrimary();
            }

            return redirect()->route('admin.settings.communication')
                ->with('success', 'Email provider updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update email provider: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update email provider: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified email provider
     */
    public function destroy(EmailProvider $emailProvider)
    {
        try {
            // Prevent deleting primary provider
            if ($emailProvider->is_primary) {
                return redirect()->route('admin.settings.communication')
                    ->with('error', 'Cannot delete the primary email provider. Please set another provider as primary first.');
            }

            $emailProvider->delete();

            return redirect()->route('admin.settings.communication')
                ->with('success', 'Email provider deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete email provider: ' . $e->getMessage());
            return redirect()->route('admin.settings.communication')
                ->with('error', 'Failed to delete email provider: ' . $e->getMessage());
        }
    }

    /**
     * Set provider as primary
     */
    public function setPrimary(EmailProvider $emailProvider)
    {
        try {
            $emailProvider->setAsPrimary();

            return redirect()->route('admin.settings.communication')
                ->with('success', 'Email provider set as primary successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to set primary email provider: ' . $e->getMessage());
            return redirect()->route('admin.settings.communication')
                ->with('error', 'Failed to set primary email provider: ' . $e->getMessage());
        }
    }
}
