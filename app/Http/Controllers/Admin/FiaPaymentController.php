<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

class FiaPaymentController extends Controller
{
    /**
     * Display FIA Payment Confirmations dashboard
     */
    public function index()
    {
        // Only get FIA payment confirmations that have matching records in fia_payment_records
        $totalConfirmations = DB::table('payment_confirmations')
            ->join('fia_payment_records', 'payment_confirmations.member_id', '=', 'fia_payment_records.member_id')
            ->count();
        $totalAmount = DB::table('payment_confirmations')
            ->join('fia_payment_records', 'payment_confirmations.member_id', '=', 'fia_payment_records.member_id')
            ->sum('payment_confirmations.amount_to_pay');
        
        // Since there's no status column, all confirmations are considered pending
        $pendingConfirmations = $totalConfirmations;
        $verifiedConfirmations = 0;
        
        // Get recent FIA confirmations that have matching records
        $recentConfirmations = DB::table('payment_confirmations')
            ->join('fia_payment_records', 'payment_confirmations.member_id', '=', 'fia_payment_records.member_id')
            ->select('payment_confirmations.*')
            ->orderBy('payment_confirmations.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($confirmation) {
                $confirmation->created_at = \Carbon\Carbon::parse($confirmation->created_at);
                return $confirmation;
            });
        
