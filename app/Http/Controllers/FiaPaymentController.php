<?php

namespace App\Http\Controllers;

use App\Models\FiaPayment;
use App\Models\FiaVerification;
use App\Services\SmsNotificationService;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FiaPaymentController extends Controller
{
    protected $smsService;
    protected $emailService;

    public function __construct(SmsNotificationService $smsService, EmailNotificationService $emailService)
    {
        $this->smsService = $smsService;
        $this->emailService = $emailService;
    }

    /**
     * Show FIA payments verification page
     */
    public function index()
    {
        return view('fia.index');
    }

    /**
     * Submit payment verification
     */
    public function submitVerification(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'payment_reference' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:mobile_money,bank_transfer,cash,other',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if verification already exists for this reference
            $existingVerification = FiaVerification::where('payment_reference', $validated['payment_reference'])
                ->where('status', '!=', 'rejected')
                ->first();

            if ($existingVerification) {
                // Remove previous submission if it exists
                $existingVerification->delete();
                Log::info('Previous FIA verification removed and replaced', [
                    'payment_reference' => $validated['payment_reference'],
                    'previous_id' => $existingVerification->id
                ]);
            }

            // Create new verification
            $verification = FiaVerification::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'payment_reference' => $validated['payment_reference'],
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'description' => $validated['description'] ?? null,
                'status' => 'pending',
                'submitted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send confirmation notification
            $this->sendVerificationNotification($verification);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verification submitted successfully',
                'verification_id' => $verification->id
            ]);

        } catch (\Exception $e) {
            Log::error('FIA verification submission error', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit verification'
            ], 500);
        }
    }

    /**
     * Verify payment and mark as paid
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'verification_id' => 'required|exists:fia_verifications,id',
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $verification = FiaVerification::findOrFail($validated['verification_id']);

            if ($verification->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This verification has already been processed'
                ], 400);
            }

            // Update verification status
            $verification->update([
                'status' => $validated['action'] === 'approve' ? 'verified' : 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $validated['notes'] ?? null,
            ]);

            // If approved, create payment record
            if ($validated['action'] === 'approve') {
                $this->createPaymentRecord($verification);
                $this->sendApprovalNotification($verification);
            } else {
                $this->sendRejectionNotification($verification);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Payment verification {$validated['action']}d successfully"
            ]);

        } catch (\Exception $e) {
            Log::error('FIA payment verification error', [
                'error' => $e->getMessage(),
                'verification_id' => $validated['verification_id']
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process verification'
            ], 500);
        }
    }

    /**
     * Get all payments data
     */
    public function getPayments(Request $request)
    {
        $payments = FiaPayment::with(['verification'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $payments->items(),
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ]
        ]);
    }

    /**
     * Get verified payments only
     */
    public function getVerifiedPayments(Request $request)
    {
        $verifiedPayments = FiaPayment::with(['verification'])
            ->where('status', 'verified')
            ->orderBy('verified_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $verifiedPayments->items(),
            'pagination' => [
                'current_page' => $verifiedPayments->currentPage(),
                'last_page' => $verifiedPayments->lastPage(),
                'per_page' => $verifiedPayments->perPage(),
                'total' => $verifiedPayments->total(),
            ]
        ]);
    }

    /**
     * Create payment record from verification
     */
    private function createPaymentRecord($verification)
    {
        FiaPayment::create([
            'verification_id' => $verification->id,
            'name' => $verification->name,
            'phone' => $verification->phone,
            'email' => $verification->email,
            'payment_reference' => $verification->payment_reference,
            'amount' => $verification->amount,
            'payment_date' => $verification->payment_date,
            'payment_method' => $verification->payment_method,
            'description' => $verification->description,
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
    }

    /**
     * Send verification notification
     */
    private function sendVerificationNotification($verification)
    {
        try {
            // Send SMS
            $message = "FIA Payment Verification Received. Ref: {$verification->payment_reference}. Amount: TSh " . number_format($verification->amount) . ". We will process your verification shortly.";
            $this->smsService->sendSms($verification->phone, $message, [
                'verification_id' => $verification->id,
                'type' => 'fia_verification'
            ]);

            // Send Email
            $this->emailService->sendCustomEmail(
                $verification->email,
                'FIA Payment Verification Received - Feedtan CMG',
                'emails.fia-verification',
                [
                    'verification' => $verification,
                    'amount_formatted' => number_format($verification->amount),
                    'payment_date_formatted' => $verification->payment_date->format('d M Y')
                ]
            );

        } catch (\Exception $e) {
            Log::error('Failed to send FIA verification notification', [
                'error' => $e->getMessage(),
                'verification_id' => $verification->id
            ]);
        }
    }

    /**
     * Send approval notification
     */
    private function sendApprovalNotification($verification)
    {
        try {
            // Send SMS
            $message = "FIA Payment Verified Successfully! Ref: {$verification->payment_reference}. Amount: TSh " . number_format($verification->amount) . ". Thank you for using Feedtan CMG.";
            $this->smsService->sendSms($verification->phone, $message, [
                'verification_id' => $verification->id,
                'type' => 'fia_approval'
            ]);

            // Send Email
            $this->emailService->sendCustomEmail(
                $verification->email,
                'FIA Payment Verified Successfully - Feedtan CMG',
                'emails.fia-approval',
                [
                    'verification' => $verification,
                    'amount_formatted' => number_format($verification->amount),
                    'payment_date_formatted' => $verification->payment_date->format('d M Y'),
                    'verified_date_formatted' => now()->format('d M Y, H:i')
                ]
            );

        } catch (\Exception $e) {
            Log::error('Failed to send FIA approval notification', [
                'error' => $e->getMessage(),
                'verification_id' => $verification->id
            ]);
        }
    }

    /**
     * Send rejection notification
     */
    private function sendRejectionNotification($verification)
    {
        try {
            // Send SMS
            $message = "FIA Payment Verification Rejected. Ref: {$verification->payment_reference}. Please contact support for assistance.";
            $this->smsService->sendSms($verification->phone, $message, [
                'verification_id' => $verification->id,
                'type' => 'fia_rejection'
            ]);

            // Send Email
            $this->emailService->sendCustomEmail(
                $verification->email,
                'FIA Payment Verification Rejected - Feedtan CMG',
                'emails.fia-rejection',
                [
                    'verification' => $verification,
                    'amount_formatted' => number_format($verification->amount),
                    'payment_date_formatted' => $verification->payment_date->format('d M Y'),
                    'rejection_notes' => $verification->verification_notes
                ]
            );

        } catch (\Exception $e) {
            Log::error('Failed to send FIA rejection notification', [
                'error' => $e->getMessage(),
                'verification_id' => $verification->id
            ]);
        }
    }
}
