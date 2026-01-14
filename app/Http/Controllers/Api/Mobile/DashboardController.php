<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Issue;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\SocialWelfare;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get KPIs
        $loanOutstanding = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'disbursed'])
            ->sum('remaining_amount') ?? 0;

        $savingsBalance = SavingsAccount::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('balance') ?? 0;

        // Get next due payment
        $nextDueLoan = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'disbursed'])
            ->where('maturity_date', '>=', now())
            ->orderBy('maturity_date')
            ->first();

        // Get alerts
        $alerts = $this->getAlerts($user);

        // Recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->transaction_type,
                    'amount' => (float) $transaction->amount,
                    'status' => $transaction->status,
                    'date' => $transaction->transaction_date->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'kpis' => [
                    'loan_outstanding' => (float) $loanOutstanding,
                    'savings_balance' => (float) $savingsBalance,
                    'next_due_date' => $nextDueLoan ? $nextDueLoan->maturity_date->format('Y-m-d') : null,
                    'next_due_amount' => $nextDueLoan ? (float) $nextDueLoan->remaining_amount : null,
                ],
                'alerts' => $alerts,
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }

    public function stats(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'loans' => [
                'total' => Loan::where('user_id', $user->id)->count(),
                'active' => Loan::where('user_id', $user->id)->where('status', 'active')->count(),
                'pending' => Loan::where('user_id', $user->id)->where('status', 'pending')->count(),
                'total_amount' => (float) (Loan::where('user_id', $user->id)->sum('principal_amount') ?? 0),
                'remaining_amount' => (float) (Loan::where('user_id', $user->id)->sum('remaining_amount') ?? 0),
            ],
            'savings' => [
                'total_accounts' => SavingsAccount::where('user_id', $user->id)->count(),
                'total_balance' => (float) (SavingsAccount::where('user_id', $user->id)->sum('balance') ?? 0),
                'active_accounts' => SavingsAccount::where('user_id', $user->id)->where('status', 'active')->count(),
            ],
            'investments' => [
                'total' => Investment::where('user_id', $user->id)->count(),
                'active' => Investment::where('user_id', $user->id)->where('status', 'active')->count(),
                'total_principal' => (float) (Investment::where('user_id', $user->id)->sum('principal_amount') ?? 0),
            ],
            'welfare' => [
                'contributions' => (float) (SocialWelfare::where('user_id', $user->id)->where('type', 'contribution')->sum('amount') ?? 0),
                'benefits' => (float) (SocialWelfare::where('user_id', $user->id)->where('type', 'benefit')->sum('amount') ?? 0),
            ],
            'issues' => [
                'pending' => Issue::where('user_id', $user->id)->where('status', 'pending')->count(),
                'total' => Issue::where('user_id', $user->id)->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function alerts(Request $request)
    {
        $user = Auth::user();
        $alerts = $this->getAlerts($user);

        return response()->json([
            'success' => true,
            'data' => $alerts,
        ]);
    }

    public function apiGuide(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'api_version' => 'v1',
                'base_url' => url('/api/mobile/v1'),
                'authentication' => [
                    'type' => 'Bearer Token (Sanctum)',
                    'login_endpoint' => '/auth/login',
                    'register_endpoint' => '/auth/register',
                ],
                'endpoints' => [
                    'dashboard' => '/dashboard',
                    'loans' => '/loans',
                    'savings' => '/savings',
                    'investments' => '/investments',
                    'welfare' => '/welfare',
                    'issues' => '/issues',
                    'transactions' => '/transactions',
                    'profile' => '/profile',
                    'notifications' => '/notifications',
                    'file_uploads' => '/upload',
                ],
                'features' => [
                    'what_members_can_do' => [
                        'register_verify_profile',
                        'dashboard_snapshot',
                        'loans_management',
                        'savings_management',
                        'investments_management',
                        'social_welfare',
                        'issues_feedback',
                        'notifications',
                        'profile_security',
                        'documents',
                    ],
                ],
            ],
        ]);
    }

    private function getAlerts($user): array
    {
        $alerts = [];

        // Overdue loans
        $overdueLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('maturity_date', '<', now())
            ->count();

        if ($overdueLoans > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => "{$overdueLoans} Overdue Loan(s)",
                'message' => 'You have loans that are past their due date',
                'action' => 'view_loans',
            ];
        }

        // Upcoming payments (within 7 days)
        $upcomingPayments = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'disbursed'])
            ->whereBetween('maturity_date', [now(), now()->addDays(7)])
            ->count();

        if ($upcomingPayments > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => "{$upcomingPayments} Payment(s) Due Soon",
                'message' => 'You have payments due within the next 7 days',
                'action' => 'view_loans',
            ];
        }

        // Pending loan approvals
        $pendingLoans = Loan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingLoans > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => "{$pendingLoans} Pending Loan Application(s)",
                'message' => 'Your loan applications are awaiting review',
                'action' => 'view_loans',
            ];
        }

        // Pending issues
        $pendingIssues = Issue::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingIssues > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => "{$pendingIssues} Pending Issue(s)",
                'message' => 'Your issues are being reviewed',
                'action' => 'view_issues',
            ];
        }

        return $alerts;
    }
}
