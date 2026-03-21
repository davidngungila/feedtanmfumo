<?php

namespace App\Http\Controllers;

use App\Models\FiaPayment;
use App\Models\FiaVerification;
use App\Services\SmsNotificationService;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use PDF;

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
     * Show FIA payments popup page
     */
    public function popup()
    {
        return view('fia.popup');
    }

    /**
     * Show FIA payments Swahili page
     */
    public function swahili()
    {
        return view('fia.swahili');
    }

    /**
     * Show advanced verified payments page
     */
    public function showVerifiedPayments()
    {
        return view('fia.verified-payments');
    }

    /**
     * Lookup membership code
     */
    public function lookupMembership($membershipCode)
    {
        try {
            // Find member in FIA payment records
            $member = DB::table('fia_payment_records')
                ->where('member_id', $membershipCode)
                ->first();

            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Membership code not found in FIA payment records'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'member' => $member
            ]);

        } catch (\Exception $e) {
            Log::error('Error looking up membership: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error looking up membership code'
            ], 500);
        }
    }

    /**
     * Import payments from Excel file
     */
    public function importPayments(Request $request)
    {
        $request->validate([
            'payments_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('payments_file');
            $importedCount = 0;
            $errors = [];

            // Process Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row if present
            $headerRow = array_shift($rows);
            
            foreach ($rows as $index => $row) {
                try {
                    // Map columns (adjust based on your Excel structure)
                    $paymentData = [
                        'name' => $row[0] ?? null,
                        'fia_gawio' => $row[1] ?? null,
                        'fia_iliyokomaa' => $row[2] ?? null,
                        'jumla' => $row[3] ?? 0,
                        'malipo_vipande' => $row[4] ?? 0,
                        'loan' => $row[5] ?? 0,
                        'kiasi_baki' => $row[6] ?? 0,
                        'membership_code' => $row[7] ?? null,
                        'status' => 'pending'
                    ];

                    // Validate required fields
                    if (empty($paymentData['name']) || empty($paymentData['membership_code'])) {
                        $errors[] = "Row " . ($index + 2) . ": Missing name or membership code";
                        continue;
                    }

                    // Create or update payment record
                    \App\Models\FiaAdminPayment::updateOrCreate(
                        ['membership_code' => $paymentData['membership_code']],
                        $paymentData
                    );

                    $importedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => "Successfully imported {$importedCount} payments",
                'imported_count' => $importedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Payment import error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to import payments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit payment verification
     */
    public function submitVerification(Request $request)
    {
        try {
            // Get the data from the request
            $data = $request->all();
            
            // Validate required fields
            $validated = $request->validate([
                'member_id' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'amount_to_pay' => 'required|numeric|min:0',
                'savings_amount' => 'nullable|numeric|min:0',
                'investment_amount' => 'nullable|numeric|min:0',
                'cash_amount' => 'nullable|numeric|min:0',
                'payment_method' => 'required|string|in:bank,mobile',
                'notes' => 'nullable|string|max:1000',
                'fia_type' => 'nullable|string|max:50',
                'swf_amount' => 'nullable|numeric|min:0',
                'loan_repayment_amount' => 'nullable|numeric|min:0',
                'mobile_network' => 'nullable|string|max:50',
                'mobile_number' => 'nullable|string|max:20',
            ]);

            // Get member details from FIA payment records
            $member = DB::table('fia_payment_records')
                ->where('member_id', $validated['member_id'])
                ->first();

            if (!$member) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member not found in FIA payment records'
                ], 404);
            }

            // Create payment confirmation record
            $confirmationData = [
                'user_id' => null,
                'member_id' => $validated['member_id'],
                'member_name' => $member->member_name,
                'member_type' => $member->member_type ?? 'Member',
                'amount_to_pay' => $validated['amount_to_pay'],
                'deposit_balance' => 0,
                'swf_contribution' => 0,
                're_deposit' => $validated['savings_amount'] ?? 0,
                'fia_investment' => $validated['investment_amount'] ?? 0,
                'fia_type' => $validated['fia_type'] ?? null,
                'capital_contribution' => 0,
                'fine_penalty' => 0,
                'loan_repayment' => 0,
                'member_email' => $validated['email'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'bank_name' => $data['bank_name'] ?? null,
                'mobile_provider' => $data['mobile_network'] ?? null,
                'mobile_number' => $data['mobile_number'] ?? null,
                'bank_account_number' => $data['account_number'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert into payment_confirmations table
            $confirmationId = DB::table('payment_confirmations')->insertGetId($confirmationData);

            // Generate PDF with all information
            try {
                $pdfData = [
                    'confirmation' => DB::table('payment_confirmations')->find($confirmationId),
                    'member' => $member,
                    'payment_breakdown' => [
                        'gawio_la_fia' => $member->gawio_la_fia ?? 0,
                        'fia_iliyokomaa' => $member->fia_iliyokomaa ?? 0,
                        'jumla' => $member->jumla ?? 0,
                        'malipo_ya_vipande_yaliyokuwa_yamepelea' => $member->malipo_ya_vipande_yaliyokuwa_yamepelea ?? 0,
                        'loan' => $member->loan ?? 0,
                        'kiasi_baki' => $member->kiasi_baki ?? 0,
                    ],
                    'distribution' => [
                        'savings_amount' => $validated['savings_amount'] ?? 0,
                        'investment_amount' => $validated['investment_amount'] ?? 0,
                        'swf_amount' => $validated['swf_amount'] ?? 0,
                        'loan_repayment_amount' => $validated['loan_repayment_amount'] ?? 0,
                        'cash_amount' => $validated['cash_amount'] ?? 0,
                    ],
                    'payment_method' => $validated['payment_method'],
                    'fia_type' => $validated['fia_type'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ];

                $pdf = PDF::loadView('fia.payment-confirmation-pdf', $pdfData);
                $pdfFileName = 'FIA_Payment_Confirmation_' . $validated['member_id'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
                $pdfPath = storage_path('app/public/pdf/' . $pdfFileName);
                
                // Ensure directory exists
                $directory = dirname($pdfPath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $pdf->save($pdfPath);

                // Send email with PDF attachment
                $emailData = [
                    'member_name' => $member->member_name ?? 'Mwanachama',
                    'member_id' => $validated['member_id'],
                    'confirmation_id' => $confirmationId,
                    'amount' => $validated['amount_to_pay'],
                    'payment_method' => $validated['payment_method'],
                    'confirmation_date' => now()->format('d M Y, H:i'),
                ];

                Mail::send('fia.payment-confirmation-email', $emailData, function($message) use ($validated, $pdfPath, $pdfFileName) {
                    $message->to($validated['email'])
                            ->subject('Thibitisho la Malipo la FIA - FEEDTAN CMG')
                            ->attach($pdfPath, [
                                'as' => $pdfFileName,
                                'mime' => 'application/pdf',
                            ]);
                });

                Log::info('PDF confirmation generated and sent successfully to: ' . $validated['email']);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment confirmation submitted successfully! PDF document has been generated and sent to your email.',
                    'confirmation_id' => $confirmationId,
                    'pdf_generated' => true,
                    'email_sent' => true
                ]);

            } catch (\Exception $pdfError) {
                Log::error('Error generating PDF or sending email: ' . $pdfError->getMessage());
                
                // Still return success even if PDF fails
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment confirmation submitted successfully!',
                    'confirmation_id' => $confirmationId,
                    'pdf_generated' => false,
                    'email_sent' => false
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment verification submission error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit payment confirmation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get member payments
     */
    public function getPayments()
    {
        try {
            $payments = DB::table('fia_payment_records')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting payments: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving payments'
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

    /**
     * Get member payment breakdown
     */
    private function getMemberPaymentBreakdown($member)
    {
        // Get FIA admin payment for this member
        $fiaPayment = \App\Models\FiaAdminPayment::where('membership_code', $member->membership_code)
            ->first();

        if (!$fiaPayment) {
            return [];
        }

        // Create payment breakdown
        $breakdown = [];

        // Add FIA Gawio
        if ($fiaPayment->fia_gawio) {
            $breakdown[] = [
                'description' => 'FIA Gawio',
                'amount' => $fiaPayment->fia_gawio,
                'due_date' => now()->format('d M Y'),
                'status' => 'pending'
            ];
        }

        // Add FIA Iliyokomaa
        if ($fiaPayment->fia_iliyokomaa) {
            $breakdown[] = [
                'description' => 'FIA Iliyokomaa',
                'amount' => $fiaPayment->fia_iliyokomaa,
                'due_date' => now()->format('d M Y'),
                'status' => 'pending'
            ];
        }

        // Add Malipo ya vipande
        if ($fiaPayment->malipo_vipande) {
            $breakdown[] = [
                'description' => 'Malipo ya vipande yailiyokuwa',
                'amount' => $fiaPayment->malipo_vipande,
                'due_date' => now()->format('d M Y'),
                'status' => 'pending'
            ];
        }

        // Add Loan
        if ($fiaPayment->loan) {
            $breakdown[] = [
                'description' => 'Loan',
                'amount' => $fiaPayment->loan,
                'due_date' => now()->format('d M Y'),
                'status' => 'pending'
            ];
        }

        return $breakdown;
    }
}
