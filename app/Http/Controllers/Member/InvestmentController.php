<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $investments = Investment::where('user_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total' => $investments->count(),
            'active' => $investments->where('status', 'active')->count(),
            'matured' => $investments->where('status', 'matured')->count(),
            'total_principal' => $investments->sum('principal_amount'),
            'total_profit' => $investments->sum('profit_share'),
            'expected_return' => $investments->sum('expected_return'),
        ];

        return view('member.investments.index', compact('investments', 'stats'));
    }

    public function show(Investment $investment)
    {
        if ($investment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $investment->load(['user']);
        $transactions = Transaction::where('related_type', 'investment')
            ->where('related_id', $investment->id)
            ->latest()
            ->get();

        return view('member.investments.show', compact('investment', 'transactions'));
    }

    public function create()
    {
        return view('member.investments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_type' => 'required|in:4_year,6_year',
            'principal_amount' => 'required|numeric|min:1000',
            'start_date' => 'required|date',
        ]);

        $principal = $validated['principal_amount'];
        $years = $validated['plan_type'] === '4_year' ? 4 : 6;
        $interestRate = 12; // Default interest rate
        $interest = ($principal * $interestRate / 100) * $years;
        $expectedReturn = $principal + $interest;

        Investment::create([
            'user_id' => Auth::id(),
            'investment_number' => 'INV-'.strtoupper(Str::random(8)),
            'plan_type' => $validated['plan_type'],
            'principal_amount' => $principal,
            'interest_rate' => $interestRate,
            'expected_return' => $expectedReturn,
            'profit_share' => 0,
            'start_date' => $validated['start_date'],
            'maturity_date' => now()->parse($validated['start_date'])->addYears($years),
            'status' => 'active',
        ]);

        return redirect()->route('member.investments.index')->with('success', 'Investment created successfully.');
    }
}
