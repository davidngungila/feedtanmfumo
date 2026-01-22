<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FormulaController;
use App\Http\Controllers\Admin\InvestmentController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleDashboardController;
use App\Http\Controllers\Admin\SavingsAccountController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShareController;
use App\Http\Controllers\Admin\SocialWelfareController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\InvestmentController as MemberInvestmentController;
use App\Http\Controllers\Member\IssueController as MemberIssueController;
use App\Http\Controllers\Member\LoanController as MemberLoanController;
use App\Http\Controllers\Member\MembershipController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\SavingsController as MemberSavingsController;
use App\Http\Controllers\Member\WelfareController as MemberWelfareController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Member routes
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

        // Loans - require membership access
        Route::middleware('membership:loans')->group(function () {
            Route::resource('loans', MemberLoanController::class)->only(['index', 'show', 'create', 'store']);
        });

        // Savings - require membership access
        Route::middleware('membership:savings')->group(function () {
            Route::resource('savings', MemberSavingsController::class)->only(['index', 'show', 'create', 'store']);
        });

        // Investments - require membership access
        Route::middleware('membership:investments')->group(function () {
            Route::resource('investments', MemberInvestmentController::class)->only(['index', 'show', 'create', 'store']);
        });

        // Welfare - require membership access
        Route::middleware('membership:welfare')->group(function () {
            Route::resource('welfare', MemberWelfareController::class)->only(['index', 'show', 'create', 'store']);
        });

        // Issues - available to all members
        Route::resource('issues', MemberIssueController::class)->only(['index', 'show', 'create', 'store']);

        // Profile routes
        Route::get('profile', [MemberProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [MemberProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [MemberProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [MemberProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::get('profile/settings', [MemberProfileController::class, 'settings'])->name('profile.settings');
        Route::put('profile/settings', [MemberProfileController::class, 'updateSettings'])->name('profile.settings.update');

        // Membership Application - Individual Steps
        Route::get('membership/step/1', [MembershipController::class, 'showStep1'])->name('membership.step1');
        Route::post('membership/step/1', [MembershipController::class, 'storeStep1'])->name('membership.store-step1');
        
        Route::get('membership/step/2', [MembershipController::class, 'showStep2'])->name('membership.step2');
        Route::post('membership/step/2', [MembershipController::class, 'storeStep2'])->name('membership.store-step2');
        
        Route::get('membership/step/3', [MembershipController::class, 'showStep3'])->name('membership.step3');
        Route::post('membership/step/3', [MembershipController::class, 'storeStep3'])->name('membership.store-step3');
        
        Route::get('membership/step/4', [MembershipController::class, 'showStep4'])->name('membership.step4');
        Route::post('membership/step/4', [MembershipController::class, 'storeStep4'])->name('membership.store-step4');
        
        Route::get('membership/step/5', [MembershipController::class, 'showStep5'])->name('membership.step5');
        Route::post('membership/step/5', [MembershipController::class, 'storeStep5'])->name('membership.store-step5');
        
        Route::get('membership/step/6', [MembershipController::class, 'showStep6'])->name('membership.step6');
        Route::post('membership/step/6', [MembershipController::class, 'storeStep6'])->name('membership.store-step6');
        
        Route::get('membership/step/7', [MembershipController::class, 'showStep7'])->name('membership.step7');
        Route::post('membership/step/7', [MembershipController::class, 'storeStep7'])->name('membership.store-step7');
        
        Route::get('membership/step/8', [MembershipController::class, 'showStep8'])->name('membership.step8');
        Route::post('membership/step/8', [MembershipController::class, 'storeStep8'])->name('membership.store-step8');
        
        Route::get('membership/step/9', [MembershipController::class, 'showStep9'])->name('membership.step9');
        Route::post('membership/step/9', [MembershipController::class, 'storeStep9'])->name('membership.store-step9');
        
        Route::get('membership/step/10', [MembershipController::class, 'showStep10'])->name('membership.step10');
        Route::post('membership/step/10', [MembershipController::class, 'storeStep10'])->name('membership.store-step10');
        
        // Legacy route redirect
        Route::get('membership/application', function() {
            $user = Auth::user();
            $nextStep = $user->membership_application_current_step ?? 1;
            if ($nextStep == 0) $nextStep = 1;
            return redirect()->route('member.membership.step' . $nextStep);
        })->name('membership.application');
        
        Route::get('membership/status', [MembershipController::class, 'status'])->name('membership.status');
        Route::get('membership/preview', [MembershipController::class, 'preview'])->name('membership.preview');
        Route::get('membership/download-pdf', [MembershipController::class, 'downloadPdf'])->name('membership.download-pdf');

        // Member Guide
        Route::get('guide', [\App\Http\Controllers\Member\GuideController::class, 'index'])->name('guide');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/role-dashboard', [RoleDashboardController::class, 'index'])->name('role-dashboard');

    // Loans - Specific routes before resource
    Route::get('loans/pending-approvals', [LoanController::class, 'pendingApprovals'])->name('loans.pending-approvals');
    Route::get('loans/credit-assessment', [LoanController::class, 'creditAssessment'])->name('loans.credit-assessment');
    Route::get('loans/committee-review', [LoanController::class, 'committeeReview'])->name('loans.committee-review');
    Route::get('loans/approval-workflow', [LoanController::class, 'approvalWorkflow'])->name('loans.approval-workflow');
    Route::get('loans/disbursement', [LoanController::class, 'disbursement'])->name('loans.disbursement');
    Route::get('loans/active', [LoanController::class, 'activeLoans'])->name('loans.active');
    Route::get('loans/portfolio', [LoanController::class, 'portfolio'])->name('loans.portfolio');
    Route::get('loans/repayment-schedule', [LoanController::class, 'repaymentSchedule'])->name('loans.repayment-schedule');
    Route::get('loans/due-payments', [LoanController::class, 'duePayments'])->name('loans.due-payments');
    Route::get('loans/overdue', [LoanController::class, 'overdueLoans'])->name('loans.overdue');
    Route::get('loans/restructuring', [LoanController::class, 'restructuring'])->name('loans.restructuring');

    Route::resource('loans', LoanController::class);

    // Savings - Specific routes before resource
    Route::get('savings/deposits', [SavingsAccountController::class, 'deposits'])->name('savings.deposits');
    Route::get('savings/withdrawals', [SavingsAccountController::class, 'withdrawals'])->name('savings.withdrawals');
    Route::get('savings/transfers', [SavingsAccountController::class, 'transfers'])->name('savings.transfers');
    Route::get('savings/interest-posting', [SavingsAccountController::class, 'interestPosting'])->name('savings.interest-posting');
    Route::get('savings/statements', [SavingsAccountController::class, 'statements'])->name('savings.statements');
    Route::get('savings/close-account', [SavingsAccountController::class, 'closeAccount'])->name('savings.close-account');
    Route::get('savings/freeze-unfreeze', [SavingsAccountController::class, 'freezeUnfreeze'])->name('savings.freeze-unfreeze');
    Route::get('savings/upgrades', [SavingsAccountController::class, 'upgrades'])->name('savings.upgrades');
    Route::get('savings/minimum-balance', [SavingsAccountController::class, 'minimumBalance'])->name('savings.minimum-balance');
    Route::get('savings/total-balance', [SavingsAccountController::class, 'totalBalance'])->name('savings.total-balance');

    Route::resource('savings', SavingsAccountController::class);

    // Investments
    Route::resource('investments', InvestmentController::class);

    // Social Welfare - Specific routes before resource
    Route::get('welfare/fund-management', [SocialWelfareController::class, 'fundManagement'])->name('welfare.fund-management');
    Route::get('welfare/services', [SocialWelfareController::class, 'services'])->name('welfare.services');
    Route::get('welfare/claims-processing', [SocialWelfareController::class, 'claimsProcessing'])->name('welfare.claims-processing');
    Route::get('welfare/reports', [SocialWelfareController::class, 'reports'])->name('welfare.reports');

    Route::resource('welfare', SocialWelfareController::class);

    // Users - Place specific routes before resource route
    Route::get('users/roles', [UserController::class, 'roles'])->name('users.roles');
    Route::put('users/roles/{role}', [UserController::class, 'updateRolePermissions'])->name('users.roles.update');
    Route::get('users/directory', [UserController::class, 'directory'])->name('users.directory');
    Route::get('users/profiles', [UserController::class, 'profiles'])->name('users.profiles');
    Route::get('users/status', [UserController::class, 'status'])->name('users.status');
    Route::get('users/groups', [UserController::class, 'groups'])->name('users.groups');
    Route::get('users/kyc', [UserController::class, 'kyc'])->name('users.kyc');
    Route::get('users/history', [UserController::class, 'history'])->name('users.history');

    // Officials & Staff
    Route::get('users/officials/create', [UserController::class, 'createOfficial'])->name('users.officials.create');
    Route::post('users/officials', [UserController::class, 'storeOfficial'])->name('users.officials.store');

    // Membership Management
    Route::get('memberships', [\App\Http\Controllers\Admin\MembershipController::class, 'index'])->name('memberships.index');
    Route::get('memberships/{user}', [\App\Http\Controllers\Admin\MembershipController::class, 'show'])->name('memberships.show');
    Route::post('memberships/{user}/approve', [\App\Http\Controllers\Admin\MembershipController::class, 'approve'])->name('memberships.approve');
    Route::post('memberships/{user}/reject', [\App\Http\Controllers\Admin\MembershipController::class, 'reject'])->name('memberships.reject');
    Route::post('memberships/{user}/suspend', [\App\Http\Controllers\Admin\MembershipController::class, 'suspend'])->name('memberships.suspend');
    Route::post('memberships/{user}/reactivate', [\App\Http\Controllers\Admin\MembershipController::class, 'reactivate'])->name('memberships.reactivate');
    Route::get('users/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::get('users/login-history', [UserController::class, 'loginHistory'])->name('users.login-history');
    Route::get('users/activity-logs', [UserController::class, 'activityLogs'])->name('users.activity-logs');

    Route::resource('users', UserController::class);

    // Issues - Place specific routes before resource route
    Route::get('issues/tracking', [IssueController::class, 'tracking'])->name('issues.tracking');
    Route::get('issues/resolution-status', [IssueController::class, 'resolutionStatus'])->name('issues.resolution-status');
    Route::get('issues/categories', [IssueController::class, 'categories'])->name('issues.categories');
    Route::resource('issues', IssueController::class);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/loans', [ReportController::class, 'loans'])->name('reports.loans');
    Route::get('reports/savings', [ReportController::class, 'savings'])->name('reports.savings');
    Route::get('reports/investments', [ReportController::class, 'investments'])->name('reports.investments');
    Route::post('reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Settings - Specific routes before resource
    Route::get('settings/product-configuration', [SettingsController::class, 'productConfiguration'])->name('settings.product-configuration');
    Route::put('settings/product-configuration', [SettingsController::class, 'updateProductConfiguration'])->name('settings.product-configuration.update');
    Route::get('settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::put('settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.security.update');
    Route::get('settings/sms-templates', [SettingsController::class, 'smsTemplates'])->name('settings.sms-templates');
    Route::put('settings/sms-templates', [SettingsController::class, 'updateSmsTemplates'])->name('settings.sms-templates.update');
    Route::get('settings/email-settings', [SettingsController::class, 'emailSettings'])->name('settings.email-settings');
    Route::put('settings/email-settings', [SettingsController::class, 'updateEmailSettings'])->name('settings.email-settings.update');
    Route::get('settings/notification-preferences', [SettingsController::class, 'notificationPreferences'])->name('settings.notification-preferences');
    Route::put('settings/notification-preferences', [SettingsController::class, 'updateNotificationPreferences'])->name('settings.notification-preferences.update');
    Route::get('settings/reminder-settings', [SettingsController::class, 'reminderSettings'])->name('settings.reminder-settings');
    Route::put('settings/reminder-settings', [SettingsController::class, 'updateReminderSettings'])->name('settings.reminder-settings.update');

    // Settings main routes
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::put('settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('settings/organization', [SettingsController::class, 'organization'])->name('settings.organization');
    Route::put('settings/organization', [SettingsController::class, 'updateOrganization'])->name('settings.organization.update');
    Route::get('settings/communication', [SettingsController::class, 'communication'])->name('settings.communication');
    Route::put('settings/communication', [SettingsController::class, 'updateCommunication'])->name('settings.communication.update');

    // Shares Management
    Route::get('shares', [ShareController::class, 'index'])->name('shares.index');
    Route::get('shares/issue', [ShareController::class, 'issue'])->name('shares.issue');
    Route::get('shares/purchase', [ShareController::class, 'purchase'])->name('shares.purchase');
    Route::get('shares/transfer', [ShareController::class, 'transfer'])->name('shares.transfer');
    Route::get('shares/buyback', [ShareController::class, 'buyback'])->name('shares.buyback');
    Route::get('shares/cancellation', [ShareController::class, 'cancellation'])->name('shares.cancellation');
    Route::get('shares/price-setting', [ShareController::class, 'priceSetting'])->name('shares.price-setting');
    Route::get('shares/minimum-shares', [ShareController::class, 'minimumShares'])->name('shares.minimum-shares');
    Route::get('shares/maximum-shares', [ShareController::class, 'maximumShares'])->name('shares.maximum-shares');
    Route::get('shares/certificate-template', [ShareController::class, 'certificateTemplate'])->name('shares.certificate-template');
    Route::get('shares/dividend-policy', [ShareController::class, 'dividendPolicy'])->name('shares.dividend-policy');
    Route::get('shares/ownership-register', [ShareController::class, 'ownershipRegister'])->name('shares.ownership-register');
    Route::get('shares/balance-per-member', [ShareController::class, 'balancePerMember'])->name('shares.balance-per-member');
    Route::get('shares/transaction-history', [ShareController::class, 'transactionHistory'])->name('shares.transaction-history');
    Route::get('shares/certificates-issued', [ShareController::class, 'certificatesIssued'])->name('shares.certificates-issued');
    Route::get('shares/dividend-distribution', [ShareController::class, 'dividendDistribution'])->name('shares.dividend-distribution');
    Route::get('shares/capital-report', [ShareController::class, 'capitalReport'])->name('shares.capital-report');
    Route::get('shares/shareholder-register', [ShareController::class, 'shareholderRegister'])->name('shares.shareholder-register');
    Route::get('shares/transaction-ledger', [ShareController::class, 'transactionLedger'])->name('shares.transaction-ledger');
    Route::get('shares/dividend-report', [ShareController::class, 'dividendReport'])->name('shares.dividend-report');
    Route::get('shares/growth-analysis', [ShareController::class, 'growthAnalysis'])->name('shares.growth-analysis');

    // Formula Engine
    Route::get('formulas', [FormulaController::class, 'index'])->name('formulas.index');
    // Loans Formulas
    Route::get('formulas/loans/interest', [FormulaController::class, 'loansInterest'])->name('formulas.loans.interest');
    Route::get('formulas/loans/fees', [FormulaController::class, 'loansFees'])->name('formulas.loans.fees');
    Route::get('formulas/loans/limits', [FormulaController::class, 'loansLimits'])->name('formulas.loans.limits');
    Route::get('formulas/loans/repayment', [FormulaController::class, 'loansRepayment'])->name('formulas.loans.repayment');
    // Savings Formulas
    Route::get('formulas/savings/interest', [FormulaController::class, 'savingsInterest'])->name('formulas.savings.interest');
    Route::get('formulas/savings/account-specific', [FormulaController::class, 'savingsAccountSpecific'])->name('formulas.savings.account-specific');
    // Investments Formulas
    Route::get('formulas/investments/4-year', [FormulaController::class, 'investments4Year'])->name('formulas.investments.4-year');
    Route::get('formulas/investments/6-year', [FormulaController::class, 'investments6Year'])->name('formulas.investments.6-year');
    Route::get('formulas/investments/performance', [FormulaController::class, 'investmentsPerformance'])->name('formulas.investments.performance');
    // Shares Formulas
    Route::get('formulas/shares/value', [FormulaController::class, 'sharesValue'])->name('formulas.shares.value');
    Route::get('formulas/shares/dividend', [FormulaController::class, 'sharesDividend'])->name('formulas.shares.dividend');
    Route::get('formulas/shares/pricing', [FormulaController::class, 'sharesPricing'])->name('formulas.shares.pricing');
    // Welfare Formulas
    Route::get('formulas/welfare/contribution', [FormulaController::class, 'welfareContribution'])->name('formulas.welfare.contribution');
    Route::get('formulas/welfare/benefit', [FormulaController::class, 'welfareBenefit'])->name('formulas.welfare.benefit');
    Route::get('formulas/welfare/eligibility', [FormulaController::class, 'welfareEligibility'])->name('formulas.welfare.eligibility');
    // Fees & Charges
    Route::get('formulas/fees/membership', [FormulaController::class, 'feesMembership'])->name('formulas.fees.membership');
    Route::get('formulas/fees/transaction', [FormulaController::class, 'feesTransaction'])->name('formulas.fees.transaction');
    Route::get('formulas/fees/service', [FormulaController::class, 'feesService'])->name('formulas.fees.service');
    // Tax & Compliance
    Route::get('formulas/tax/calculations', [FormulaController::class, 'taxCalculations'])->name('formulas.tax.calculations');
    Route::get('formulas/tax/reserves', [FormulaController::class, 'taxReserves'])->name('formulas.tax.reserves');
    // Performance Metrics
    Route::get('formulas/metrics/financial-ratios', [FormulaController::class, 'metricsFinancialRatios'])->name('formulas.metrics.financial-ratios');
    Route::get('formulas/metrics/portfolio-quality', [FormulaController::class, 'metricsPortfolioQuality'])->name('formulas.metrics.portfolio-quality');
    // Commission & Incentives
    Route::get('formulas/commission/staff', [FormulaController::class, 'commissionStaff'])->name('formulas.commission.staff');
    Route::get('formulas/commission/member', [FormulaController::class, 'commissionMember'])->name('formulas.commission.member');
    // Formula Builder
    Route::get('formulas/builder', [FormulaController::class, 'builder'])->name('formulas.builder');
    Route::get('formulas/builder/variables', [FormulaController::class, 'builderVariables'])->name('formulas.builder.variables');
    Route::get('formulas/builder/testing', [FormulaController::class, 'builderTesting'])->name('formulas.builder.testing');
    Route::get('formulas/builder/history', [FormulaController::class, 'builderHistory'])->name('formulas.builder.history');
    // Formula Reports
    Route::get('formulas/reports/audit', [FormulaController::class, 'reportsAudit'])->name('formulas.reports.audit');
    Route::get('formulas/reports/calculation', [FormulaController::class, 'reportsCalculation'])->name('formulas.reports.calculation');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
});
