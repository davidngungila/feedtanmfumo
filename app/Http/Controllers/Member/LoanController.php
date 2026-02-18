<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Helpers\NotificationHelper;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)
            ->with('approver')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Loan::where('user_id', $user->id)->count(),
            'active' => Loan::where('user_id', $user->id)->where('status', 'active')->count(),
            'pending' => Loan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_amount' => Loan::where('user_id', $user->id)->sum('principal_amount'),
            'paid_amount' => Loan::where('user_id', $user->id)->sum('paid_amount'),
            'remaining_amount' => Loan::where('user_id', $user->id)->sum('remaining_amount'),
        ];

        return view('member.loans.index', compact('loans', 'stats'));
    }

    public function show(Loan $loan)
    {
        // Ensure user can only view their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $loan->load(['user', 'approver', 'transactions']);
        $transactions = Transaction::where('related_type', 'loan')
            ->where('related_id', $loan->id)
            ->latest()
            ->get();

        return view('member.loans.show', compact('loan', 'transactions'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Get members with membership codes for guarantor selection (exclude current user)
        $members = User::where('role', '!=', 'admin')
            ->whereNotNull('membership_code')
            ->where('id', '!=', $user->id)
            ->whereDoesntHave('roles', function($q) {
                $q->where('slug', 'admin');
            })
            ->orderBy('name')
            ->get();
        
        return view('member.loans.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'principal_amount' => 'required|numeric|min:1000',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1|max:120',
            'payment_frequency' => 'required|in:weekly,bi-weekly,monthly',
            'purpose' => 'required|string|min:10',
            'application_date' => 'required|date',
            'terms_accepted' => 'required|accepted',
            // Additional fields
            'loan_type' => 'nullable|string|max:255',
            'collateral_description' => 'nullable|string',
            'collateral_value' => 'nullable|numeric|min:0',
            'guarantor_id' => 'nullable|exists:users,id',
            'business_plan' => 'nullable|string',
            'repayment_source' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            // Document uploads
            'application_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'id_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'proof_of_income' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'collateral_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'guarantor_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $principal = $validated['principal_amount'];
        $interestRate = $validated['interest_rate'] ?? 10; // Default to 10% if not provided
        $interest = ($principal * $interestRate / 100) * ($validated['term_months'] / 12);
        $total = $principal + $interest;

        // Combine purpose category with description if provided
        $purpose = $validated['purpose'];
        if ($request->has('loan_purpose_category') && !empty($request->loan_purpose_category)) {
            $purpose = $request->loan_purpose_category . ': ' . $purpose;
        }

        // Handle file uploads
        $loanData = [
            'user_id' => Auth::id(),
            'loan_number' => 'LN-' . strtoupper(Str::random(8)),
            'principal_amount' => $principal,
            'interest_rate' => $interestRate,
            'total_amount' => $total,
            'paid_amount' => 0,
            'remaining_amount' => $total,
            'term_months' => $validated['term_months'],
            'application_date' => $validated['application_date'],
            'status' => 'pending',
            'payment_frequency' => $validated['payment_frequency'],
            'purpose' => $purpose,
            'loan_type' => $validated['loan_type'] ?? null,
            'collateral_description' => $validated['collateral_description'] ?? null,
            'collateral_value' => $validated['collateral_value'] ?? null,
            'guarantor_id' => $validated['guarantor_id'] ?? null,
            'business_plan' => $validated['business_plan'] ?? null,
            'repayment_source' => $validated['repayment_source'] ?? null,
            'additional_notes' => $validated['additional_notes'] ?? null,
        ];

        // Upload application document
        if ($request->hasFile('application_document')) {
            $loanData['application_document'] = $request->file('application_document')->store('loans/documents', 'public');
        }

        // Upload supporting documents (multiple files)
        if ($request->hasFile('supporting_documents')) {
            $supportingDocs = [];
            foreach ($request->file('supporting_documents') as $file) {
                $supportingDocs[] = $file->store('loans/documents', 'public');
            }
            $loanData['supporting_documents'] = $supportingDocs;
        }

        // Upload ID document
        if ($request->hasFile('id_document')) {
            $loanData['id_document'] = $request->file('id_document')->store('loans/documents', 'public');
        }

        // Upload proof of income
        if ($request->hasFile('proof_of_income')) {
            $loanData['proof_of_income'] = $request->file('proof_of_income')->store('loans/documents', 'public');
        }

        // Upload collateral document
        if ($request->hasFile('collateral_document')) {
            $loanData['collateral_document'] = $request->file('collateral_document')->store('loans/documents', 'public');
        }

        // Upload guarantor document
        if ($request->hasFile('guarantor_document')) {
            $loanData['guarantor_document'] = $request->file('guarantor_document')->store('loans/documents', 'public');
        }

        $loan = Loan::create($loanData);
        $loan->load(['user', 'guarantor']);

        // Send notification to applicant
        NotificationHelper::create(
            $loan->user,
            'loan',
            'Loan Application Submitted',
            "Your loan application (Loan Number: {$loan->loan_number}) has been submitted successfully and is pending approval.",
            'document',
            'blue',
            route('member.loans.show', $loan)
        );

        // Send notification to admins
        NotificationHelper::createForAdmins(
            'loan',
            'New Loan Application',
            "A new loan application has been submitted by {$loan->user->name} (Loan Number: {$loan->loan_number}).",
            'bell',
            'green',
            route('admin.loans.show', $loan)
        );

        // Send email notification to applicant
        try {
            $emailService = app(EmailNotificationService::class);
            $emailService->sendLoanApplicationNotification($loan->user, [
                'loan_number' => $loan->loan_number,
                'principal_amount' => $loan->principal_amount,
                'interest_rate' => $loan->interest_rate,
                'term_months' => $loan->term_months,
                'total_amount' => $loan->total_amount,
                'purpose' => $loan->purpose,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send loan application email: '.$e->getMessage());
        }

        return redirect()->route('member.loans.show', $loan)->with('success', 'Loan application submitted successfully! Notifications have been sent.');
    }
}
