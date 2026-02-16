@php
    $user = Auth::user();
    $isAdmin = $user->isAdmin() || $user->hasRole('admin');
    $isLoanOfficer = $user->hasRole('loan_officer') || $isAdmin;
    $isDepositOfficer = $user->hasRole('deposit_officer') || $isAdmin;
    $isInvestmentOfficer = $user->hasRole('investment_officer') || $isAdmin;
    $isChairperson = $user->hasRole('chairperson') || $isAdmin;
    $isSecretary = $user->hasRole('secretary') || $isAdmin;
    $isAccountant = $user->hasRole('accountant') || $isAdmin;
    $canViewAll = $isAdmin || $isChairperson || $isSecretary || $isAccountant;
    
    // Active route checking
    $isActiveUsers = request()->routeIs(['admin.users.*', 'admin.users.index', 'admin.users.create', 'admin.users.show', 'admin.users.edit', 'admin.users.directory', 'admin.users.profiles', 'admin.users.status', 'admin.users.groups', 'admin.users.kyc', 'admin.users.history', 'admin.users.officials.*', 'admin.users.roles', 'admin.users.permissions', 'admin.users.login-history', 'admin.users.activity-logs']);
    $isActiveIssues = request()->routeIs(['admin.issues.*']);
    $isActiveLoans = request()->routeIs(['admin.loans.*', 'admin.loans.sms-reminders.*']);
    $isActiveSavings = request()->routeIs(['admin.savings.*']);
    $isActiveInvestments = request()->routeIs(['admin.investments.*']);
    $isActiveWelfare = request()->routeIs(['admin.welfare.*']);
    $isActiveReports = request()->routeIs(['admin.reports.*']);
    $isActiveSettings = request()->routeIs(['admin.settings.*', 'admin.system-settings.*']);
    $isActiveShares = request()->routeIs(['admin.shares.*']);
    $isActiveFormulas = request()->routeIs(['admin.formulas.*']);
    $isActivePaymentConfirmations = request()->routeIs(['admin.payment-confirmations.*']);
    $isActiveMonthlyDeposits = request()->routeIs(['admin.monthly-deposits.*']);
@endphp

<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#013019]' : '' }}">
    <span class="text-lg mr-3">📊</span>
    <span>Dashboard</span>
</a>

@if($canViewAll)
<!-- Users -->
<div class="dropdown-container" data-menu="users">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveUsers ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">👥</span>
            <span>Users</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveUsers ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveUsers ? '' : 'hidden' }}">
        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Users</a>
        <a href="{{ route('admin.memberships.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Memberships</a>
        <!-- Members -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Members</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Register New Member</a>
                <a href="{{ route('admin.users.directory') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member Directory</a>
                <a href="{{ route('admin.users.profiles') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member Profiles</a>
                <a href="{{ route('admin.users.status') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Membership Status</a>
                <a href="{{ route('admin.users.groups') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member Groups/Clusters</a>
                <a href="{{ route('admin.users.kyc') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">KYC Documents</a>
                <a href="{{ route('admin.users.history') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member History</a>
            </div>
        </div>
        <!-- Officials & Staff -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Officials & Staff</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.users.officials.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Add Official</a>
                <a href="{{ route('admin.users.roles') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Role Management</a>
                <a href="{{ route('admin.users.permissions') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">User Permissions</a>
                <a href="{{ route('admin.users.login-history') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Login History</a>
                <a href="{{ route('admin.users.activity-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Activity Logs</a>
            </div>
        </div>
        <!-- User Activities -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>User Activities</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.users.login-history') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Login/Logout Tracking</a>
                <a href="{{ route('admin.users.activity-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Transaction History</a>
                <a href="{{ route('admin.users.activity-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Activity Reports</a>
                <a href="{{ route('admin.users.activity-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Audit Trails</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($canViewAll)
