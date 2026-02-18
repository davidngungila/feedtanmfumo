<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Issue;
use App\Models\Loan;
use App\Models\PaymentConfirmation;
use App\Models\SavingsAccount;
use App\Models\SocialWelfare;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the member dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $user->load('roles');

        // Get user's financial data
        $loans = Loan::where('user_id', $user->id)->latest()->take(5)->get();
        $savings = SavingsAccount::where('user_id', $user->id)->get();
        $investments = Investment::where('user_id', $user->id)->latest()->take(5)->get();
        $welfare = SocialWelfare::where('user_id', $user->id)->latest()->take(5)->get();
        $issues = Issue::where('user_id', $user->id)->latest()->take(5)->get();

        // Get payment confirmations
        $paymentConfirmations = PaymentConfirmation::where('user_id', $user->id)
            ->orWhere('member_id', $user->member_number)
            ->orWhere('member_id', $user->membership_code)
            ->latest()
            ->take(5)
            ->get();

        // Calculate detailed statistics
        $stats = [
            'total_loans' => Loan::where('user_id', $user->id)->count(),
            'active_loans' => Loan::where('user_id', $user->id)->where('status', 'active')->count(),
            'pending_loans' => Loan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_loans' => Loan::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_loan_amount' => Loan::where('user_id', $user->id)->sum('principal_amount'),
            'paid_amount' => Loan::where('user_id', $user->id)->sum('paid_amount'),
            'remaining_amount' => Loan::where('user_id', $user->id)->sum('remaining_amount'),
            'overdue_loans' => Loan::where('user_id', $user->id)->where('status', 'active')->where('maturity_date', '<', now())->count(),

            'total_savings' => SavingsAccount::where('user_id', $user->id)->count(),
            'active_savings' => SavingsAccount::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_savings_balance' => SavingsAccount::where('user_id', $user->id)->sum('balance'),
            'total_savings_deposits' => Transaction::where('user_id', $user->id)->where('transaction_type', 'savings_deposit')->where('related_type', 'savings_account')->sum('amount'),
            'total_savings_withdrawals' => Transaction::where('user_id', $user->id)->where('transaction_type', 'savings_withdrawal')->where('related_type', 'savings_account')->sum('amount'),

            'total_investments' => Investment::where('user_id', $user->id)->count(),
            'active_investments' => Investment::where('user_id', $user->id)->where('status', 'active')->count(),
            'matured_investments' => Investment::where('user_id', $user->id)->where('status', 'matured')->count(),
            'total_investment_amount' => Investment::where('user_id', $user->id)->sum('principal_amount'),
            'total_profit' => Investment::where('user_id', $user->id)->sum('profit_share'),
            'expected_profit' => Investment::where('user_id', $user->id)->where('status', 'active')->sum('expected_return'),

            'total_welfare_contributions' => SocialWelfare::where('user_id', $user->id)->where('type', 'contribution')->sum('amount'),
            'total_welfare_benefits' => SocialWelfare::where('user_id', $user->id)->where('type', 'benefit')->sum('amount'),
            'welfare_balance' => SocialWelfare::where('user_id', $user->id)->where('type', 'contribution')->sum('amount') - SocialWelfare::where('user_id', $user->id)->where('type', 'benefit')->sum('amount'),

            'pending_issues' => Issue::where('user_id', $user->id)->where('status', 'pending')->count(),
            'resolved_issues' => Issue::where('user_id', $user->id)->where('status', 'resolved')->count(),
            'total_issues' => Issue::where('user_id', $user->id)->count(),
            
            'total_transactions' => Transaction::where('user_id', $user->id)->count(),
            'total_income' => Transaction::where('user_id', $user->id)->whereIn('transaction_type', ['savings_deposit', 'investment_disbursement', 'welfare_benefit', 'loan_disbursement'])->sum('amount'),
            'total_expenses' => Transaction::where('user_id', $user->id)->whereIn('transaction_type', ['savings_withdrawal', 'loan_payment', 'welfare_contribution', 'investment_deposit'])->sum('amount'),
        ];

        // Recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Get KPIs
        $loanOutstanding = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'disbursed'])
            ->sum('remaining_amount') ?? 0;

        // Get next due payment
        $nextDueLoan = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'disbursed'])
            ->where('maturity_date', '>=', now())
            ->orderBy('maturity_date')
            ->first();

        // Get alerts
        $alerts = [];

        // Overdue loans
        $overdueLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('maturity_date', '<', now())
            ->count();

        if ($overdueLoans > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'title' => "{$overdueLoans} Overdue Loan(s)",
                'description' => 'You have loans that are past their due date',
                'action' => route('member.loans.index', ['status' => 'overdue']),
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
                'icon' => 'clock',
                'title' => "{$upcomingPayments} Payment(s) Due Soon",
                'description' => 'You have payments due within the next 7 days',
                'action' => route('member.loans.index'),
            ];
        }

        // Pending loan approvals
        $pendingLoans = Loan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingLoans > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'clock',
                'title' => "{$pendingLoans} Pending Loan Application(s)",
                'description' => 'Your loan applications are awaiting review',
                'action' => route('member.loans.index', ['status' => 'pending']),
            ];
        }

        // Pending issues
        if ($stats['pending_issues'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'flag',
                'title' => "{$stats['pending_issues']} Pending Issue(s)",
                'description' => 'Your issues are being reviewed',
                'action' => route('member.issues.index', ['status' => 'pending']),
            ];
        }

        // Role-based permissions
        $isLoanOfficer = $user->hasRole('loan_officer');
        $isDepositOfficer = $user->hasRole('deposit_officer');
        $isInvestmentOfficer = $user->hasRole('investment_officer');
        $isChairperson = $user->hasRole('chairperson');
        $isSecretary = $user->hasRole('secretary');
        $isAccountant = $user->hasRole('accountant');

        return view('member.dashboard', compact(
            'user', 'loans', 'savings', 'investments', 'welfare', 'issues',
            'stats', 'recentTransactions', 'alerts', 'loanOutstanding', 'nextDueLoan',
            'isLoanOfficer', 'isDepositOfficer', 'isInvestmentOfficer',
            'isChairperson', 'isSecretary', 'isAccountant', 'paymentConfirmations'
        ));
    }
}
