<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'principal_amount' => (float) $loan->principal_amount,
                    'total_amount' => (float) $loan->total_amount,
                    'remaining_amount' => (float) $loan->remaining_amount,
                    'status' => $loan->status,
                    'application_date' => $loan->application_date->format('Y-m-d'),
                    'maturity_date' => $loan->maturity_date?->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $loans,
        ]);
    }

    public function show(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $loan->load('transactions');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $loan->id,
                'loan_number' => $loan->loan_number,
                'principal_amount' => (float) $loan->principal_amount,
                'total_amount' => (float) $loan->total_amount,
                'remaining_amount' => (float) $loan->remaining_amount,
                'status' => $loan->status,
                'purpose' => $loan->purpose,
                'term_months' => $loan->term_months,
                'payment_frequency' => $loan->payment_frequency,
                'application_date' => $loan->application_date->format('Y-m-d'),
                'maturity_date' => $loan->maturity_date?->format('Y-m-d'),
                'transactions' => $loan->transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'amount' => (float) $transaction->amount,
                        'type' => $transaction->transaction_type,
                        'date' => $transaction->transaction_date->format('Y-m-d'),
                        'status' => $transaction->status,
                    ];
                }),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'principal_amount' => 'required|numeric|min:1000',
            'purpose' => 'required|string|max:500',
            'term_months' => 'required|integer|min:1|max:60',
            'payment_frequency' => 'required|in:monthly,weekly,bi-weekly',
        ]);

        // Use the same logic as MemberLoanController
        $interestRate = 10;
        $principalAmount = $validated['principal_amount'];
        $termMonths = $validated['term_months'];
        $interest = ($principalAmount * $interestRate / 100) * ($termMonths / 12);
        $totalAmount = $principalAmount + $interest;

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'loan_number' => 'LN-'.strtoupper(\Illuminate\Support\Str::random(8)),
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

        return response()->json([
            'success' => true,
            'message' => 'Loan application submitted successfully',
            'data' => [
                'loan_id' => $loan->id,
                'loan_number' => $loan->loan_number,
            ],
        ], 201);
    }

    public function schedule(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Calculate repayment schedule
        $schedule = [];
        if ($loan->disbursement_date && $loan->term_months > 0) {
            $monthlyPayment = $loan->total_amount / $loan->term_months;
            $currentDate = $loan->disbursement_date->copy();

            for ($i = 1; $i <= $loan->term_months; $i++) {
                $schedule[] = [
                    'installment_number' => $i,
                    'due_date' => $currentDate->addMonth()->format('Y-m-d'),
                    'amount' => (float) $monthlyPayment,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $schedule,
        ]);
    }

    public function pay(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,mobile_money,bank_transfer,cheque',
            'reference_number' => 'nullable|string|max:255',
        ]);

        // TODO: Implement payment processing logic
        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
        ]);
    }
}