        return view('admin.fia-payments.index', compact(
            'totalConfirmations',
            'verifiedConfirmations', 
            'pendingConfirmations',
            'totalAmount',
            'recentConfirmations'
        ));
    }

    /**
     * Display all payment confirmations
     */
    public function confirmations()
    {
        $confirmations = DB::table('payment_confirmations')
            ->join('fia_payment_records', 'payment_confirmations.member_id', '=', 'fia_payment_records.member_id')
            ->select('payment_confirmations.*')
            ->orderBy('payment_confirmations.created_at', 'desc')
            ->paginate(20);
            
        return view('admin.fia-payments.confirmations', compact('confirmations'));
    }

    /**
     * Get confirmations data for AJAX
     */
    public function getConfirmations(Request $request)
    {
        $query = DB::table('payment_confirmations')
            ->join('fia_payment_records', 'payment_confirmations.member_id', '=', 'fia_payment_records.member_id')
            ->select('payment_confirmations.*');
        
        // Search by member ID or name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_confirmations.member_id', 'like', "%{$search}%")
                  ->orWhere('payment_confirmations.member_name', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_confirmations.created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_confirmations.created_at', '<=', $request->date_to);
        }

        $confirmations = $query->orderBy('payment_confirmations.created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $confirmations
        ]);
    }

    /**
     * Verify a payment confirmation
     */
    public function verifyConfirmation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'verified' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            'send_email' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $confirmation = DB::table('payment_confirmations')->where('id', $request->id)->first();
            
            if (!$confirmation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment confirmation not found'
                ], 404);
            }

            // Get the corresponding FIA payment record
            $paymentRecord = DB::table('fia_payment_records')
                ->where('member_id', $confirmation->member_id)
                ->first();

            // Update confirmation with notes
            DB::table('payment_confirmations')
                ->where('id', $request->id)
                ->update([
                    'notes' => $request->notes,
                    'updated_at' => now()
                ]);

            // Generate and send PDF if requested
            if ($request->send_email && !empty($confirmation->member_email)) {
                $this->generateAndSendPdf($confirmation, $paymentRecord);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmation updated successfully' . ($request->send_email ? ' and PDF sent to email' : '')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating confirmation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF and send email
     */
    private function generateAndSendPdf($confirmation, $paymentRecord)
    {
        try {
            // Generate PDF
            $pdfData = [
                'confirmation' => $confirmation,
                'paymentRecord' => $paymentRecord,
                'generated_at' => now()
            ];

            $pdf = PDF::loadView('pdf.fia-payment-confirmation', $pdfData);
            $pdfFilename = 'FIA_Payment_Confirmation_' . $confirmation->member_id . '_' . date('Y-m-d') . '.pdf';

            // Send email
            Mail::send('emails.fia-payment-confirmation', $pdfData, function($message) use ($confirmation, $pdf, $pdfFilename) {
                $message->to($confirmation->member_email)
                    ->subject('FIA Payment Confirmation - ' . $confirmation->member_name)
                    ->attachData($pdf->output(), $pdfFilename, [
                        'mime' => 'application/pdf',
                    ]);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Error sending PDF email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bulk verify confirmations
     */
    public function bulkVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'verified' => 'required|boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = DB::table('payment_confirmations')
                ->whereIn('id', $request->ids)
                ->update([
                    'notes' => $request->notes,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => "Updated {$updated} payment confirmations successfully"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating confirmations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export confirmations to Excel
     */
    public function exportConfirmations(Request $request)
    {
        $query = DB::table('payment_confirmations')->where('fia_investment', '>', 0);
        
        // Search by member ID or name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhere('member_name', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $confirmations = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV content
        $filename = 'fia_payment_confirmations_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($confirmations) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'ID', 'Member ID', 'Member Name', 'Member Type', 'Amount to Pay',
                'FIA Investment', 'FIA Type', 'SWF Contribution', 'Re-Deposit',
                'Capital Contribution', 'Loan Repayment', 'Fine Penalty',
                'Member Email', 'Payment Method', 'Bank Name', 'Mobile Provider',
                'Mobile Number', 'Bank Account Number', 'Notes', 'Created At'
            ]);
            
            // CSV Data
            foreach ($confirmations as $confirmation) {
                fputcsv($file, [
                    $confirmation->id,
                    $confirmation->member_id,
                    $confirmation->member_name,
                    $confirmation->member_type,
                    $confirmation->amount_to_pay,
                    $confirmation->fia_investment,
                    $confirmation->fia_type,
                    $confirmation->swf_contribution,
                    $confirmation->re_deposit,
                    $confirmation->capital_contribution,
                    $confirmation->loan_repayment,
                    $confirmation->fine_penalty,
                    $confirmation->member_email,
                    $confirmation->payment_method,
                    $confirmation->bank_name,
                    $confirmation->mobile_provider,
                    $confirmation->mobile_number,
                    $confirmation->bank_account_number,
                    $confirmation->notes,
                    $confirmation->created_at
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get confirmation statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_confirmations' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->count(),
            'total_amount' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('amount_to_pay'),
            'total_fia_investment' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('fia_investment'),
            'recent_confirmations' => DB::table('payment_confirmations')
                ->where('fia_investment', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Delete a payment confirmation
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('payment_confirmations')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmation deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment confirmation not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display FIA Payment Confirmations page
     */
    public function confirmations()
    {
        return view('admin.fia-payments.confirmations');
    }

    /**
     * Get FIA Payment Confirmations data for AJAX
     */
    public function getConfirmations(Request $request)
    {
        try {
            $query = DB::table('payment_confirmations')
                ->select([
                    'id',
                    'member_id',
                    'member_name', 
                    'amount_to_pay as amount',
                    'created_at as payment_date',
                    DB::raw("'pending' as status")
                ])
                ->where('fia_investment', '>', 0) // Only FIA payments
                ->orderBy('created_at', 'desc');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('member_id', 'like', "%{$search}%")
                      ->orWhere('member_name', 'like', "%{$search}%");
                });
            }

            // Status filter (though all are pending for now)
            if ($request->filled('status') && $request->input('status') != '') {
                $query->whereRaw("'pending' = ?", [$request->input('status')]);
            }

            // Date filters
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->input('date_to'));
            }

            $payments = $query->paginate(20);

            // Format the data for the frontend
            $formattedData = $payments->getCollection()->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'membership_code' => $payment->member_id,
                    'member_name' => $payment->member_name,
                    'reference' => 'FIA-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                    'amount' => number_format($payment->amount, 2),
                    'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('d M Y'),
                    'status' => $payment->status,
                    'actions' => view('admin.fia-payments.partials.actions', ['payment' => $payment])->render()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'per_page' => $payments->perPage(),
                    'total' => $payments->total(),
                    'from' => $payments->firstItem(),
                    'to' => $payments->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting FIA confirmations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a payment confirmation
     */
    public function verifyConfirmation(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'status' => 'required|in:verified,rejected'
            ]);

            $updated = DB::table('payment_confirmations')
                ->where('id', $request->id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => "Payment marked as {$request->status}"
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk verify payments
     */
    public function bulkVerify(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer',
                'status' => 'required|in:verified,rejected'
            ]);

            $updated = DB::table('payment_confirmations')
                ->whereIn('id', $request->ids)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => "{$updated} payments marked as {$request->status}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export confirmations to Excel
     */
    public function exportConfirmations(Request $request)
    {
        // Similar to existing export method but for FIA payments only
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fia-confirmations.csv"',
        ];

        $callback = function () use ($request) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, ['ID', 'Member ID', 'Member Name', 'Amount', 'Payment Date', 'Status']);
            
            $query = DB::table('payment_confirmations')
                ->where('fia_investment', '>', 0)
                ->orderBy('created_at', 'desc');

            // Apply same filters as getConfirmations
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('member_id', 'like', "%{$search}%")
                      ->orWhere('member_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->input('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->input('date_to'));
            }

            $confirmations = $query->get();
            
            foreach ($confirmations as $confirmation) {
                fputcsv($file, [
                    $confirmation->id,
                    $confirmation->member_id,
                    $confirmation->member_name,
                    $confirmation->amount_to_pay,
                    $confirmation->created_at,
                    'pending'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
