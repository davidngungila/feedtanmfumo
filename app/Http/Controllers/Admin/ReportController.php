<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\SocialWelfare;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function financial()
    {
        $stats = [
            'total_loans' => Loan::where('status', 'active')->sum('remaining_amount'),
            'total_savings' => SavingsAccount::sum('balance'),
            'total_investments' => Investment::where('status', 'active')->sum('principal_amount'),
            'total_welfare_fund' => SocialWelfare::where('type', 'contribution')->sum('amount') - SocialWelfare::where('type', 'benefit')->sum('amount'),
            'total_principal' => Loan::sum('principal_amount'),
            'total_paid' => Loan::sum('paid_amount'),
            'total_revenue' => Transaction::where('transaction_type', 'loan_payment')->sum('amount'),
            'total_members' => User::whereHas('roles', function ($q) {
                $q->where('slug', 'member');
            })->count(),
        ];

        $loanStats = Loan::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->groupBy('status')
            ->get();

        $savingsStats = SavingsAccount::select('account_type', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
            ->groupBy('account_type')
            ->get();

        // Monthly trends
        $monthlyLoans = Loan::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlySavings = Transaction::where('transaction_type', 'savings_deposit')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('sum(amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.financial', compact('stats', 'loanStats', 'savingsStats', 'monthlyLoans', 'monthlySavings'));
    }

    public function loans()
    {
        $loans = Loan::with('user')->latest()->paginate(50);
        $stats = [
            'total' => Loan::count(),
            'total_amount' => Loan::sum('principal_amount'),
            'paid_amount' => Loan::sum('paid_amount'),
            'remaining_amount' => Loan::sum('remaining_amount'),
            'active_count' => Loan::where('status', 'active')->count(),
            'pending_count' => Loan::where('status', 'pending')->count(),
            'completed_count' => Loan::where('status', 'completed')->count(),
            'overdue_count' => Loan::where('status', 'active')->where('maturity_date', '<', now())->count(),
            'avg_loan_amount' => Loan::avg('principal_amount'),
            'recovery_rate' => Loan::sum('principal_amount') > 0 ? (Loan::sum('paid_amount') / Loan::sum('principal_amount')) * 100 : 0,
        ];

        $loansByStatus = Loan::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->groupBy('status')
            ->get();

        $loansByMonth = Loan::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.loans', compact('loans', 'stats', 'loansByStatus', 'loansByMonth'));
    }

    public function savings()
    {
        $accounts = SavingsAccount::with('user')->latest()->paginate(50);
        $stats = [
            'total_accounts' => SavingsAccount::count(),
            'total_balance' => SavingsAccount::sum('balance'),
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'avg_balance' => SavingsAccount::avg('balance'),
            'total_deposits' => Transaction::where('transaction_type', 'savings_deposit')->sum('amount'),
            'total_withdrawals' => Transaction::where('transaction_type', 'savings_withdrawal')->sum('amount'),
            'by_type' => SavingsAccount::select('account_type', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
                ->groupBy('account_type')
                ->get(),
            'by_status' => SavingsAccount::select('status', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
                ->groupBy('status')
                ->get(),
        ];

        $monthlyDeposits = Transaction::where('transaction_type', 'savings_deposit')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('sum(amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.savings', compact('accounts', 'stats', 'monthlyDeposits'));
    }

    public function investments()
    {
        $investments = Investment::with('user')->latest()->paginate(50);
        $stats = [
            'total' => Investment::count(),
            'total_principal' => Investment::sum('principal_amount'),
            'total_profit' => Investment::sum('profit_share'),
            'active' => Investment::where('status', 'active')->sum('principal_amount'),
            'active_count' => Investment::where('status', 'active')->count(),
            'matured_count' => Investment::where('status', 'matured')->count(),
            'avg_principal' => Investment::avg('principal_amount'),
            'avg_interest_rate' => Investment::avg('interest_rate'),
            'by_type' => Investment::select('plan_type', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
                ->groupBy('plan_type')
                ->get(),
            'by_status' => Investment::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
                ->groupBy('status')
                ->get(),
        ];

        $monthlyInvestments = Investment::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.reports.investments', compact('investments', 'stats', 'monthlyInvestments'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'loans');

        // Export functionality can be added here
        return redirect()->back()->with('info', 'Export feature coming soon.');
    }

    public function exportFinancialPdf()
    {
        $stats = [
            'total_loans' => Loan::where('status', 'active')->sum('remaining_amount'),
            'total_savings' => SavingsAccount::sum('balance'),
            'total_investments' => Investment::where('status', 'active')->sum('principal_amount'),
            'total_welfare_fund' => SocialWelfare::where('type', 'contribution')->sum('amount') - SocialWelfare::where('type', 'benefit')->sum('amount'),
            'total_principal' => Loan::sum('principal_amount'),
            'total_paid' => Loan::sum('paid_amount'),
            'total_revenue' => Transaction::where('transaction_type', 'loan_payment')->sum('amount'),
            'total_members' => User::whereHas('roles', function ($q) {
                $q->where('slug', 'member');
            })->count(),
        ];

        $loanStats = Loan::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->groupBy('status')
            ->get();

        $savingsStats = SavingsAccount::select('account_type', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
            ->groupBy('account_type')
            ->get();

        $monthlyLoans = Loan::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlySavings = Transaction::where('transaction_type', 'savings_deposit')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('sum(amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return PdfHelper::downloadPdf('admin.reports.pdf.financial', [
            'stats' => $stats,
            'loanStats' => $loanStats,
            'savingsStats' => $savingsStats,
            'monthlyLoans' => $monthlyLoans,
            'monthlySavings' => $monthlySavings,
            'documentTitle' => 'Financial Report',
            'documentSubtitle' => 'Comprehensive Financial Overview',
        ], 'financial-report-'.date('Y-m-d-His').'.pdf');
    }

    public function exportLoansPdf()
    {
        $loans = Loan::with('user')->latest()->get();
        $stats = [
            'total' => Loan::count(),
            'total_amount' => Loan::sum('principal_amount'),
            'paid_amount' => Loan::sum('paid_amount'),
            'remaining_amount' => Loan::sum('remaining_amount'),
            'active_count' => Loan::where('status', 'active')->count(),
            'pending_count' => Loan::where('status', 'pending')->count(),
            'completed_count' => Loan::where('status', 'completed')->count(),
            'overdue_count' => Loan::where('status', 'active')->where('maturity_date', '<', now())->count(),
            'avg_loan_amount' => Loan::avg('principal_amount'),
            'recovery_rate' => Loan::sum('principal_amount') > 0 ? (Loan::sum('paid_amount') / Loan::sum('principal_amount')) * 100 : 0,
        ];

        $loansByStatus = Loan::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->groupBy('status')
            ->get();

        $loansByMonth = Loan::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return PdfHelper::downloadPdf('admin.reports.pdf.loans', [
            'loans' => $loans,
            'stats' => $stats,
            'loansByStatus' => $loansByStatus,
            'loansByMonth' => $loansByMonth,
            'documentTitle' => 'Loan Portfolio Report',
            'documentSubtitle' => 'Detailed Loan Analysis',
        ], 'loans-report-'.date('Y-m-d-His').'.pdf');
    }

    public function exportSavingsPdf()
    {
        $accounts = SavingsAccount::with('user')->latest()->get();
        $stats = [
            'total_accounts' => SavingsAccount::count(),
            'total_balance' => SavingsAccount::sum('balance'),
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'avg_balance' => SavingsAccount::avg('balance'),
            'total_deposits' => Transaction::where('transaction_type', 'savings_deposit')->sum('amount'),
            'total_withdrawals' => Transaction::where('transaction_type', 'savings_withdrawal')->sum('amount'),
            'by_type' => SavingsAccount::select('account_type', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
                ->groupBy('account_type')
                ->get(),
            'by_status' => SavingsAccount::select('status', DB::raw('count(*) as count'), DB::raw('sum(balance) as total'))
                ->groupBy('status')
                ->get(),
        ];

        $monthlyDeposits = Transaction::where('transaction_type', 'savings_deposit')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('sum(amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return PdfHelper::downloadPdf('admin.reports.pdf.savings', [
            'accounts' => $accounts,
            'stats' => $stats,
            'monthlyDeposits' => $monthlyDeposits,
            'documentTitle' => 'Savings Report',
            'documentSubtitle' => 'Savings Accounts and Deposits Analysis',
        ], 'savings-report-'.date('Y-m-d-His').'.pdf');
    }

    public function exportInvestmentsPdf()
    {
        $investments = Investment::with('user')->latest()->get();
        $stats = [
            'total' => Investment::count(),
            'total_principal' => Investment::sum('principal_amount'),
            'total_profit' => Investment::sum('profit_share'),
            'active' => Investment::where('status', 'active')->sum('principal_amount'),
            'active_count' => Investment::where('status', 'active')->count(),
            'matured_count' => Investment::where('status', 'matured')->count(),
            'avg_principal' => Investment::avg('principal_amount'),
            'avg_interest_rate' => Investment::avg('interest_rate'),
            'by_type' => Investment::select('plan_type', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
                ->groupBy('plan_type')
                ->get(),
            'by_status' => Investment::select('status', DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
                ->groupBy('status')
                ->get(),
        ];

        $monthlyInvestments = Investment::select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'), DB::raw('sum(principal_amount) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return PdfHelper::downloadPdf('admin.reports.pdf.investments', [
            'investments' => $investments,
            'stats' => $stats,
            'monthlyInvestments' => $monthlyInvestments,
            'documentTitle' => 'Investment Report',
            'documentSubtitle' => 'Investment Portfolio and Performance',
        ], 'investments-report-'.date('Y-m-d-His').'.pdf');
    }
}
