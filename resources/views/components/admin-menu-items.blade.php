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
    $isActivePayments = request()->routeIs(['admin.payments.*']);
    $isActiveFiaPayments = request()->routeIs(['admin.fia-payments.*']);
    $isActiveFiaPaymentRecords = request()->routeIs(['admin.fia-payment-records.*']);
@endphp

<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#013019]' : '' }}">
    <span class="text-lg mr-3">📊</span>
    <span>Dashboard</span>
</a>

@if($canViewAll)
<!-- Members -->
<div class="dropdown-container" data-menu="users">
    <button class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveUsers ? 'bg-[#013019]' : '' }}">
        <div class="flex items-center">
            <span class="text-lg mr-3">👥</span>
            <span>Members</span>
        </div>
        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveUsers ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveUsers ? '' : 'hidden' }}">
        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Members</a>
        <a href="{{ route('admin.memberships.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Memberships Request</a>
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
        <a href="{{ route('admin.savings.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Savings Accounts</a>
        <a href="{{ route('admin.savings.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">New Savings Account</a>
        
        <!-- Deposits & Withdrawals -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Deposits & Withdrawals</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.deposits') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Deposits</a>
                <a href="{{ route('admin.savings.withdrawals') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Withdrawals</a>
                <a href="{{ route('admin.savings.transfers') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Transfers</a>
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
                <a href="{{ route('admin.savings.close-account') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Close Account</a>
                <a href="{{ route('admin.savings.freeze-unfreeze') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Freeze/Unfreeze</a>
                <a href="{{ route('admin.savings.upgrades') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Upgrades</a>
            </div>
        </div>
        
        <!-- Interest & Balances -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Interest & Balances</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.interest-posting') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Interest Posting</a>
                <a href="{{ route('admin.savings.minimum-balance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Minimum Balance</a>
                <a href="{{ route('admin.savings.total-balance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Total Balance</a>
            </div>
        </div>
        
        <!-- Reports & Statements -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Reports & Statements</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.savings.statements') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Account Statements</a>
                <a href="{{ route('admin.reports.savings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Savings Reports</a>
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
        <!-- Guarantor Assessments -->
        <a href="{{ route('admin.guarantor-assessments.index') }}" class="block px-4 py-2 mt-1 rounded-md hover:bg-[#013019] transition text-sm {{ request()->routeIs('admin.guarantor-assessments.*') ? 'bg-[#013019] font-bold' : '' }}">
            <span>Guarantor Assessments</span>
        </a>
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
        <!-- Loan Reports -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Loan Reports</span>
                <svg class="w-3 h-3 nested-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="nested-dropdown-menu hidden pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.loans.portfolio') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Portfolio</a>
                <a href="{{ route('admin.reports.loans.performance') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Loan Performance</a>
                <a href="{{ route('admin.reports.loans.repayment') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Repayment Reports</a>
                <a href="{{ route('admin.reports.loans.defaults') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-xs">Default Reports</a>
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
        <!-- Commission -->
        <div class="nested-dropdown-container">
            <button class="nested-dropdown-toggle flex items-center justify-between w-full px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">
                <span>Commission</span>
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
        
        <!-- Basic Settings -->
        <a href="{{ route('admin.settings.system') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">System Settings</a>
        <a href="{{ route('admin.settings.organization') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Organization Settings</a>
        <a href="{{ route('admin.settings.product-configuration') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Product Configuration</a>
        <a href="{{ route('admin.settings.security') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Security Settings</a>
        <a href="{{ route('admin.settings.communication') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Communication Settings</a>
        <a href="{{ route('admin.settings.email-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Email Templates</a>
        <a href="{{ route('admin.settings.sms-templates') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">SMS Templates</a>
        <a href="{{ route('admin.settings.notification-preferences') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Notification Preferences</a>
        <a href="{{ route('admin.settings.reminder-settings') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Reminder Settings</a>
    </div>
</div>
@endif