<!-- Issues -->
<div class="dropdown-container" data-menu="issues">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveIssues ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">⚙️</span>
            <span>Issues</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveIssues ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveIssues ? '' : 'hidden' }}">
        <a href="{{ route('admin.issues.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Issues</a>
        <!-- Technical Issues -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Technical Issues</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.issues.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Report Issue</a>
                <a href="{{ route('admin.issues.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Open Tickets</a>
                <a href="{{ route('admin.issues.tracking') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Issue Tracking</a>
                <a href="{{ route('admin.issues.resolution-status') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Resolution Status</a>
                <a href="{{ route('admin.issues.categories') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Issue Categories (Login, Transactions, Reports, etc.)</a>
            </div>
        </div>
        <!-- Operational Issues -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Operational Issues</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.issues.index', ['category' => 'loan_defaults']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Defaults</a>
                <a href="{{ route('admin.issues.index', ['category' => 'savings_irregularities']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Savings Irregularities</a>
                <a href="{{ route('admin.issues.index', ['category' => 'investment_issues']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Investment Issues</a>
                <a href="{{ route('admin.issues.index', ['category' => 'welfare_disputes']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Welfare Disputes</a>
                <a href="{{ route('admin.issues.index', ['category' => 'compliance']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Compliance Issues</a>
            </div>
        </div>
        <!-- Issue Management -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Issue Management</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.issues.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Assign Issues</a>
                <a href="{{ route('admin.issues.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Priority Settings</a>
                <a href="{{ route('admin.issues.tracking') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Escalation Matrix</a>
                <a href="{{ route('admin.issues.resolution-status') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Resolution Reports</a>
                <a href="{{ route('admin.issues.tracking') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Issue Analytics</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isLoanOfficer || $canViewAll)
<!-- Loans -->
<div class="dropdown-container" data-menu="loans">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveLoans ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">💰</span>
            <span>Loans</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveLoans ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveLoans ? '' : 'hidden' }}">
        <a href="{{ route('admin.loans.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Loans</a>
        <!-- Loan Products -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loan Products</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.loans.index', ['purpose' => 'business']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Business Loans</a>
                <a href="{{ route('admin.loans.index', ['purpose' => 'emergency']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Emergency Loans</a>
                <a href="{{ route('admin.loans.index', ['purpose' => 'agricultural']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Agricultural Loans</a>
                <a href="{{ route('admin.loans.index', ['purpose' => 'education']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Education Loans</a>
                <a href="{{ route('admin.loans.index', ['purpose' => 'asset_financing']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Asset Financing</a>
            </div>
        </div>
        <!-- Loan Processing -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loan Processing</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.loans.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">New Loan Application</a>
                <a href="{{ route('admin.loans.pending-approvals') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Pending Approvals</a>
                <a href="{{ route('admin.loans.credit-assessment') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Credit Assessment</a>
                <a href="{{ route('admin.loans.committee-review') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Committee Review</a>
                <a href="{{ route('admin.loans.approval-workflow') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Approval Workflow</a>
                <a href="{{ route('admin.loans.disbursement') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Disbursement</a>
            </div>
        </div>
        <!-- Active Loans -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Active Loans</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.loans.active') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Active Loans</a>
                <a href="{{ route('admin.loans.portfolio') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Portfolio</a>
                <a href="{{ route('admin.loans.repayment-schedule') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Repayment Schedule</a>
                <a href="{{ route('admin.loans.due-payments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Due Payments</a>
                <a href="{{ route('admin.loans.overdue') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Overdue Loans</a>
                <a href="{{ route('admin.loans.sms-reminders.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">SMS Reminders</a>
                <a href="{{ route('admin.loans.restructuring') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Restructuring</a>
            </div>
        </div>
        <!-- Loan Operations -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loan Operations</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.loans.active') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Record Payment</a>
                <a href="{{ route('admin.loans.active') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Post Interest</a>
                <a href="{{ route('admin.loans.overdue') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Calculate Penalties</a>
                <a href="{{ route('admin.loans.active') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Closure</a>
                <a href="{{ route('admin.loans.overdue') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Write-off Management</a>
            </div>
        </div>
        <!-- Loan Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loan Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.loans') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Book Summary</a>
                <a href="{{ route('admin.reports.loans') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Default Rate Analysis</a>
                <a href="{{ route('admin.reports.loans') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Income Report</a>
                <a href="{{ route('admin.reports.loans') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Portfolio at Risk</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isDepositOfficer || $canViewAll)
