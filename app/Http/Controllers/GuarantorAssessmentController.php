<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\GuarantorAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuaranteeAgreementMail;
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
     * Store the assessment response.
     */
    public function store(Request $request, $loanUlid)
    {
        $loan = Loan::where('ulid', $loanUlid)->firstOrFail();

        $validated = $request->validate([
            'guarantor_id' => 'required|exists:users,id',
            'relationship' => 'required|string',
            'relationship_other' => 'required_if:relationship,Other|nullable|string',
            'loan_purpose' => 'required|string',
            'loan_purpose_other' => 'required_if:loan_purpose,Other|nullable|string',
            'reviewed_history' => 'required|string',
            'other_debts' => 'required|string',
            'sufficient_savings' => 'required|string',
            'financial_obligation_impact' => 'required|string',
            'other_guarantees' => 'required|string',
            'solely_responsible_understanding' => 'required|string',
            'recovery_mechanism_understanding' => 'required|string',
            'borrower_backup_plan' => 'required|string',
            'guarantor_backup_plan' => 'required|string',
            'final_declaration' => 'required|accepted',
            'additional_comments' => 'nullable|string',
        ]);

        $assessment = GuarantorAssessment::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $validated['guarantor_id'],
            'borrower_name' => $loan->user->name,
            'relationship' => $validated['relationship'],
            'relationship_other' => $validated['relationship_other'],
            'loan_purpose' => $validated['loan_purpose'],
            'loan_purpose_other' => $validated['loan_purpose_other'],
            'reviewed_history' => $validated['reviewed_history'],
            'other_debts' => $validated['other_debts'],
            'sufficient_savings' => $validated['sufficient_savings'],
            'financial_obligation_impact' => $validated['financial_obligation_impact'],
            'other_guarantees' => $validated['other_guarantees'],
            'solely_responsible_understanding' => $validated['solely_responsible_understanding'],
            'recovery_mechanism_understanding' => $validated['recovery_mechanism_understanding'],
            'borrower_backup_plan' => $validated['borrower_backup_plan'],
            'guarantor_backup_plan' => $validated['guarantor_backup_plan'],
            'final_declaration' => true,
            'additional_comments' => $validated['additional_comments'],
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Update the loan with the guarantor if not already set
        if (!$loan->guarantor_id) {
            $loan->update(['guarantor_id' => $validated['guarantor_id']]);
        }

        // Send automated email with Guarantee Agreement
        try {
            $guarantor = User::find($validated['guarantor_id']);
            Mail::to($guarantor->email)
                ->cc($loan->user->email)
                ->send(new GuaranteeAgreementMail($assessment));
        } catch (\Exception $e) {
            \Log::error('Failed to send Guarantee Agreement email: ' . $e->getMessage());
        }

        return redirect()->route('guarantor-assessment.success', $assessment->ulid);
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
