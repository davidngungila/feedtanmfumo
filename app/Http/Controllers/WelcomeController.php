<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Issue;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\SocialWelfare;
use App\Models\Transaction;
use App\Models\User;

class WelcomeController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_members' => User::where('role', 'user')->orWhereHas('roles', function ($q) {
                $q->where('slug', '!=', 'admin');
            })->count(),
            'total_loans' => Loan::count(),
            'active_loans' => Loan::whereIn('status', ['active', 'disbursed'])->count(),
            'total_loan_amount' => Loan::where('status', '!=', 'rejected')->sum('total_amount') ?? 0,
            'total_savings_balance' => SavingsAccount::sum('balance') ?? 0,
            'total_savings_accounts' => SavingsAccount::count(),
            'total_investments' => Investment::count(),
            'total_investment_amount' => Investment::sum('principal_amount') ?? 0,
            'total_transactions' => Transaction::count(),
            'total_issues' => Issue::count(),
            'resolved_issues' => Issue::where('status', 'resolved')->count(),
        ];

        // Loan Statistics
        $loanStats = [
            'pending' => Loan::where('status', 'pending')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'active' => Loan::where('status', 'active')->count(),
            'completed' => Loan::where('status', 'completed')->count(),
            'total_disbursed' => Loan::whereIn('status', ['active', 'disbursed', 'completed'])->sum('principal_amount') ?? 0,
            'total_repaid' => Loan::sum('paid_amount') ?? 0,
            'outstanding' => Loan::whereIn('status', ['active', 'disbursed'])->sum('remaining_amount') ?? 0,
            'overdue' => Loan::where('status', 'active')
                ->where('maturity_date', '<', now())
                ->count(),
        ];

        // Savings Statistics
        $savingsStats = [
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'total_balance' => SavingsAccount::sum('balance') ?? 0,
            'average_balance' => SavingsAccount::where('status', 'active')->avg('balance') ?? 0,
            'total_deposits' => Transaction::where('transaction_type', 'savings_deposit')
                ->where('status', 'completed')
                ->sum('amount') ?? 0,
        ];

        // Investment Statistics
        $investmentStats = [
            'active' => Investment::where('status', 'active')->count(),
            'matured' => Investment::where('status', 'matured')->count(),
            'total_principal' => Investment::sum('principal_amount') ?? 0,
            'total_profit' => Investment::sum('profit_share') ?? 0,
            'expected_returns' => Investment::sum('expected_return') ?? 0,
        ];

        // Recent Activity (Last 30 days)
        $recentActivity = [
            'new_members' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'new_loans' => Loan::where('created_at', '>=', now()->subDays(30))->count(),
            'new_savings' => SavingsAccount::where('created_at', '>=', now()->subDays(30))->count(),
            'new_investments' => Investment::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        // Monthly Trends (Last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $month->format('M Y'),
                'month_short' => $month->format('M'),
                'members' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'loans' => Loan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'loan_amount' => Loan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('principal_amount') ?? 0,
                'savings' => SavingsAccount::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'transactions' => Transaction::whereYear('transaction_date', $month->year)
                    ->whereMonth('transaction_date', $month->month)
                    ->count(),
            ];
        }

        // Recent Loans
        $recentLoans = Loan::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Success Rate
        $successRate = $stats['total_loans'] > 0
            ? round(($loanStats['completed'] / $stats['total_loans']) * 100, 1)
            : 0;

        // Portfolio Health
        $portfolioHealth = [
            'recovery_rate' => $loanStats['total_disbursed'] > 0
                ? round(($loanStats['total_repaid'] / $loanStats['total_disbursed']) * 100, 1)
                : 0,
            'default_rate' => $loanStats['total_disbursed'] > 0
                ? round(($loanStats['overdue'] / $loanStats['total_disbursed']) * 100, 1)
                : 0,
        ];

        // Service Distribution
        $serviceDistribution = [
            'loans' => $stats['total_loans'],
            'savings' => $stats['total_savings_accounts'],
            'investments' => $stats['total_investments'],
            'welfare' => SocialWelfare::count(),
        ];

        return view('welcome', compact(
            'stats',
            'loanStats',
            'savingsStats',
            'investmentStats',
            'recentActivity',
            'monthlyTrends',
            'recentLoans',
            'recentTransactions',
            'successRate',
            'portfolioHealth',
            'serviceDistribution'
        ));
    }
}