<!-- Savings -->
<div class="dropdown-container" data-menu="savings">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveSavings ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">🏦</span>
            <span>Savings</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveSavings ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveSavings ? '' : 'hidden' }}">
        <a href="{{ route('admin.savings.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Savings</a>
        <!-- Account Types -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Account Types</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.index', ['account_type' => 'emergency']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Emergency Savings Account</a>
                <a href="{{ route('admin.savings.index', ['account_type' => 'rda']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Recurrent Deposit Account (RDA)</a>
                <a href="{{ route('admin.savings.index', ['account_type' => 'flex']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Flex Account</a>
                <a href="{{ route('admin.savings.index', ['account_type' => 'business']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Business Savings Account</a>
            </div>
        </div>
        <!-- Savings Operations -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Savings Operations</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.deposits') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Deposit Transactions</a>
                <a href="{{ route('admin.savings.withdrawals') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Withdrawal Requests</a>
                <a href="{{ route('admin.savings.transfers') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Transfer Between Accounts</a>
                <a href="{{ route('admin.savings.interest-posting') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Posting</a>
                <a href="{{ route('admin.savings.statements') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Statements</a>
            </div>
        </div>
        <!-- Account Management -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Account Management</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Open New Account</a>
                <a href="{{ route('admin.savings.close-account') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Close Account</a>
                <a href="{{ route('admin.savings.freeze-unfreeze') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Freeze/Unfreeze</a>
                <a href="{{ route('admin.savings.upgrades') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Upgrades</a>
                <a href="{{ route('admin.savings.minimum-balance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Minimum Balance Monitoring</a>
            </div>
        </div>
        <!-- Savings Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Savings Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.total-balance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Total Savings Balance</a>
                <a href="{{ route('admin.reports.savings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Growth Trend</a>
                <a href="{{ route('admin.reports.savings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Deposit/Withdrawal Summary</a>
                <a href="{{ route('admin.reports.savings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Accrued Report</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isDepositOfficer || $isAccountant || $canViewAll)
<!-- Interest Payment Management -->
<div class="dropdown-container" data-menu="payment-confirmations">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActivePaymentConfirmations ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">💳</span>
            <span>Interest Payment</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActivePaymentConfirmations ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActivePaymentConfirmations ? '' : 'hidden' }}">
        <a href="{{ route('admin.payment-confirmations.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Payment Confirmations</a>
        <a href="{{ route('admin.payment-confirmations.upload') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Upload Excel Sheet</a>
    </div>
</div>

<!-- Monthly Deposits -->
<div class="dropdown-container" data-menu="monthly-deposits">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveMonthlyDeposits ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">📅</span>
            <span>Monthly Deposits</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveMonthlyDeposits ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveMonthlyDeposits ? '' : 'hidden' }}">
        <a href="{{ route('admin.monthly-deposits.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Upload History</a>
        <a href="{{ route('admin.monthly-deposits.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Upload Excel Sheet</a>
    </div>
</div>
@endif

