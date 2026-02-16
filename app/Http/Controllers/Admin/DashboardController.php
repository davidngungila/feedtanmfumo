<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use App\Models\Issue;
use App\Models\SavingsAccount;
use App\Models\Investment;
use App\Models\SocialWelfare;
use App\Models\Transaction;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirect officers to their role-specific dashboard
        if ($user->hasAnyRole(['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant'])) {
            return redirect()->route('admin.role-dashboard');
        }
        // User Statistics
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->orWhereHas('roles', function($q) {
                $q->where('slug', 'admin');
            })->count(),
            'regular_users' => User::whereDoesntHave('roles', function($q) {
                $q->where('slug', 'admin');
            })->where('role', '!=', 'admin')->count(),
            'recent_users' => User::with('roles')->latest()->take(5)->get(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_users_month' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'new_users_last_week' => User::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count(),
        ];

        // Issues Statistics
        $issues = [
            'pending' => Issue::where('status', 'pending')->count(),
            'in_progress' => Issue::where('status', 'in_progress')->count(),
            'resolved' => Issue::where('status', 'resolved')->count(),
            'closed' => Issue::where('status', 'closed')->count(),
            'total' => Issue::count(),
            'high_priority' => Issue::where('priority', 'high')->orWhere('priority', 'urgent')->count(),
            'medium_priority' => Issue::where('priority', 'medium')->count(),
            'low_priority' => Issue::where('priority', 'low')->count(),
            'urgent' => Issue::where('priority', 'urgent')->count(),
            'recent_issues' => Issue::with(['user', 'assignedTo'])->latest()->take(5)->get(),
        ];

        // Loans Statistics
        $loans = [
            'pending' => Loan::where('status', 'pending')->count(),
            'approved' => Loan::where('status', 'approved')->count(),
            'active' => Loan::where('status', 'active')->count(),
            'rejected' => Loan::where('status', 'rejected')->count(),
            'completed' => Loan::where('status', 'completed')->count(),
            'total_amount' => Loan::where('status', '!=', 'rejected')->sum('total_amount') ?? 0,
            'pending_amount' => Loan::where('status', 'pending')->sum('total_amount') ?? 0,
            'overdue' => Loan::where('status', 'active')
                ->where('maturity_date', '<', now())
                ->count(),
            'recent_loans' => Loan::with('user')->latest()->take(5)->get(),
        ];

        // Savings Statistics
        $savings = [
            'total_accounts' => SavingsAccount::count(),
            'total_balance' => SavingsAccount::sum('balance') ?? 0,
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'recent_accounts' => SavingsAccount::with('user')->latest()->take(5)->get(),
        ];

        // Investments Statistics
        $investments = [
            'total' => Investment::count(),
            'active' => Investment::where('status', 'active')->count(),
            'matured' => Investment::where('status', 'matured')->count(),
            'total_principal' => Investment::sum('principal_amount') ?? 0,
            'total_expected_return' => Investment::sum('expected_return') ?? 0,
            'recent_investments' => Investment::with('user')->latest()->take(5)->get(),
        ];

        // Welfare Statistics
        $welfare = [
            'total' => SocialWelfare::count(),
            'pending' => SocialWelfare::where('status', 'pending')->count(),
            'approved' => SocialWelfare::where('status', 'approved')->count(),
            'total_amount' => SocialWelfare::where('status', 'approved')->sum('amount') ?? 0,
        ];

        // Recent Activity (last 10 activities)
        $recentActivities = collect();
        
        // Recent users
        User::latest()->take(3)->get()->each(function($user) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'user',
                'icon' => 'user',
                'title' => 'New User Registered',
                'description' => $user->name . ' joined the platform',
                'time' => $user->created_at,
                'color' => 'blue',
            ]);
        });

        // Recent issues
        Issue::latest()->take(3)->get()->each(function($issue) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'issue',
                'icon' => 'alert',
                'title' => 'New Issue: ' . $issue->title,
                'description' => 'Priority: ' . ucfirst($issue->priority),
                'time' => $issue->created_at,
                'color' => $issue->priority === 'urgent' || $issue->priority === 'high' ? 'red' : 'orange',
            ]);
        });

        // Recent loans
        Loan::latest()->take(2)->get()->each(function($loan) use ($recentActivities) {
            $recentActivities->push([
                'type' => 'loan',
                'icon' => 'money',
                'title' => 'New Loan Application',
                'description' => 'Amount: TZS ' . number_format($loan->total_amount, 2),
                'time' => $loan->created_at,
                'color' => 'green',
            ]);
        });

        // Guarantor Assessment Statistics
        $assessments = [
            'pending' => \App\Models\GuarantorAssessment::where('status', 'pending')->count(),
            'approved' => \App\Models\GuarantorAssessment::where('status', 'approved')->count(),
            'total' => \App\Models\GuarantorAssessment::count(),
        ];

        $recentActivities = $recentActivities->sortByDesc('time')->take(10)->values();

        // Performance Metrics (Last 6 months)
        $performanceMetrics = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $performanceMetrics[] = [
                'month' => $month->format('M Y'),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'loans' => Loan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'issues' => Issue::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Alerts & Notifications
        $alerts = [];
        
        // Overdue loans
        if ($loans['overdue'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'title' => $loans['overdue'] . ' Overdue Loan(s)',
                'description' => 'Some loans have passed their maturity date',
                'action' => route('admin.loans.index', ['status' => 'overdue']),
            ];
        }

        // Urgent issues
        if ($issues['urgent'] > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'title' => $issues['urgent'] . ' Urgent Issue(s)',
                'description' => 'Requires immediate attention',
                'action' => route('admin.issues.index', ['priority' => 'urgent']),
            ];
        }

        // Pending approvals
        if ($loans['pending'] > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'clock',
                'title' => $loans['pending'] . ' Pending Loan Approval(s)',
                'description' => 'Awaiting review and approval',
                'action' => route('admin.loans.index', ['status' => 'pending']),
            ];
        }

        // High priority issues
        if ($issues['high_priority'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'flag',
                'title' => $issues['high_priority'] . ' High Priority Issue(s)',
                'description' => 'Needs attention soon',
                'action' => route('admin.issues.index', ['priority' => 'high']),
            ];
        }

        // Pending guarantor assessments
        if ($assessments['pending'] > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'user-check',
                'title' => $assessments['pending'] . ' Pending Guarantor Review(s)',
                'description' => 'Guarantor assessments waiting for approval',
                'action' => route('admin.guarantor-assessments.index'),
            ];
        }

        // Role-based Statistics
        $roleStats = [];
        $roles = Role::withCount('users')->get();
        foreach ($roles as $role) {
            $roleStats[] = [
                'name' => $role->name,
                'slug' => $role->slug,
                'count' => $role->users_count,
            ];
        }

        // Users by role (for pie chart)
        $usersByRole = [
            'Members' => User::where('role', 'user')->whereDoesntHave('roles')->count(),
        ];
        foreach ($roles as $role) {
            $count = User::whereHas('roles', function($q) use ($role) {
                $q->where('roles.id', $role->id);
            })->count();
            if ($count > 0) {
                $usersByRole[$role->name] = $count;
            }
        }

        // Issues by status (for pie chart)
        $issuesByStatus = [
            'Pending' => $issues['pending'],
            'In Progress' => $issues['in_progress'],
            'Resolved' => $issues['resolved'],
            'Closed' => $issues['closed'],
        ];

        // Loans by status (for pie chart)
        $loansByStatus = [
            'Pending' => $loans['pending'],
            'Approved' => $loans['approved'],
            'Active' => $loans['active'],
            'Completed' => $loans['completed'],
            'Rejected' => $loans['rejected'],
        ];

        // Monthly trend data for line chart (last 6 months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyTrends[] = [
                'month' => $month->format('M'),
                'month_full' => $month->format('M Y'),
                'users' => User::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'loans' => Loan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'issues' => Issue::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'savings' => SavingsAccount::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Transactions by type (for bar chart) - check if table exists
        $transactionsByType = [];
        try {
            if (DB::getSchemaBuilder()->hasTable('transactions')) {
                $transactionsByType = DB::table('transactions')
                    ->select('type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
                    ->groupBy('type')
                    ->get()
                    ->mapWithKeys(function($item) {
                        return [ucfirst($item->type) => ['count' => $item->count, 'total' => $item->total ?? 0]];
                    })
                    ->toArray();
            }
        } catch (\Exception $e) {
            // Table doesn't exist or error, use empty array
            $transactionsByType = [];
        }

        return view('admin.dashboard', compact(
            'stats', 
            'issues', 
            'loans', 
            'savings', 
            'investments', 
            'welfare',
            'assessments',
            'recentActivities',
            'performanceMetrics',
            'alerts',
            'roleStats',
            'usersByRole',
            'issuesByStatus',
            'loansByStatus',
            'monthlyTrends',
            'transactionsByType'
        ));
    }
}
