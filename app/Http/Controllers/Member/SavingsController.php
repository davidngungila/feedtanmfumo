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
            'initial_deposit' => 'required|numeric|min:0',
            'payment_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'maturity_date' => 'nullable|date|required_if:account_type,rda',
        ]);

        $type = $validated['account_type'];
        $amount = $validated['initial_deposit'];
        $interestRate = 0;

        switch ($type) {
            case 'business':
                $interestRate = 0.0296 * 100;
                break;
            case 'flex':
                $interestRate = 0.0537 * 100;
                break;
            case 'emergency':
                $interestRate = 0;
                break;
            case 'rda':
                if ($amount > 299000) {
                    $interestRate = 0.0678 * 100;
                } elseif ($amount >= 200000) {
                    $interestRate = 0.063 * 100;
                } elseif ($amount >= 100000) {
                    $interestRate = 0.0584 * 100;
                } else {
                    $interestRate = 0;
                }
                break;
        }

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('savings/receipts', 'public');
        }

        SavingsAccount::create([
            'user_id' => Auth::id(),
            'account_number' => 'SAV-'.strtoupper(Str::random(8)),
            'account_type' => $type,
            'balance' => $amount,
            'interest_rate' => $interestRate,
            'minimum_balance' => 0,
            'opening_date' => $validated['opening_date'],
            'maturity_date' => $validated['maturity_date'] ?? null,
            'payment_receipt' => $receiptPath,
            'status' => 'pending',
        ]);

        return redirect()->route('member.savings.index')->with('success', 'Akaunti yako ya akiba imefunguliwa na inasubiri kuhakikiwa kwa amana ya kwanza.');
    }
}