@if($isInvestmentOfficer || $canViewAll)
<!-- Investments -->
<div class="dropdown-container" data-menu="investments">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveInvestments ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">📈</span>
            <span>Investments</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveInvestments ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveInvestments ? '' : 'hidden' }}">
        <a href="{{ route('admin.investments.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Investments</a>
        <!-- Investment Products -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Investment Products</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.investments.index', ['plan_type' => '4_year']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">4-Year Investment Plan</a>
                <a href="{{ route('admin.investments.index', ['plan_type' => '6_year']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">6-Year Investment Plan</a>
            </div>
        </div>
        <!-- Investment Operations -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Investment Operations</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.investments.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">New Investment Enrollment</a>
                <a href="{{ route('admin.investments.index', ['status' => 'active']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Investment Top-up</a>
                <a href="{{ route('admin.investments.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Maturity Calculation</a>
                <a href="{{ route('admin.investments.index', ['status' => 'matured']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Profit Distribution</a>
                <a href="{{ route('admin.investments.index', ['status' => 'active']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Early Withdrawal</a>
                <a href="{{ route('admin.investments.index', ['status' => 'matured']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Renewal Processing</a>
            </div>
        </div>
        <!-- Investment Tracking -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Investment Tracking</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.investments.index', ['status' => 'active']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Active Investments</a>
                <a href="{{ route('admin.investments.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Maturity Calendar</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Investment Performance</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Portfolio Value</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Projected Returns</a>
            </div>
        </div>
        <!-- Investment Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Investment Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Investment Summary</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Maturity Schedule</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Return on Investment</a>
                <a href="{{ route('admin.reports.investments') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Comparative Analysis</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($canViewAll)
<!-- Welfare -->
<div class="dropdown-container" data-menu="welfare">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveWelfare ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">❤️</span>
            <span>Welfare</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveWelfare ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveWelfare ? '' : 'hidden' }}">
        <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Welfare</a>
        <!-- Fund Management -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Fund Management</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Contribution Collection</a>
                <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Fund Balance</a>
                <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Investment of Welfare Fund</a>
                <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Fund Utilization Rate</a>
            </div>
        </div>
        <!-- Welfare Services -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Welfare Services</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.welfare.index', ['type' => 'medical']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Medical Support Claims</a>
                <a href="{{ route('admin.welfare.index', ['type' => 'funeral']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Funeral Assistance</a>
                <a href="{{ route('admin.welfare.index', ['type' => 'education']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Education Support</a>
                <a href="{{ route('admin.welfare.index', ['type' => 'emergency']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Emergency Grants</a>
                <a href="{{ route('admin.welfare.index', ['type' => 'special_needs']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Special Needs Support</a>
            </div>
        </div>
        <!-- Claims Processing -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Claims Processing</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.welfare.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">New Claim Application</a>
                <a href="{{ route('admin.welfare.index', ['status' => 'pending']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Claim Verification</a>
                <a href="{{ route('admin.welfare.index', ['status' => 'pending']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Approval Workflow</a>
                <a href="{{ route('admin.welfare.index', ['status' => 'approved']) }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Disbursement Processing</a>
                <a href="{{ route('admin.welfare.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Claim History</a>
            </div>
        </div>
        <!-- Welfare Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Welfare Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.welfare.reports.fund-status') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Fund Status Report</a>
                <a href="{{ route('admin.welfare.reports.claim-analysis') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Claim Analysis</a>
                <a href="{{ route('admin.welfare.reports.beneficiary') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Beneficiary Reports</a>
                <a href="{{ route('admin.welfare.reports.utilization') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Utilization Statistics</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isAccountant || $canViewAll)
<!-- Reports -->
<div class="dropdown-container" data-menu="reports">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveReports ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">📋</span>
            <span>Reports</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveReports ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveReports ? '' : 'hidden' }}">
        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Reports</a>
        <!-- Financial Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Financial Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.financial') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Balance Sheet</a>
                <a href="{{ route('admin.reports.financial') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Income Statement</a>
                <a href="{{ route('admin.reports.financial') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Cash Flow Statement</a>
                <a href="{{ route('admin.reports.financial') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Trial Balance</a>
                <a href="{{ route('admin.reports.financial') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">General Ledger</a>
            </div>
        </div>
        <!-- Operational Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Operational Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Daily Transaction Report</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Monthly Performance Report</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member Activity Report</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Product Performance Report</a>
            </div>
        </div>
        <!-- Compliance Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Compliance Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Regulatory Reports</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Audit Reports</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Tax Reports</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Meeting Minutes Reports</a>
            </div>
        </div>
        <!-- Custom Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Custom Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Report Builder</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Custom Queries</a>
                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Export Options (PDF, Excel, Print)</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($canViewAll)
