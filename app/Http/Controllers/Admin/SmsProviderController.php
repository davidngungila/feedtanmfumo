<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsProviderController extends Controller
{
    /**
     * Show the form for creating a new SMS provider
     */
    public function create()
    {
        return view('admin.settings.sms-provider.create');
    }

    /**
     * Store a newly created SMS provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'from' => 'nullable|string|max:11',
            'api_url' => 'required|url',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
        ]);

        try {
            $provider = SmsProvider::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'from' => $validated['from'] ?? 'FEEDTAN',
                'api_url' => $validated['api_url'],
                'description' => $validated['description'] ?? null,
                'active' => $request->has('active'),
                'is_primary' => $request->has('is_primary'),
            ]);

            // If set as primary, ensure it's the only primary
            if ($request->has('is_primary')) {
                $provider->setAsPrimary();
            }

            return redirect()->route('admin.settings.communication')
                ->with('success', 'SMS provider created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create SMS provider: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create SMS provider: ' . $e->getMessage());
        }
    }

    /**
     * Test SMS provider connection
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'api_url' => 'required|url',
            'from' => 'nullable|string|max:11',
            'test_phone' => 'required|string|regex:/^255[0-9]{9}$/',
        ]);

        try {
            $phoneNumber = $request->test_phone;
            $message = 'Test SMS from FeedTan - Connection test';
            $from = $request->from ?? 'FEEDTAN';

            // Format phone number
            $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (strpos($cleaned, '0') === 0) {
                $cleaned = '255' . substr($cleaned, 1);
            } elseif (strpos($cleaned, '255') !== 0) {
                $cleaned = '255' . $cleaned;
            }

            if (!preg_match('/^255[0-9]{9}$/', $cleaned)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid phone number format. Expected: 255XXXXXXXXX (12 digits starting with 255)'
                ], 400);
            }

            // Normalize API URL
            $apiUrl = $request->api_url;
            if (strpos($apiUrl, '/api/sms') !== false) {
                if (strpos($apiUrl, '/v1/') !== false) {
                    $apiUrl = str_replace('/v1/', '/v2/', $apiUrl);
                } elseif (strpos($apiUrl, '/v2/') === false && strpos($apiUrl, '/text/') === false) {
                    $apiUrl = rtrim($apiUrl, '/') . '/v2/text/single';
                }
            } elseif (strpos($apiUrl, '/link/sms') !== false) {
                $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
            } else {
                $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
            }

            // Use Bearer token authentication (username field contains the Bearer token)
            $bearerToken = $request->username;
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($apiUrl, [
                    'from' => $from,
                    'to' => $cleaned,
                    'text' => $message,
                    'flash' => 0,
                    'reference' => 'feedtan_test_' . time()
                ]);

            $httpCode = $response->status();
            $responseBody = $response->body();

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check response structure
                if (isset($responseData['messages']) && is_array($responseData['messages']) && count($responseData['messages']) > 0) {
                    $messageStatus = $responseData['messages'][0]['status'] ?? [];
                    
                    if (isset($messageStatus['groupName']) && $messageStatus['groupName'] === 'REJECTED') {
                        $errorMsg = $messageStatus['description'] ?? $messageStatus['name'] ?? 'Message rejected';
                        return response()->json([
                            'success' => false,
                            'error' => $errorMsg,
                            'status' => 'REJECTED'
                        ], 400);
                    }
                    
                    // Message is pending or sent
                    if (($messageStatus['groupName'] ?? '') === 'PENDING' || ($messageStatus['id'] ?? 0) === 51) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Connection test successful! SMS sent successfully.',
                            'status' => 'CONNECTED',
                            'message_id' => $responseData['messages'][0]['messageId'] ?? null
                        ]);
                    }
                    
                    return response()->json([
                        'success' => false,
                        'error' => $messageStatus['description'] ?? $messageStatus['name'] ?? 'Unknown status',
                        'status' => $messageStatus['groupName'] ?? 'UNKNOWN'
                    ], 400);
                }
                
                // If response structure is different but status is 200, assume success
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful!',
                    'status' => 'CONNECTED'
                ]);
            }

            $errorMsg = "Connection failed with HTTP code {$httpCode}";
            if ($responseBody) {
                $errorMsg .= ': ' . substr($responseBody, 0, 200);
            }

            return response()->json([
                'success' => false,
                'error' => $errorMsg,
                'status' => 'FAILED',
                'http_code' => $httpCode
            ], 400);

        } catch (\Exception $e) {
            Log::error('SMS connection test failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified SMS provider
     */
    public function edit(SmsProvider $smsProvider)
    {
        return view('admin.settings.sms-provider.edit', compact('smsProvider'));
    }

    /**
     * Update the specified SMS provider
     */
    public function update(Request $request, SmsProvider $smsProvider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string',
            'from' => 'nullable|string|max:11',
            'api_url' => 'required|url',
            'description' => 'nullable|string',
            'active' => 'nullable|boolean',
            'is_primary' => 'nullable|boolean',
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'from' => $validated['from'] ?? 'FEEDTAN',
                'api_url' => $validated['api_url'],
                'description' => $validated['description'] ?? null,
                'active' => $request->has('active'),
                'is_primary' => $request->has('is_primary'),
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = $validated['password'];
            }

            $smsProvider->update($updateData);

            // If set as primary, ensure it's the only primary
            if ($request->has('is_primary')) {
                $smsProvider->setAsPrimary();
            }

            return redirect()->route('admin.settings.communication')
                ->with('success', 'SMS provider updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update SMS provider: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update SMS provider: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified SMS provider
     */
    public function destroy(SmsProvider $smsProvider)
    {
        try {
            // Prevent deleting primary provider
            if ($smsProvider->is_primary) {
                return redirect()->route('admin.settings.communication')
                    ->with('error', 'Cannot delete the primary SMS provider. Please set another provider as primary first.');
            }

            $smsProvider->delete();

            return redirect()->route('admin.settings.communication')
                ->with('success', 'SMS provider deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete SMS provider: ' . $e->getMessage());
            return redirect()->route('admin.settings.communication')
                ->with('error', 'Failed to delete SMS provider: ' . $e->getMessage());
        }
    }

    /**
     * Set provider as primary
     */
    public function setPrimary(SmsProvider $smsProvider)
    {
        try {
            $smsProvider->setAsPrimary();

            return redirect()->route('admin.settings.communication')
                ->with('success', 'SMS provider set as primary successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to set primary SMS provider: ' . $e->getMessage());
            return redirect()->route('admin.settings.communication')
                ->with('error', 'Failed to set primary SMS provider: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified SMS provider
     */
    public function show(SmsProvider $smsProvider)
    {
        return view('admin.settings.sms-provider.show', compact('smsProvider'));
    }

    /**
     * Test SMS provider configuration
     */
    public function test(Request $request, SmsProvider $smsProvider)
    {
        $request->validate([
            'test_phone' => 'required|string',
            'test_message' => 'nullable|string|max:500',
        ]);

        try {
            $phoneNumber = $request->test_phone;
            $message = $request->test_message ?? "Test SMS from {$smsProvider->name} - Configuration test successful!";
            $from = $smsProvider->from ?? 'FEEDTAN';

            // Format phone number
            $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (strpos($cleaned, '0') === 0) {
                $cleaned = '255' . substr($cleaned, 1);
            } elseif (strpos($cleaned, '255') !== 0) {
                $cleaned = '255' . $cleaned;
            }

            if (!preg_match('/^255[0-9]{9}$/', $cleaned)) {
                return redirect()->route('admin.sms-provider.show', $smsProvider)
                    ->with('error', 'Invalid phone number format. Expected: 255XXXXXXXXX (12 digits starting with 255)');
            }

            // Ensure API URL has the correct endpoint
            $apiUrl = $smsProvider->api_url;
            // Normalize API URL to use v2 endpoint
            if (strpos($apiUrl, '/api/sms') !== false) {
                // Replace v1 with v2 if present, or add v2/text/single if not
                if (strpos($apiUrl, '/v1/') !== false) {
                    $apiUrl = str_replace('/v1/', '/v2/', $apiUrl);
                } elseif (strpos($apiUrl, '/v2/') === false && strpos($apiUrl, '/text/') === false) {
                    $apiUrl = rtrim($apiUrl, '/') . '/v2/text/single';
                }
            } elseif (strpos($apiUrl, '/link/sms') !== false) {
                // Convert old link format to new API format
                $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
            } else {
                // Default to v2 endpoint
                $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
            }

            // Use Bearer token authentication (username field contains the Bearer token)
            $bearerToken = $smsProvider->username; // Username field is actually the Bearer token
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($apiUrl, [
                    'from' => $from,
                    'to' => $cleaned,
                    'text' => $message,
                    'flash' => 0,
                    'reference' => 'feedtan_test_' . time()
                ]);

            $httpCode = $response->status();
            $responseBody = $response->body();

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check response structure
                if (isset($responseData['messages']) && is_array($responseData['messages']) && count($responseData['messages']) > 0) {
                    $messageStatus = $responseData['messages'][0]['status'] ?? [];
                    
                    if (isset($messageStatus['groupName']) && $messageStatus['groupName'] === 'REJECTED') {
                        $errorMsg = $messageStatus['description'] ?? $messageStatus['name'] ?? 'Message rejected';
                        return redirect()->route('admin.sms-provider.show', $smsProvider)
                            ->with('error', 'SMS test failed: ' . $errorMsg);
                    }
                    
                    // Message is pending or sent
                    if (($messageStatus['groupName'] ?? '') === 'PENDING' || ($messageStatus['id'] ?? 0) === 51) {
                        return redirect()->route('admin.sms-provider.show', $smsProvider)
                            ->with('success', "Test SMS sent successfully to {$cleaned}!");
                    }
                    
                    return redirect()->route('admin.sms-provider.show', $smsProvider)
                        ->with('error', 'SMS test failed: ' . ($messageStatus['description'] ?? $messageStatus['name'] ?? 'Unknown status'));
                }
                
                // If response structure is different but status is 200, assume success
                return redirect()->route('admin.sms-provider.show', $smsProvider)
                    ->with('success', "Test SMS sent successfully to {$cleaned}!");
            }

            $errorMsg = "SMS test failed with HTTP code {$httpCode}";
            if ($responseBody) {
                $errorMsg .= ': ' . substr($responseBody, 0, 200);
            }

            return redirect()->route('admin.sms-provider.show', $smsProvider)
                ->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('SMS test failed: ' . $e->getMessage());
            return redirect()->route('admin.sms-provider.show', $smsProvider)
                ->with('error', 'SMS test failed: ' . $e->getMessage());
        }
    }
}
