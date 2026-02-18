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
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $principal = $validated['principal_amount'];
        $is4Year = $validated['plan_type'] === '4_year';
        
        $years = $is4Year ? 4 : 6;
        $interestRate = $is4Year ? 8.6 : 10.0;
        $unitPrice = $is4Year ? 110.0 : 120.0;
        
        // Calculate units bought (as per user formula: principal * unitPrice/100)
        $units = $principal * ($unitPrice / 100);
        $totalInterest = $units * ($interestRate / 100) * $years;
        // Expected return is units bought + total interest
        $expectedReturn = $units + $totalInterest;

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('investments/receipts', 'public');
        }

        Investment::create([
            'user_id' => Auth::id(),
            'investment_number' => 'INV-'.strtoupper(Str::random(8)),
            'investment_name' => $is4Year ? '8.6% Four-years FIA' : '10% Six-years FIA',
            'plan_type' => $validated['plan_type'],
            'principal_amount' => $principal,
            'interest_rate' => $interestRate,
            'unit_price' => $unitPrice,
            'expected_return' => $expectedReturn,
            'profit_share' => 0,
            'start_date' => now(),
            'maturity_date' => now()->addYears($years),
            'payment_receipt' => $receiptPath,
            'status' => 'pending',
        ]);

        return redirect()->route('member.investments.index')->with('success', 'Uwekezaji wako umewasilishwa na unasubiri kuhakikiwa. Tafadhali subiri uidhinishaji.');
    }
}