<!-- Shares Management -->
<div class="dropdown-container" data-menu="shares">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveShares ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">🔵</span>
            <span>Shares</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveShares ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveShares ? '' : 'hidden' }}">
        <a href="{{ route('admin.shares.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Share Management</a>
        <!-- Share Capital Operations -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Share Capital Operations</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.shares.issue') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Issue New Shares</a>
                <a href="{{ route('admin.shares.purchase') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Purchase</a>
                <a href="{{ route('admin.shares.transfer') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Transfer</a>
                <a href="{{ route('admin.shares.buyback') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Buyback</a>
                <a href="{{ route('admin.shares.cancellation') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Cancellation</a>
            </div>
        </div>
        <!-- Share Configuration -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Share Configuration</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.shares.price-setting') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Price Setting</a>
                <a href="{{ route('admin.shares.minimum-shares') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Minimum Shares per Member</a>
                <a href="{{ route('admin.shares.maximum-shares') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Maximum Shares per Member</a>
                <a href="{{ route('admin.shares.certificate-template') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Certificate Template</a>
                <a href="{{ route('admin.shares.dividend-policy') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Dividend Policy Settings</a>
            </div>
        </div>
        <!-- Member Shares -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Member Shares</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.shares.ownership-register') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Ownership Register</a>
                <a href="{{ route('admin.shares.balance-per-member') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Balance per Member</a>
                <a href="{{ route('admin.shares.transaction-history') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Transaction History</a>
                <a href="{{ route('admin.shares.certificates-issued') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Certificates Issued</a>
                <a href="{{ route('admin.shares.dividend-distribution') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Dividend Distribution List</a>
            </div>
        </div>
        <!-- Share Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Share Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.shares.capital-report') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Capital Report</a>
                <a href="{{ route('admin.shares.shareholder-register') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Shareholder Register</a>
                <a href="{{ route('admin.shares.transaction-ledger') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Transaction Ledger</a>
                <a href="{{ route('admin.shares.dividend-report') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Dividend Distribution Report</a>
                <a href="{{ route('admin.shares.growth-analysis') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Capital Growth Analysis</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isAdmin)
