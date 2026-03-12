<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\GuarantorAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\GuaranteeAgreementMail;
use App\Mail\GuarantorSubmittedNotification;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class GuarantorAssessmentController extends Controller
{
    /**
     * Show the assessment form to the guarantor.
     */
    public function show($loanUlid)
    {
        $loan = Loan::where('ulid', $loanUlid)->with('user')->firstOrFail();
        
        // Only get active members for the guarantor list
        $members = User::where('status', 'approved')
            ->orderBy('name')
            ->get(['id', 'name', 'member_number']);

        return view('guarantor-assessment.form', compact('loan', 'members'));
    }

    /**
     * Show the advanced assessment form to the guarantor.
     */
    public function showAdvanced($loanUlid)
    {
        $loan = Loan::where('ulid', $loanUlid)->with('user')->firstOrFail();
        return view('guarantor-assessment.advanced', compact('loan'));
    }

    /**
     * Verify member code for guarantor assessment.
     */
    public function verifyMember($memberCode)
    {
        try {
            $member = User::where('membership_code', $memberCode)
                ->where('status', 'approved')
                ->first();

            if (!$member) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Member not found or not approved'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'membership_code' => $member->membership_code,
                    'phone' => $member->phone,
                    'email' => $member->email,
                    'membership_status' => $member->membership_status ?? 'Active',
                    'created_at' => $member->created_at
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Member verification error', [
                'member_code' => $memberCode,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error verifying member'
            ], 500);
        }
    }

    /**
     * Store the assessment response.
     */
    public function store(Request $request, $loanUlid)
    {
        $loan = Loan::where('ulid', $loanUlid)->firstOrFail();

        $validated = $request->validate([
            'guarantor_id' => 'required|exists:users,id',
            'member_code' => 'required|string',
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'relationship' => 'required|string',
            'relationship_other' => 'required_if:relationship,other|nullable|string',
            'address' => 'required|string',
            'occupation' => 'required|string',
            'monthly_income' => 'required|numeric|min:0',
            'loan_purpose' => 'required|string',
            'loan_purpose_other' => 'required_if:loan_purpose,other|nullable|string',
            'repayment_history' => 'required|string',
            'existing_debts' => 'required|string',
            'sufficient_savings' => 'required|string',
            'sole_responsibility' => 'required|string',
            'recovery_process' => 'required|string',
            'voluntary_guarantee' => 'required|string',
            'additional_comments' => 'nullable|string',
        ]);

        try {
            // Create guarantor assessment record
            $assessment = GuarantorAssessment::create([
                'loan_id' => $loan->id,
                'guarantor_id' => $validated['guarantor_id'],
                'member_code' => $validated['member_code'],
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'relationship' => $validated['relationship'],
                'relationship_other' => $validated['relationship_other'],
                'address' => $validated['address'],
                'occupation' => $validated['occupation'],
                'monthly_income' => $validated['monthly_income'],
                'loan_purpose' => $validated['loan_purpose'],
                'loan_purpose_other' => $validated['loan_purpose_other'],
                'repayment_history' => $validated['repayment_history'],
                'existing_debts' => $validated['existing_debts'],
                'sufficient_savings' => $validated['sufficient_savings'],
                'sole_responsibility' => $validated['sole_responsibility'],
                'recovery_process' => $validated['recovery_process'],
                'voluntary_guarantee' => $validated['voluntary_guarantee'],
                'additional_comments' => $validated['additional_comments'],
                'assessment_date' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send confirmation email to guarantor
            try {
                Mail::to($validated['email'])->send(new GuaranteeAgreementMail($assessment, $loan));
            } catch (\Exception $e) {
                Log::error('Failed to send guarantor email', [
                    'assessment_id' => $assessment->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Send notification to borrower
            try {
                if ($loan->user && $loan->user->email) {
                    Mail::to($loan->user->email)->send(new GuarantorSubmittedNotification($assessment, $loan));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send borrower notification', [
                    'assessment_id' => $assessment->id,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Assessment submitted successfully',
                'assessment_id' => $assessment->id
            ]);

        } catch (\Exception $e) {
            Log::error('Guarantor assessment submission error', [
                'loan_ulid' => $loanUlid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit assessment'
            ], 500);
        }
    }

    /**
     * Show success page.
     */
    public function success($assessmentUlid)
    {
        $assessment = GuarantorAssessment::where('ulid', $assessmentUlid)->firstOrFail();
        return view('guarantor-assessment.success', compact('assessment'));
    }

    /**
     * Admin: List all assessments.
     */
    public function index()
    {
        $assessments = GuarantorAssessment::with(['loan.user', 'guarantor'])
            ->latest()
            ->paginate(20);

        return view('admin.guarantor-assessments.index', compact('assessments'));
    }

    /**
     * Admin: Show assessment details.
     */
    public function showAdmin(GuarantorAssessment $assessment)
    {
        $assessment->load(['loan.user', 'guarantor']);
        return view('admin.guarantor-assessments.show', compact('assessment'));
    }

    /**
     * Admin: Approve assessment.
     */
    public function approve(GuarantorAssessment $assessment)
    {
        $assessment->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Guarantor assessment approved.');
    }

    /**
     * Admin: Request clarification.
     */
    public function requestClarification(GuarantorAssessment $assessment)
    {
        $assessment->update(['status' => 'clarification_needed']);
        return redirect()->back()->with('info', 'Clarification requested from guarantor.');
    }

    /**
     * Admin: Download assessment as PDF.
     */
    public function downloadPdf(GuarantorAssessment $assessment)
    {
        $assessment->load(['loan.user', 'guarantor']);
        
        $pdf = Pdf::loadView('pdfs.guarantee-agreement', compact('assessment'));
        
        return $pdf->download('Guarantee_Agreement_' . $assessment->ulid . '.pdf');
    }
}
