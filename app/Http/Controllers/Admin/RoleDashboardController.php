<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Models\Investment;
use App\Models\SocialWelfare;
use App\Models\User;
use App\Models\Issue;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleDashboardController extends Controller
{
    /**
     * Show role-specific dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('slug')->toArray();
        
        // If user has multiple roles, show combined dashboard
        if (count($roles) > 1) {
            return $this->combinedDashboard($roles);
        }
        
        // Single role dashboards
        if (in_array('loan_officer', $roles)) {
            return $this->loanOfficerDashboard();
        }
        
        if (in_array('deposit_officer', $roles)) {
            return $this->depositOfficerDashboard();
        }
        
        if (in_array('investment_officer', $roles)) {
            return $this->investmentOfficerDashboard();
        }
        
        if (in_array('chairperson', $roles)) {
            return $this->chairpersonDashboard();
        }
        
        if (in_array('secretary', $roles)) {
            return $this->secretaryDashboard();
        }
        
        if (in_array('accountant', $roles)) {
            return $this->accountantDashboard();
        }
        
        // Default to admin dashboard if admin role
        if (in_array('admin', $roles) || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Fallback
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * Combined dashboard for users with multiple roles
     */
    protected function combinedDashboard(array $roles)
    {
        $user = Auth::user();
        $data = [];
        
        // Collect data from all roles
        foreach ($roles as $role) {
            switch ($role) {
                case 'loan_officer':
                    $data['loan_officer'] = $this->getLoanOfficerData();
                    break;
                case 'deposit_officer':
                    $data['deposit_officer'] = $this->getDepositOfficerData();
                    break;
                case 'investment_officer':
                    $data['investment_officer'] = $this->getInvestmentOfficerData();
                    break;
                case 'chairperson':
                    $data['chairperson'] = $this->getChairpersonData();
                    break;
                case 'secretary':
                    $data['secretary'] = $this->getSecretaryData();
                    break;
                case 'accountant':
                    $data['accountant'] = $this->getAccountantData();
                    break;
            }
        }
        
        return view('admin.role-dashboards.combined', compact('data', 'roles', 'user'));
    }
    
    /**
     * Loan Officer Dashboard
     */
    protected function loanOfficerDashboard()
    {
        $user = Auth::user();
        $data = $this->getLoanOfficerData();
        
        return view('admin.role-dashboards.loan-officer', compact('data', 'user'));
    }
    
    /**
     * Deposit Officer Dashboard
     */
    protected function depositOfficerDashboard()
    {
        $user = Auth::user();
        $data = $this->getDepositOfficerData();
        
        return view('admin.role-dashboards.deposit-officer', compact('data', 'user'));
    }
    
    /**
     * Investment Officer Dashboard
     */
    protected function investmentOfficerDashboard()
    {
        $user = Auth::user();
        $data = $this->getInvestmentOfficerData();
        
        return view('admin.role-dashboards.investment-officer', compact('data', 'user'));
    }
    
    /**
     * Chairperson Dashboard
     */
    protected function chairpersonDashboard()
    {
        $user = Auth::user();
        $data = $this->getChairpersonData();
        
        return view('admin.role-dashboards.chairperson', compact('data', 'user'));
    }
    
    /**
     * Secretary Dashboard
     */
    protected function secretaryDashboard()
    {
        $user = Auth::user();
        $data = $this->getSecretaryData();
        
        return view('admin.role-dashboards.secretary', compact('data', 'user'));
    }
    
    /**
     * Accountant Dashboard
     */
    protected function accountantDashboard()
    {
        $user = Auth::user();
        $data = $this->getAccountantData();
        
        return view('admin.role-dashboards.accountant', compact('data', 'user'));
    }
    
    /**
     * Get Loan Officer specific data
     */
    protected function getLoanOfficerData()
    {
        return [
            'pending_approvals' => Loan::where('status', 'pending')->count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'total_portfolio' => Loan::where('status', 'active')->sum('remaining_amount'),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('maturity_date', '<', now())
                ->count(),
            'pending_applications' => Loan::where('status', 'pending')->latest()->limit(10)->get(),
            'recent_approvals' => Loan::where('status', 'approved')
                ->latest()
                ->limit(5)
                ->get(),
            'monthly_loans' => Loan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'monthly_amount' => Loan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('principal_amount'),
        ];
    }
    
    /**
     * Get Deposit Officer specific data
     */
    protected function getDepositOfficerData()
    {
        return [
            'total_accounts' => SavingsAccount::count(),
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'total_balance' => SavingsAccount::sum('balance'),
            'today_deposits' => Transaction::where('transaction_type', 'savings_deposit')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'today_withdrawals' => Transaction::where('transaction_type', 'savings_withdrawal')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'recent_accounts' => SavingsAccount::latest()->limit(10)->get(),
            'recent_transactions' => Transaction::whereIn('transaction_type', ['savings_deposit', 'savings_withdrawal'])
                ->latest()
                ->limit(10)
                ->get(),
            'monthly_deposits' => Transaction::where('transaction_type', 'savings_deposit')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];
    }
    
    /**
     * Get Investment Officer specific data
     */
    protected function getInvestmentOfficerData()
    {
        return [
            'total_investments' => Investment::count(),
            'active_investments' => Investment::where('status', 'active')->count(),
            'matured_investments' => Investment::where('status', 'matured')->count(),
            'total_principal' => Investment::sum('principal_amount'),
            'total_profit' => Investment::sum('profit_share'),
            'recent_investments' => Investment::latest()->limit(10)->get(),
            'upcoming_maturities' => Investment::where('status', 'active')
                ->whereBetween('maturity_date', [now(), now()->addMonths(3)])
                ->orderBy('maturity_date')
                ->limit(10)
                ->get(),
            'monthly_investments' => Investment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('principal_amount'),
        ];
    }
    
    /**
     * Get Chairperson specific data
     */
    protected function getChairpersonData()
    {
        return [
            'total_members' => User::whereHas('roles', function($q) { $q->where('slug', 'member'); })->count(),
            'total_loans' => Loan::count(),
            'total_savings' => SavingsAccount::sum('balance'),
            'total_investments' => Investment::sum('principal_amount'),
            'pending_issues' => Issue::where('status', 'open')->count(),
            'recent_issues' => Issue::latest()->limit(10)->get(),
            'financial_summary' => [
                'total_assets' => SavingsAccount::sum('balance') + Investment::sum('principal_amount'),
                'total_liabilities' => Loan::where('status', 'active')->sum('remaining_amount'),
                'net_position' => (SavingsAccount::sum('balance') + Investment::sum('principal_amount')) - Loan::where('status', 'active')->sum('remaining_amount'),
            ],
        ];
    }
    
    /**
     * Get Secretary specific data
     */
    protected function getSecretaryData()
    {
        return [
            'total_members' => User::whereHas('roles', function($q) { $q->where('slug', 'member'); })->count(),
            'pending_issues' => Issue::where('status', 'open')->count(),
            'resolved_issues' => Issue::where('status', 'resolved')->count(),
            'recent_members' => User::whereHas('roles', function($q) { $q->where('slug', 'member'); })
                ->latest()
                ->limit(10)
                ->get(),
            'recent_issues' => Issue::latest()->limit(10)->get(),
            'member_registrations_this_month' => User::whereHas('roles', function($q) { $q->where('slug', 'member'); })
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }
    
    /**
     * Get Accountant specific data
     */
    protected function getAccountantData()
    {
        return [
            'total_revenue' => Transaction::where('transaction_type', 'loan_payment')->sum('amount'),
            'total_expenses' => Transaction::whereIn('transaction_type', ['loan_disbursement', 'welfare_benefit'])->sum('amount'),
            'net_profit' => Transaction::where('transaction_type', 'loan_payment')->sum('amount') - 
                           Transaction::whereIn('transaction_type', ['loan_disbursement', 'welfare_benefit'])->sum('amount'),
            'total_transactions' => Transaction::count(),
            'today_transactions' => Transaction::whereDate('created_at', today())->count(),
            'recent_transactions' => Transaction::latest()->limit(20)->get(),
            'monthly_revenue' => Transaction::where('transaction_type', 'loan_payment')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'financial_summary' => [
                'total_loans' => Loan::sum('principal_amount'),
                'total_paid' => Loan::sum('paid_amount'),
                'outstanding' => Loan::sum('remaining_amount'),
                'total_savings' => SavingsAccount::sum('balance'),
                'total_investments' => Investment::sum('principal_amount'),
            ],
        ];
    }
}