<!-- Formula Engine -->
<div class="dropdown-container" data-menu="formulas">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveFormulas ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">⚙️</span>
            <span>Formulas</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveFormulas ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveFormulas ? '' : 'hidden' }}">
        <a href="{{ route('admin.formulas.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Formula Management</a>
        <!-- Loans Formulas -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loans Formulas</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.loans.interest') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Calculation</a>
                <a href="{{ route('admin.formulas.loans.fees') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Fee Formulas</a>
                <a href="{{ route('admin.formulas.loans.limits') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Limits & Ratios</a>
                <a href="{{ route('admin.formulas.loans.repayment') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Repayment Formulas</a>
            </div>
        </div>
        <!-- Savings Formulas -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Savings Formulas</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.savings.interest') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Calculation</a>
                <a href="{{ route('admin.formulas.savings.account-specific') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account-Specific Formulas</a>
            </div>
        </div>
        <!-- Investments Formulas -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Investments Formulas</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.investments.4-year') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">4-Year Plan Formulas</a>
                <a href="{{ route('admin.formulas.investments.6-year') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">6-Year Plan Formulas</a>
                <a href="{{ route('admin.formulas.investments.performance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Performance Metrics</a>
            </div>
        </div>
        <!-- Shares Formulas -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Shares Formulas</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.shares.value') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Value Formulas</a>
                <a href="{{ route('admin.formulas.shares.dividend') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Dividend Formulas</a>
                <a href="{{ route('admin.formulas.shares.pricing') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Share Pricing Models</a>
            </div>
        </div>
        <!-- Welfare Formulas -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Welfare Formulas</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.welfare.contribution') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Contribution Formulas</a>
                <a href="{{ route('admin.formulas.welfare.benefit') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Benefit Calculation</a>
                <a href="{{ route('admin.formulas.welfare.eligibility') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Eligibility Formulas</a>
            </div>
        </div>
        <!-- Fees & Charges -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Fees & Charges</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.fees.membership') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Membership Fees</a>
                <a href="{{ route('admin.formulas.fees.transaction') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Transaction Fees</a>
                <a href="{{ route('admin.formulas.fees.service') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Service Fees</a>
            </div>
        </div>
        <!-- Tax & Compliance -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Tax & Compliance</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.tax.calculations') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Tax Calculations</a>
                <a href="{{ route('admin.formulas.tax.reserves') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Reserve Requirements</a>
            </div>
        </div>
        <!-- Performance Metrics -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Performance Metrics</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.metrics.financial-ratios') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Financial Ratios</a>
                <a href="{{ route('admin.formulas.metrics.portfolio-quality') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Portfolio Quality</a>
            </div>
        </div>
        <!-- Commission & Incentives -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Commission & Incentives</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.commission.staff') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Staff Commissions</a>
                <a href="{{ route('admin.formulas.commission.member') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Member Incentives</a>
            </div>
        </div>
        <!-- Formula Builder -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Formula Builder</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.builder') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Formula Editor</a>
                <a href="{{ route('admin.formulas.builder.variables') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Formula Variables</a>
                <a href="{{ route('admin.formulas.builder.testing') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Formula Testing</a>
                <a href="{{ route('admin.formulas.builder.history') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Formula History</a>
            </div>
        </div>
        <!-- Formula Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Formula Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.formulas.reports.audit') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Formula Audit Reports</a>
                <a href="{{ route('admin.formulas.reports.calculation') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Calculation Verification</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($isAdmin)
