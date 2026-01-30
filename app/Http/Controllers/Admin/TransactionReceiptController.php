<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\PdfHelper;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionReceiptController extends Controller
{
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'processor', 'related']);

        // Get organization info
        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        // Determine transaction type label
        $transactionTypeLabel = $this->getTransactionTypeLabel($transaction->transaction_type);

        // Get related entity details
        $relatedDetails = $this->getRelatedDetails($transaction);

        return PdfHelper::downloadPdf('admin.transactions.receipt', [
            'transaction' => $transaction,
            'organizationInfo' => $organizationInfo,
            'transactionTypeLabel' => $transactionTypeLabel,
            'relatedDetails' => $relatedDetails,
            'documentTitle' => 'Transaction Receipt',
            'documentSubtitle' => $transactionTypeLabel,
        ], 'receipt-'.$transaction->transaction_number.'-'.date('Y-m-d-His').'.pdf');
    }

    protected function getTransactionTypeLabel(string $type): string
    {
        return match($type) {
            'loan_payment' => 'Loan Payment Receipt',
            'loan_disbursement' => 'Loan Disbursement Receipt',
            'savings_deposit' => 'Savings Deposit Receipt',
            'savings_withdrawal' => 'Savings Withdrawal Receipt',
            'investment_deposit' => 'Investment Deposit Receipt',
            'investment_disbursement' => 'Investment Disbursement Receipt',
            'welfare_contribution' => 'Welfare Contribution Receipt',
            'welfare_benefit' => 'Welfare Benefit Receipt',
            default => 'Transaction Receipt',
        };
    }

    protected function getRelatedDetails(Transaction $transaction): array
    {
        $details = [];

        if ($transaction->related) {
            switch ($transaction->related_type) {
                case 'loan':
                    $details = [
                        'type' => 'Loan',
                        'number' => $transaction->related->loan_number ?? 'N/A',
                        'principal' => number_format($transaction->related->principal_amount ?? 0, 2),
                        'remaining' => number_format($transaction->related->remaining_amount ?? 0, 2),
                        'status' => ucfirst($transaction->related->status ?? 'N/A'),
                    ];
                    break;

                case 'savings_account':
                    $details = [
                        'type' => 'Savings Account',
                        'number' => $transaction->related->account_number ?? 'N/A',
                        'account_type' => ucfirst($transaction->related->account_type ?? 'N/A'),
                        'balance' => number_format($transaction->related->balance ?? 0, 2),
                        'status' => ucfirst($transaction->related->status ?? 'N/A'),
                    ];
                    break;

                case 'investment':
                    $details = [
                        'type' => 'Investment',
                        'number' => $transaction->related->investment_number ?? 'N/A',
                        'plan_type' => $transaction->related->plan_type_name ?? ucfirst($transaction->related->plan_type ?? 'N/A'),
                        'principal' => number_format($transaction->related->principal_amount ?? 0, 2),
                        'status' => ucfirst($transaction->related->status ?? 'N/A'),
                    ];
                    break;

                case 'social_welfare':
                    $details = [
                        'type' => 'Social Welfare',
                        'number' => $transaction->related->welfare_number ?? 'N/A',
                        'welfare_type' => ucfirst($transaction->related->type ?? 'N/A'),
                        'benefit_type' => $transaction->related->benefit_type_name ?? 'N/A',
                        'status' => ucfirst($transaction->related->status ?? 'N/A'),
                    ];
                    break;
            }
        }

        return $details;
    }
}

