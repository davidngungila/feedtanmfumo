<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfirmation;
use App\Models\SavingsAccount;
use App\Models\User;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentConfirmationController extends Controller
{
    public function __construct(
        protected EmailNotificationService $emailService
    ) {}

    public function index(): \Illuminate\View\View
    {
        return view('payment-confirmation.index');
    }

    public function lookupMember(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid member ID',
            ], 422);
        }

        $memberId = $request->input('member_id');

        // Try to find member by member_number or membership_code
        $user = User::where('member_number', $memberId)
            ->orWhere('membership_code', $memberId)
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found. Please check your member ID.',
            ], 404);
        }

        // Calculate total deposit balance from all active savings accounts
        $depositBalance = SavingsAccount::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('balance') ?? 0;

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $user->id,
                'member_id' => $user->member_number ?? $user->membership_code,
                'name' => $user->name,
                'member_type' => $user->membershipType?->name ?? 'N/A',
                'email' => $user->email,
                'deposit_balance' => number_format($depositBalance, 2),
                'deposit_balance_raw' => $depositBalance,
            ],
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'member_id' => 'required|string',
            'amount_to_pay' => 'required|numeric|min:0.01',
            'member_email' => 'required|email',
            'swf_contribution' => 'nullable|numeric|min:0',
            're_deposit' => 'nullable|numeric|min:0',
            'fia_investment' => 'nullable|numeric|min:0',
            'fia_type' => 'nullable|in:4_year,6_year',
            'capital_contribution' => 'nullable|numeric|min:0',
            'loan_repayment' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail($request->input('user_id'));

        // Calculate total distribution
        $swfContribution = (float) ($request->input('swf_contribution') ?? 0);
        $reDeposit = (float) ($request->input('re_deposit') ?? 0);
        $fiaInvestment = (float) ($request->input('fia_investment') ?? 0);
        $capitalContribution = (float) ($request->input('capital_contribution') ?? 0);
        $loanRepayment = (float) ($request->input('loan_repayment') ?? 0);

        $totalDistribution = $swfContribution + $reDeposit + $fiaInvestment + $capitalContribution + $loanRepayment;
        $amountToPay = (float) $request->input('amount_to_pay');

        // Validate that total distribution equals amount to pay
        if (abs($totalDistribution - $amountToPay) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Total distribution ('.number_format($totalDistribution, 2).') must equal the amount to be paid ('.number_format($amountToPay, 2).')',
            ], 422);
        }

        // Get deposit balance
        $depositBalance = SavingsAccount::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('balance') ?? 0;

        // Validate that deposit balance is sufficient
        if ($depositBalance < $amountToPay) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient deposit balance. Your balance is '.number_format($depositBalance, 2).' but you need '.number_format($amountToPay, 2),
            ], 422);
        }

        // Create payment confirmation
        $paymentConfirmation = PaymentConfirmation::create([
            'user_id' => $user->id,
            'member_id' => $request->input('member_id'),
            'member_name' => $user->name,
            'member_type' => $user->membershipType?->name,
            'amount_to_pay' => $amountToPay,
            'deposit_balance' => $depositBalance,
            'swf_contribution' => $swfContribution,
            're_deposit' => $reDeposit,
            'fia_investment' => $fiaInvestment,
            'fia_type' => $request->input('fia_type'),
            'capital_contribution' => $capitalContribution,
            'loan_repayment' => $loanRepayment,
            'member_email' => $request->input('member_email'),
            'notes' => $request->input('notes'),
        ]);

        // Send email notification
        try {
            $this->emailService->sendPaymentConfirmationEmail($paymentConfirmation);
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation email: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmation submitted successfully!',
            'payment_confirmation_id' => $paymentConfirmation->id,
        ]);
    }
}