<!-- Settings -->
<div class="dropdown-container" data-menu="settings">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveSettings ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">⚙️</span>
            <span>Settings</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveSettings ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveSettings ? '' : 'hidden' }}">
        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Settings</a>
        
        <!-- 🔐 Users -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🔐 Users</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.users') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Users</a>
                <a href="{{ route('admin.system-settings.roles') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Roles</a>
                <a href="{{ route('admin.system-settings.permissions') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Permissions</a>
                <a href="{{ route('admin.system-settings.role-assignment') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Role Assignment</a>
                <a href="{{ route('admin.system-settings.login-sessions') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Login Sessions</a>
                <a href="{{ route('admin.system-settings.password-policy') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Password Policy</a>
            </div>
        </div>

        <!-- 🏢 Organization -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🏢 Organization</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.system-information') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">System Information</a>
                <a href="{{ route('admin.system-settings.organization-profile') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Organization Profile</a>
                <a href="{{ route('admin.system-settings.contact-details') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Contact Details</a>
                <a href="{{ route('admin.system-settings.logo-branding') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Logo & Branding</a>
                <a href="{{ route('admin.system-settings.language-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Language Settings</a>
                <a href="{{ route('admin.system-settings.timezone-date-format') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Timezone & Date Format</a>
            </div>
        </div>

        <!-- ⚙️ Application -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>⚙️ Application</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.general-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">General Settings</a>
                <a href="{{ route('admin.system-settings.feature-toggles') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Feature Toggles</a>
                <a href="{{ route('admin.system-settings.maintenance-mode') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Maintenance Mode</a>
                <a href="{{ route('admin.system-settings.default-values') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Default Values</a>
                <a href="{{ route('admin.system-settings.system-preferences') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">System Preferences</a>
            </div>
        </div>

        <!-- 🔔 Notifications -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🔔 Notifications</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.email-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Email Settings (SMTP)</a>
                <a href="{{ route('admin.system-settings.sms-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">SMS Settings</a>
                <a href="{{ route('admin.system-settings.push-notifications') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Push Notifications</a>
                <a href="{{ route('admin.system-settings.notification-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Notification Templates</a>
                <a href="{{ route('admin.system-settings.alert-rules') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Alert Rules</a>
            </div>
        </div>

        <!-- 🗂️ Data -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🗂️ Data</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.backup-restore') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Backup & Restore</a>
                <a href="{{ route('admin.system-settings.import-data') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Import Data</a>
                <a href="{{ route('admin.system-settings.export-data') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Export Data</a>
                <a href="{{ route('admin.system-settings.database-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Database Settings</a>
                <a href="{{ route('admin.system-settings.data-retention-policy') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Data Retention Policy</a>
            </div>
        </div>

        <!-- 🔒 Security -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🔒 Security</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.security-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Security Settings</a>
                <a href="{{ route('admin.system-settings.two-factor-auth') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Two-Factor Authentication (2FA)</a>
                <a href="{{ route('admin.system-settings.ip-whitelisting') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">IP Whitelisting / Blacklisting</a>
                <a href="{{ route('admin.system-settings.audit-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Audit Logs</a>
                <a href="{{ route('admin.system-settings.activity-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Activity Logs</a>
            </div>
        </div>

        <!-- 📄 Templates -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>📄 Templates</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.pdf-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">PDF Templates</a>
                <a href="{{ route('admin.system-settings.email-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Email Templates</a>
                <a href="{{ route('admin.system-settings.report-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Report Templates</a>
                <a href="{{ route('admin.system-settings.certificate-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Certificate Templates</a>
            </div>
        </div>

        <!-- 🔌 Integrations -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🔌 Integrations</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.api-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">API Settings</a>
                <a href="{{ route('admin.system-settings.payment-gateways') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Payment Gateways</a>
                <a href="{{ route('admin.system-settings.third-party-services') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Third-party Services</a>
                <a href="{{ route('admin.system-settings.webhooks') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Webhooks</a>
            </div>
        </div>

        <!-- 🧰 Tools -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🧰 Tools</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.cache-management') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Cache Management</a>
                <a href="{{ route('admin.system-settings.system-logs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">System Logs</a>
                <a href="{{ route('admin.system-settings.queue-jobs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Queue Jobs</a>
                <a href="{{ route('admin.system-settings.cron-jobs') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Cron Jobs</a>
                <a href="{{ route('admin.system-settings.debug-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Debug Settings</a>
            </div>
        </div>

        <!-- 🔄 Updates -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>🔄 Updates</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.system-updates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">System Updates</a>
                <a href="{{ route('admin.system-settings.version-info') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Version Info</a>
                <a href="{{ route('admin.system-settings.changelog') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Changelog</a>
                <a href="{{ route('admin.system-settings.cache-management') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Clear Cache</a>
                <a href="{{ route('admin.system-settings.system-updates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Optimize System</a>
            </div>
        </div>

        <!-- 📊 Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>📊 Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.system-reports') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">System Reports</a>
                <a href="{{ route('admin.system-settings.usage-statistics') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Usage Statistics</a>
                <a href="{{ route('admin.system-settings.performance-monitoring') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Performance Monitoring</a>
                <a href="{{ route('admin.system-settings.error-reports') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Error Reports</a>
            </div>
        </div>

        <!-- Bonus Features -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>✨ Bonus</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.system-settings.custom-fields') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Custom Fields</a>
                <a href="{{ route('admin.system-settings.module-management') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Module Management</a>
                <a href="{{ route('admin.system-settings.menu-builder') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Menu Builder</a>
                <a href="{{ route('admin.system-settings.theme-appearance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Theme / Appearance</a>
                <a href="{{ route('admin.system-settings.license-management') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">License Management</a>
            </div>
        </div>
    </div>
</div>
@endif

