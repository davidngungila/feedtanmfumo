<?php

use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Mobile\DashboardController;
use App\Http\Controllers\Api\Mobile\FileUploadController;
use App\Http\Controllers\Api\Mobile\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
*/

// Public API routes
Route::prefix('mobile/v1')->group(function () {
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

    // API Documentation/Contract
    Route::get('/guide', [DashboardController::class, 'apiGuide']);
});

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->prefix('mobile/v1')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/alerts', [DashboardController::class, 'alerts']);

    // Loans
    Route::get('/loans', [\App\Http\Controllers\Api\Mobile\LoanController::class, 'index']);
    Route::get('/loans/{loan}', [\App\Http\Controllers\Api\Mobile\LoanController::class, 'show']);
    Route::post('/loans', [\App\Http\Controllers\Api\Mobile\LoanController::class, 'store']);
    Route::get('/loans/{loan}/schedule', [\App\Http\Controllers\Api\Mobile\LoanController::class, 'schedule']);
    Route::post('/loans/{loan}/pay', [\App\Http\Controllers\Api\Mobile\LoanController::class, 'pay']);

    // Savings
    Route::get('/savings', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'index']);
    Route::get('/savings/{savings}', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'show']);
    Route::post('/savings', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'store']);
    Route::post('/savings/{savings}/deposit', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'deposit']);
    Route::post('/savings/{savings}/withdraw', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'withdraw']);
    Route::get('/savings/{savings}/statements', [\App\Http\Controllers\Api\Mobile\SavingsController::class, 'statements']);

    // Investments
    Route::get('/investments', [\App\Http\Controllers\Api\Mobile\InvestmentController::class, 'index']);
    Route::get('/investments/{investment}', [\App\Http\Controllers\Api\Mobile\InvestmentController::class, 'show']);
    Route::post('/investments', [\App\Http\Controllers\Api\Mobile\InvestmentController::class, 'store']);

    // Welfare
    Route::get('/welfare', [\App\Http\Controllers\Api\Mobile\WelfareController::class, 'index']);
    Route::get('/welfare/{welfare}', [\App\Http\Controllers\Api\Mobile\WelfareController::class, 'show']);
    Route::post('/welfare', [\App\Http\Controllers\Api\Mobile\WelfareController::class, 'store']);

    // Issues
    Route::get('/issues', [\App\Http\Controllers\Api\Mobile\IssueController::class, 'index']);
    Route::get('/issues/{issue}', [\App\Http\Controllers\Api\Mobile\IssueController::class, 'show']);
    Route::post('/issues', [\App\Http\Controllers\Api\Mobile\IssueController::class, 'store']);

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\Api\Mobile\TransactionController::class, 'index']);

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'show']);
    Route::put('/profile', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'update']);
    Route::put('/profile/password', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'updatePassword']);
    Route::get('/profile/documents', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'documents']);

    // File Uploads
    Route::post('/upload/kyc', [FileUploadController::class, 'uploadKyc']);
    Route::post('/upload/loan-document', [FileUploadController::class, 'uploadLoanDocument']);
    Route::post('/upload/welfare-document', [FileUploadController::class, 'uploadWelfareDocument']);
    Route::post('/upload/issue-attachment', [FileUploadController::class, 'uploadIssueAttachment']);

    // Notifications
    Route::post('/notifications/register-device', [NotificationController::class, 'registerDevice']);
    Route::delete('/notifications/unregister-device/{deviceToken}', [NotificationController::class, 'unregisterDevice']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/preferences', [NotificationController::class, 'updatePreferences']);
});
