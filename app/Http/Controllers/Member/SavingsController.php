<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SavingsAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SavingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $accounts = SavingsAccount::where('user_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total_accounts' => $accounts->count(),
            'total_balance' => $accounts->sum('balance'),
            'by_type' => $accounts->groupBy('account_type')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'balance' => $group->sum('balance'),
                ];
            }),
        ];

        return view('member.savings.index', compact('accounts', 'stats'));
    }

    public function show(SavingsAccount $saving)
    {
        if ($saving->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $saving->load(['user']);
        $transactions = Transaction::where('related_type', 'savings_account')
            ->where('related_id', $saving->id)
            ->latest()
            ->get();

        return view('member.savings.show', compact('saving', 'transactions'));
    }

    public function create()
    {
        return view('member.savings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:emergency,rda,flex,business',
            'opening_date' => 'required|date',
            'maturity_date' => 'nullable|date|required_if:account_type,rda',
        ]);

        SavingsAccount::create([
            'user_id' => Auth::id(),
            'account_number' => 'SAV-'.strtoupper(Str::random(8)),
            'account_type' => $validated['account_type'],
            'balance' => 0,
            'interest_rate' => 0,
            'minimum_balance' => 0,
            'opening_date' => $validated['opening_date'],
            'maturity_date' => $validated['maturity_date'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('member.savings.index')->with('success', 'Savings account created successfully.');
    }
}
