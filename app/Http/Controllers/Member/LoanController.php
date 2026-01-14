<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Transaction;
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
        return view('member.loans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'principal_amount' => 'required|numeric|min:1000',
            'purpose' => 'required|string|max:500',
            'term_months' => 'required|integer|min:1|max:60',
            'payment_frequency' => 'required|in:monthly,weekly,bi-weekly',
        ]);

        $interestRate = 10; // Default interest rate
        $principalAmount = $validated['principal_amount'];
        $termMonths = $validated['term_months'];

        // Calculate total amount with interest
        $interest = ($principalAmount * $interestRate / 100) * ($termMonths / 12);
        $totalAmount = $principalAmount + $interest;

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'loan_number' => 'LN-'.strtoupper(Str::random(8)),
            'principal_amount' => $principalAmount,
            'interest_rate' => $interestRate,
            'total_amount' => $totalAmount,
            'remaining_amount' => $totalAmount,
            'term_months' => $termMonths,
            'payment_frequency' => $validated['payment_frequency'],
            'purpose' => $validated['purpose'],
            'application_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('member.loans.index')->with('success', 'Loan application submitted successfully.');
    }
}
