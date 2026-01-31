<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Helpers\NotificationHelper;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('user');
        
        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Loan::count(),
            'pending' => Loan::where('status', 'pending')->count(),
            'active' => Loan::where('status', 'active')->count(),
            'overdue' => Loan::where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($query) {
                      $query->where('status', 'active')
                            ->where('maturity_date', '<', now());
                  });
            })->count(),
            'total_amount' => Loan::where('status', 'active')->sum('remaining_amount') ?? 0,
            'total_principal' => Loan::sum('principal_amount') ?? 0,
            'total_paid' => Loan::sum('paid_amount') ?? 0,
        ];
        
        return view('admin.loans.index', compact('loans', 'stats'));
    }

    public function create()
    {
        // Get all users who are not admins (members)
        $users = User::where('role', '!=', 'admin')
            ->whereDoesntHave('roles', function($q) {
                $q->where('slug', 'admin');
            })
            ->orderBy('name')
            ->get();
        
        // Fallback: if no users found, get all users except those with admin role
        if ($users->isEmpty()) {
            $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        }
        
        return view('admin.loans.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
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
        $interest = ($principal * $validated['interest_rate'] / 100) * ($validated['term_months'] / 12);
        $total = $principal + $interest;

        // Combine purpose category with description if provided
        $purpose = $validated['purpose'];
        if ($request->has('loan_purpose_category') && !empty($request->loan_purpose_category)) {
            $purpose = $request->loan_purpose_category . ': ' . $purpose;
        }

        // Handle file uploads
        $loanData = [
            'user_id' => $validated['user_id'],
            'loan_number' => 'LN-' . strtoupper(Str::random(8)),
            'principal_amount' => $principal,
            'interest_rate' => $validated['interest_rate'],
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
            route('admin.loans.show', $loan)
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

        return redirect()->route('admin.loans.show', $loan)->with('success', 'Loan application submitted successfully! Notifications have been sent.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['user', 'approver', 'transactions']);
        return view('admin.loans.show', compact('loan'));
    }

    public function exportPdf(Loan $loan)
    {
        $loan->load(['user', 'guarantor', 'approver', 'transactions']);
        
        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        return \App\Helpers\PdfHelper::downloadPdf('admin.loans.pdf', [
            'loan' => $loan,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Loan Application Document',
            'documentSubtitle' => 'Loan Number: ' . $loan->loan_number,
        ], 'loan-'.$loan->loan_number.'-'.date('Y-m-d-His').'.pdf');
    }

    public function edit(Loan $loan)
    {
        $users = User::where('role', 'user')->get();
        return view('admin.loans.edit', compact('loan', 'users'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1',
            'payment_frequency' => 'required|in:weekly,bi-weekly,monthly',
            'application_date' => 'required|date',
            'status' => 'required|in:pending,approved,disbursed,active,completed,overdue,defaulted,rejected',
            'approval_date' => 'nullable|date',
            'disbursement_date' => 'nullable|date',
            'purpose' => 'nullable|string',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
        ]);

        // Recalculate total amount if principal, interest rate, or term changed
        $principal = $validated['principal_amount'];
        $interest = ($principal * $validated['interest_rate'] / 100) * ($validated['term_months'] / 12);
        $total = $principal + $interest;

        // Calculate remaining amount (total - paid)
        $remaining = $total - $loan->paid_amount;

        $validated['total_amount'] = $total;
        $validated['remaining_amount'] = max(0, $remaining);

        // Auto-set approval date and approver if status changed to approved
        if ($validated['status'] === 'approved' && !$loan->approval_date) {
            $validated['approval_date'] = $validated['approval_date'] ?? now();
            $validated['approved_by'] = auth()->id();
        }

        // Auto-set disbursement date and calculate maturity if status changed to disbursed
        if ($validated['status'] === 'disbursed' && !$loan->disbursement_date) {
            $validated['disbursement_date'] = $validated['disbursement_date'] ?? now();
            $validated['status'] = 'active';
            if ($validated['term_months']) {
                $disbursementDate = Carbon::parse($validated['disbursement_date']);
                $validated['maturity_date'] = $disbursementDate->copy()->addMonths($validated['term_months']);
            }
        }

        // Calculate maturity date if term_months changed and loan is active
        if ($loan->status === 'active' && $validated['term_months'] != $loan->term_months && $loan->disbursement_date) {
            $disbursementDate = Carbon::parse($loan->disbursement_date);
            $validated['maturity_date'] = $disbursementDate->copy()->addMonths($validated['term_months']);
        }

        $loan->update($validated);

        return redirect()->route('admin.loans.show', $loan)->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        if ($loan->status === 'active') {
            return redirect()->back()->with('error', 'Cannot delete an active loan.');
        }
        $loan->delete();
        return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully.');
    }

    public function activeLoans(Request $request)
    {
        $query = Loan::with('user')->where('status', 'active');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'total_amount' => Loan::where('status', 'active')->sum('total_amount') ?? 0,
            'total_paid' => Loan::where('status', 'active')->sum('paid_amount') ?? 0,
            'total_remaining' => Loan::where('status', 'active')->sum('remaining_amount') ?? 0,
            'avg_interest_rate' => Loan::where('status', 'active')->avg('interest_rate') ?? 0,
        ];
        
        return view('admin.loans.active', compact('loans', 'stats'));
    }

    public function portfolio(Request $request)
    {
        $loans = Loan::with('user')->whereIn('status', ['active', 'overdue'])->latest()->paginate(20);
        
        $stats = [
            'total_loans' => Loan::whereIn('status', ['active', 'overdue'])->count(),
            'total_portfolio_value' => Loan::whereIn('status', ['active', 'overdue'])->sum('total_amount') ?? 0,
            'total_outstanding' => Loan::whereIn('status', ['active', 'overdue'])->sum('remaining_amount') ?? 0,
            'total_recovered' => Loan::whereIn('status', ['active', 'overdue'])->sum('paid_amount') ?? 0,
            'recovery_rate' => 0,
        ];
        
        if ($stats['total_portfolio_value'] > 0) {
            $stats['recovery_rate'] = round(($stats['total_recovered'] / $stats['total_portfolio_value']) * 100, 2);
        }
        
        // Portfolio by status
        $portfolioByStatus = [
            'active' => Loan::where('status', 'active')->sum('remaining_amount') ?? 0,
            'overdue' => Loan::where('status', 'overdue')->sum('remaining_amount') ?? 0,
        ];
        
        return view('admin.loans.portfolio', compact('loans', 'stats', 'portfolioByStatus'));
    }

    public function repaymentSchedule(Request $request)
    {
        $loanId = $request->get('loan_id');
        $loan = null;
        $schedule = [];
        
        if ($loanId) {
            $loan = Loan::with('user')->findOrFail($loanId);
            
            // Generate repayment schedule
            if ($loan->status === 'active' && $loan->term_months > 0) {
                $monthlyPayment = $loan->total_amount / $loan->term_months;
                $startDate = $loan->disbursement_date ?? $loan->application_date;
                
                for ($i = 1; $i <= $loan->term_months; $i++) {
                    $dueDate = Carbon::parse($startDate)->addMonths($i);
                    $schedule[] = [
                        'installment' => $i,
                        'due_date' => $dueDate,
                        'amount' => $monthlyPayment,
                        'status' => $dueDate->isPast() ? ($loan->paid_amount >= ($monthlyPayment * $i) ? 'paid' : 'overdue') : 'pending',
                    ];
                }
            }
        }
        
        $activeLoans = Loan::with('user')->where('status', 'active')->orderBy('loan_number')->get();
        
        return view('admin.loans.repayment-schedule', compact('loan', 'schedule', 'activeLoans'));
    }

    public function duePayments(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Get loans with payments due on or before the selected date
        $loans = Loan::with('user')
            ->where('status', 'active')
            ->where(function($q) use ($selectedDate) {
                $q->where('maturity_date', '<=', $selectedDate)
                  ->orWhere(function($query) use ($selectedDate) {
                      // Calculate if any installment is due
                      $query->whereNotNull('disbursement_date')
                            ->whereRaw('DATE_ADD(disbursement_date, INTERVAL FLOOR(DATEDIFF(?, disbursement_date) / 30) MONTH) <= ?', [$selectedDate, $selectedDate]);
                  });
            })
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_due' => $loans->total(),
            'total_amount_due' => 0,
            'date' => $selectedDate,
        ];
        
        foreach ($loans as $loan) {
            if ($loan->term_months > 0) {
                $monthlyPayment = $loan->total_amount / $loan->term_months;
                $stats['total_amount_due'] += $monthlyPayment;
            }
        }
        
        return view('admin.loans.due-payments', compact('loans', 'stats'));
    }

    public function overdueLoans(Request $request)
    {
        $query = Loan::with('user')
            ->where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($query) {
                      $query->where('status', 'active')
                            ->where('maturity_date', '<', now());
                  });
            });
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'total_overdue_amount' => Loan::where(function($q) {
                $q->where('status', 'overdue')
                  ->orWhere(function($query) {
                      $query->where('status', 'active')
                            ->where('maturity_date', '<', now());
                  });
            })->sum('remaining_amount') ?? 0,
            'avg_days_overdue' => 0,
        ];
        
        $totalDays = 0;
        $count = 0;
        foreach ($loans as $loan) {
            if ($loan->maturity_date && $loan->maturity_date->isPast()) {
                $totalDays += $loan->maturity_date->diffInDays(now());
                $count++;
            }
        }
        if ($count > 0) {
            $stats['avg_days_overdue'] = round($totalDays / $count, 1);
        }
        
        return view('admin.loans.overdue', compact('loans', 'stats'));
    }

    public function restructuring(Request $request)
    {
        $query = Loan::with('user')->where('status', 'active');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->where('remaining_amount', '>', 0)->latest()->paginate(20);
        
        $stats = [
            'total_eligible' => Loan::where('status', 'active')->where('remaining_amount', '>', 0)->count(),
            'total_outstanding' => Loan::where('status', 'active')->sum('remaining_amount') ?? 0,
        ];
        
        return view('admin.loans.restructuring', compact('loans', 'stats'));
    }

    public function pendingApprovals(Request $request)
    {
        $query = Loan::with('user')->where('status', 'pending');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'total_amount' => Loan::where('status', 'pending')->sum('total_amount') ?? 0,
            'avg_amount' => Loan::where('status', 'pending')->avg('total_amount') ?? 0,
        ];
        
        return view('admin.loans.pending-approvals', compact('loans', 'stats'));
    }

    public function creditAssessment(Request $request)
    {
        $query = Loan::with('user')->where('status', 'pending');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'high_risk' => Loan::where('status', 'pending')->where('total_amount', '>', 1000000)->count(),
            'medium_risk' => Loan::where('status', 'pending')->whereBetween('total_amount', [500000, 1000000])->count(),
            'low_risk' => Loan::where('status', 'pending')->where('total_amount', '<', 500000)->count(),
        ];
        
        return view('admin.loans.credit-assessment', compact('loans', 'stats'));
    }

    public function committeeReview(Request $request)
    {
        $query = Loan::with('user')->where('status', 'pending')
            ->where('total_amount', '>', 500000); // Loans requiring committee review
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'total_amount' => $query->sum('total_amount') ?? 0,
            'awaiting_review' => $loans->count(),
        ];
        
        return view('admin.loans.committee-review', compact('loans', 'stats'));
    }

    public function approvalWorkflow(Request $request)
    {
        $loans = Loan::with('user')
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'pending' => Loan::where('status', 'pending')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'in_workflow' => Loan::whereIn('status', ['pending', 'approved'])->count(),
        ];
        
        return view('admin.loans.approval-workflow', compact('loans', 'stats'));
    }

    public function disbursement(Request $request)
    {
        $query = Loan::with('user')->where('status', 'approved');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $loans = $query->latest()->paginate(20);
        
        $stats = [
            'total' => $loans->total(),
            'total_amount' => Loan::where('status', 'approved')->sum('total_amount') ?? 0,
            'ready_for_disbursement' => Loan::where('status', 'approved')->whereNotNull('approval_date')->count(),
        ];
        
        return view('admin.loans.disbursement', compact('loans', 'stats'));
    }
}
