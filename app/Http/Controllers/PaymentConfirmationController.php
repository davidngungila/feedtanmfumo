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

        $memberId = trim($request->input('member_id'));

        // First, check if there's a PaymentConfirmation record for this member_id
        // This handles cases where members are imported from Excel but not yet registered
        $paymentConfirmation = PaymentConfirmation::where('member_id', $memberId)
            ->latest()
            ->first();

        if ($paymentConfirmation) {
            // Found a payment confirmation record - use its data
            $user = $paymentConfirmation->user;

            // Get deposit balance from user if exists, otherwise use the stored balance
            $depositBalance = 0;
            if ($user) {
                $depositBalance = SavingsAccount::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->sum('balance') ?? 0;
            } else {
                // Use the stored deposit balance from payment confirmation
                $depositBalance = $paymentConfirmation->deposit_balance ?? 0;
            }

            return response()->json([
                'success' => true,
                'member' => [
                    'id' => $user?->id,
                    'member_id' => $paymentConfirmation->member_id,
                    'name' => $paymentConfirmation->member_name,
                    'member_type' => $paymentConfirmation->member_type ?? 'N/A',
                    'email' => $paymentConfirmation->member_email ?? ($user?->email ?? ''),
                    'deposit_balance' => number_format($depositBalance, 2),
                    'deposit_balance_raw' => $depositBalance,
                    'amount_to_pay' => $paymentConfirmation->amount_to_pay,
                    'swf_contribution' => $paymentConfirmation->swf_contribution,
                    're_deposit' => $paymentConfirmation->re_deposit,
                    'fia_investment' => $paymentConfirmation->fia_investment,
                    'fia_type' => $paymentConfirmation->fia_type,
                    'capital_contribution' => $paymentConfirmation->capital_contribution,
                    'loan_repayment' => $paymentConfirmation->loan_repayment,
                    'fine_penalty' => $paymentConfirmation->fine_penalty,
                    'has_existing_confirmation' => true,
                ],
            ]);
        }

        // If no payment confirmation found, try to find user by member_number or membership_code
        $user = User::where('member_number', $memberId)
            ->orWhere('membership_code', $memberId)
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found. Please check your member ID or contact the administrator.',
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
                'has_existing_confirmation' => false,
            ],
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        // Pre-process nullable fields: convert empty strings to null
        if ($request->has('user_id') && $request->input('user_id') === '') {
            $request->merge(['user_id' => null]);
        }
        if ($request->has('fia_type') && $request->input('fia_type') === '') {
            $request->merge(['fia_type' => null]);
        }
        if ($request->has('mobile_provider') && $request->input('mobile_provider') === '') {
            $request->merge(['mobile_provider' => null]);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'member_id' => 'required|string',
            'amount_to_pay' => 'required|numeric|min:0.01',
            'member_email' => 'required|email',
            'swf_contribution' => 'nullable|numeric|min:0',
            're_deposit' => 'nullable|numeric|min:0',
            'fia_investment' => 'nullable|numeric|min:0',
            'fia_type' => 'nullable|in:4_year,6_year',
            'capital_contribution' => 'nullable|numeric|min:0',
            'loan_repayment' => 'nullable|numeric|min:0',
            'fine_penalty' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'nullable|in:bank,mobile',
            'mobile_provider' => 'required_if:payment_method,mobile|nullable|in:mpesa,halotel',
            'mobile_number' => 'required_if:payment_method,mobile|nullable|string|max:20',
            'bank_account_number' => 'required_if:payment_method,bank|nullable|string|max:50',
            'bank_account_confirmation' => 'required_if:payment_method,bank|nullable|string|max:50|same:bank_account_number',
        ], [
            'payment_method.required' => 'Please select a payment method.',
            'mobile_provider.required_if' => 'Please select a mobile money provider.',
            'mobile_number.required_if' => 'Please enter your mobile number.',
            'bank_account_number.required_if' => 'Please enter your bank account number.',
            'bank_account_confirmation.required_if' => 'Please confirm your bank account number.',
            'bank_account_confirmation.same' => 'Bank account numbers do not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->input('user_id');
        $user = $userId ? User::find($userId) : null;

        // If no user, try to find by member_id from PaymentConfirmation
        if (! $user) {
            $paymentConfirmation = PaymentConfirmation::where('member_id', $request->input('member_id'))
                ->latest()
                ->first();

            if ($paymentConfirmation && $paymentConfirmation->user_id) {
                $user = User::find($paymentConfirmation->user_id);
            }
        }

        // Calculate total distribution
        $swfContribution = (float) ($request->input('swf_contribution') ?? 0);
        $reDeposit = (float) ($request->input('re_deposit') ?? 0);
        $fiaInvestment = (float) ($request->input('fia_investment') ?? 0);
        $capitalContribution = (float) ($request->input('capital_contribution') ?? 0);
        $loanRepayment = (float) ($request->input('loan_repayment') ?? 0);
        $finePenalty = (float) ($request->input('fine_penalty') ?? 0);

        $totalDistribution = $swfContribution + $reDeposit + $fiaInvestment + $capitalContribution + $loanRepayment + $finePenalty;
        $amountToPay = (float) $request->input('amount_to_pay');

        // Validate that total distribution does not exceed amount to pay
        if ($totalDistribution > $amountToPay) {
            return response()->json([
                'success' => false,
                'message' => 'Mgawanyo ('.number_format($totalDistribution, 2).') hauwezi kuzidi kiwango cha gawio ('.number_format($amountToPay, 2).')',
            ], 422);
        }

        // Validate payment method if there is cash remaining
        if ($amountToPay > $totalDistribution + 0.01 && empty($request->input('payment_method'))) {
            return response()->json([
                'success' => false,
                'message' => 'Tafadhali chagua njia ya malipo kwa ajili ya kupokea kiasi kilichobaki.',
            ], 422);
        }

        // Get deposit balance
        $depositBalance = 0;
        if ($user) {
            $depositBalance = SavingsAccount::where('user_id', $user->id)
                ->where('status', 'active')
                ->sum('balance') ?? 0;
        } else {
            // If no user, check if there's an existing payment confirmation with balance
            $existingConfirmation = PaymentConfirmation::where('member_id', $request->input('member_id'))
                ->latest()
                ->first();
            if ($existingConfirmation) {
                $depositBalance = $existingConfirmation->deposit_balance ?? 0;
            }
        }

        // Validate that deposit balance is sufficient
        if ($depositBalance < $amountToPay) {
            return response()->json([
                'success' => false,
                'message' => 'Salio la akiba halitoshi. Salio lako ni '.number_format($depositBalance, 2).' lakini unahitaji '.number_format($amountToPay, 2),
            ], 422);
        }

        // Get member name and type
        $memberName = $user?->name ?? '';
        $memberType = $user?->membershipType?->name ?? null;

        // If no user, try to get from existing payment confirmation
        if (empty($memberName)) {
            $existingConfirmation = PaymentConfirmation::where('member_id', $request->input('member_id'))
                ->latest()
                ->first();
            if ($existingConfirmation) {
                $memberName = $existingConfirmation->member_name ?? '';
                $memberType = $existingConfirmation->member_type ?? null;
            }
        }

        if (empty($memberName)) {
            $memberName = 'Member '.$request->input('member_id');
        }

        // Create payment confirmation
        $paymentConfirmation = PaymentConfirmation::create([
            'user_id' => $user?->id,
            'member_id' => $request->input('member_id'),
            'member_name' => $memberName,
            'member_type' => $memberType,
            'amount_to_pay' => $amountToPay,
            'deposit_balance' => $depositBalance,
            'swf_contribution' => $swfContribution,
            're_deposit' => $reDeposit,
            'fia_investment' => $fiaInvestment,
            'fia_type' => $request->input('fia_type'),
            'capital_contribution' => $capitalContribution,
            'loan_repayment' => $loanRepayment,
            'fine_penalty' => $finePenalty,
            'member_email' => $request->input('member_email'),
            'notes' => $request->input('notes'),
            'payment_method' => $request->input('payment_method'),
            'mobile_provider' => $request->input('mobile_provider'),
            'mobile_number' => $request->input('mobile_number'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_account_confirmation' => $request->input('bank_account_confirmation'),
        ]);

        // Send email notification
        try {
            $this->emailService->sendPaymentConfirmationEmail($paymentConfirmation);
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation email: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Uthibitisho wa malipo umewasilishwa kikamilifu!',
            'payment_confirmation_id' => $paymentConfirmation->id,
        ]);
    }
}
