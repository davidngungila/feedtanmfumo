<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\SavingsAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $savings = SavingsAccount::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($account) {
                return [
                    'id' => $account->id,
                    'account_number' => $account->account_number,
                    'account_type' => $account->account_type,
                    'balance' => (float) $account->balance,
                    'status' => $account->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $savings,
        ]);
    }

    public function show(SavingsAccount $savings)
    {
        if ($savings->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $savings->id,
                'account_number' => $savings->account_number,
                'account_type' => $savings->account_type,
                'balance' => (float) $savings->balance,
                'status' => $savings->status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        // TODO: Implement savings account creation
        return response()->json([
            'success' => true,
            'message' => 'Savings account created successfully',
        ], 201);
    }

    public function deposit(Request $request, SavingsAccount $savings)
    {
        if ($savings->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mobile_money,bank_transfer',
            'reference_number' => 'nullable|string|max:255',
        ]);

        // TODO: Implement deposit logic
        return response()->json([
            'success' => true,
            'message' => 'Deposit processed successfully',
        ]);
    }

    public function withdraw(Request $request, SavingsAccount $savings)
    {
        if ($savings->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mobile_money,bank_transfer',
        ]);

        // TODO: Implement withdrawal logic
        return response()->json([
            'success' => true,
            'message' => 'Withdrawal processed successfully',
        ]);
    }

    public function statements(SavingsAccount $savings)
    {
        if ($savings->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $transactions = $savings->transactions()
            ->latest()
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->transaction_type,
                    'amount' => (float) $transaction->amount,
                    'date' => $transaction->transaction_date->format('Y-m-d'),
                    'status' => $transaction->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }
}
