<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display all transactions.
     */
    public function transactions(Request $request)
    {
        $query = DB::table('payment_transactions')
            ->join('users', 'payment_transactions.user_id', '=', 'users.id')
            ->leftJoin('loans', 'payment_transactions.loan_id', '=', 'loans.id')
            ->select(
                'payment_transactions.*',
                'users.name as user_name',
                'users.member_number',
                'loans.loan_number'
            );

        // Filters
        if ($request->filled('status')) {
            $query->where('payment_transactions.status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('payment_transactions.payment_method', $request->method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('payment_transactions.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_transactions.created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_transactions.transaction_reference', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%")
                  ->orWhere('users.member_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('payment_transactions.created_at', 'desc')
            ->paginate(50);

        $statistics = [
            'total_transactions' => DB::table('payment_transactions')->count(),
            'total_amount' => DB::table('payment_transactions')->sum('amount'),
            'successful_transactions' => DB::table('payment_transactions')->where('status', 'completed')->count(),
            'failed_transactions' => DB::table('payment_transactions')->where('status', 'failed')->count(),
            'pending_transactions' => DB::table('payment_transactions')->where('status', 'pending')->count(),
        ];

        return view('admin.payments.transactions', compact('transactions', 'statistics'));
    }

    /**
     * Display BillPay interface.
     */
    public function billpay()
    {
        $billProviders = DB::table('bill_providers')->get();
        $recentBills = DB::table('bill_payments')
            ->join('users', 'bill_payments.user_id', '=', 'users.id')
            ->join('bill_providers', 'bill_payments.provider_id', '=', 'bill_providers.id')
            ->select('bill_payments.*', 'users.name as user_name', 'bill_providers.name as provider_name')
            ->orderBy('bill_payments.created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.payments.billpay', compact('billProviders', 'recentBills'));
    }

    /**
     * Display checkout payments interface.
     */
    public function checkout()
    {
        $activeCheckouts = DB::table('checkout_sessions')
            ->join('users', 'checkout_sessions.user_id', '=', 'users.id')
            ->select('checkout_sessions.*', 'users.name as user_name')
            ->where('checkout_sessions.status', 'active')
            ->orderBy('checkout_sessions.created_at', 'desc')
            ->get();

        $completedCheckouts = DB::table('checkout_sessions')
            ->join('users', 'checkout_sessions.user_id', '=', 'users.id')
            ->select('checkout_sessions.*', 'users.name as user_name')
            ->where('checkout_sessions.status', 'completed')
            ->orderBy('checkout_sessions.completed_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.payments.checkout', compact('activeCheckouts', 'completedCheckouts'));
    }

    /**
     * Display USSD Push interface.
     */
    public function ussdPush()
    {
        return view('admin.payments.ussd-push');
    }

    /**
     * Preview USSD Push request.
     */
    public function previewUssdPush(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|in:TZS',
            'orderReference' => 'required|string|max:50',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'fetchSenderDetails' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'phoneNumber' => $request->phoneNumber,
                'fetchSenderDetails' => $request->boolean('fetchSenderDetails', false),
            ];

            // Add checksum if enabled
            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payments/preview-ussd-push-request', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('USSD Push preview error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Initiate USSD Push request.
     */
    public function initiateUssdPush(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|in:TZS',
            'orderReference' => 'required|string|max:50',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'selectedMethod' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'phoneNumber' => $request->phoneNumber,
                'paymentMethod' => $request->selectedMethod,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payments/initiate-ussd-push-request', $payload);

            if ($response->successful()) {
                // Log the transaction
                $transactionId = $response->json('transactionId');
                $this->logTransaction($request, $response->json(), 'ussd_push');

                return response()->json([
                    'status' => 'success',
                    'message' => 'USSD Push initiated successfully',
                    'data' => $response->json(),
                    'transaction_id' => $transactionId
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('USSD Push initiation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display card payments interface.
     */
    public function cardPayments()
    {
        $cardTransactions = DB::table('card_transactions')
            ->join('users', 'card_transactions.user_id', '=', 'users.id')
            ->select('card_transactions.*', 'users.name as user_name')
            ->orderBy('card_transactions.created_at', 'desc')
            ->paginate(50);

        return view('admin.payments.card-payments', compact('cardTransactions'));
    }

    /**
     * Process card payment.
     */
    public function processCardPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|in:TZS',
            'orderReference' => 'required|string|max:50',
            'cardNumber' => 'required|string|regex:/^[0-9]{16}$/',
            'expiryMonth' => 'required|string|regex:/^[0-9]{2}$/',
            'expiryYear' => 'required|string|regex:/^[0-9]{2}$/',
            'cvv' => 'required|string|regex:/^[0-9]{3,4}$/',
            'cardholderName' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'card' => [
                    'number' => $request->cardNumber,
                    'expiryMonth' => $request->expiryMonth,
                    'expiryYear' => $request->expiryYear,
                    'cvv' => $request->cvv,
                    'holderName' => $request->cardholderName,
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payments/process-card-payment', $payload);

            if ($response->successful()) {
                $this->logCardTransaction($request, $response->json(), 'card_payment');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Card payment processed successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Card payment processing error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display payouts interface.
     */
    public function payouts()
    {
        $payouts = DB::table('payouts')
            ->join('users', 'payouts.user_id', '=', 'users.id')
            ->select('payouts.*', 'users.name as user_name')
            ->orderBy('payouts.created_at', 'desc')
            ->paginate(50);

        return view('admin.payments.payouts', compact('payouts'));
    }

    /**
     * Process payout.
     */
    public function processPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|in:TZS',
            'orderReference' => 'required|string|max:50',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'provider' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'phoneNumber' => $request->phoneNumber,
                'provider' => $request->provider,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payments/process-payout', $payload);

            if ($response->successful()) {
                $this->logPayout($request, $response->json());

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payout processed successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Payout processing error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display reconciliation interface.
     */
    public function reconciliation()
    {
        $pendingReconciliation = DB::table('payment_transactions')
            ->where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();

        $reconciledToday = DB::table('payment_transactions')
            ->where('status', 'completed')
            ->whereDate('reconciled_at', today())
            ->count();

        $failedReconciliation = DB::table('payment_transactions')
            ->where('status', 'failed')
            ->whereDate('created_at', today())
            ->count();

        return view('admin.payments.reconciliation', compact(
            'pendingReconciliation',
            'reconciledToday',
            'failedReconciliation'
        ));
    }

    /**
     * Reconcile transactions.
     */
    public function reconcileTransactions(Request $request)
    {
        try {
            $transactionIds = $request->input('transaction_ids', []);

            foreach ($transactionIds as $transactionId) {
                $transaction = DB::table('payment_transactions')
                    ->where('id', $transactionId)
                    ->first();

                if ($transaction && $transaction->status === 'pending') {
                    // Check with ClickPesa API for transaction status
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                        'Content-Type' => 'application/json',
                    ])->get("https://api.clickpesa.com/third-parties/payments/transaction/{$transaction->transaction_reference}");

                    if ($response->successful()) {
                        $status = $response->json('status');
                        
                        DB::table('payment_transactions')
                            ->where('id', $transactionId)
                            ->update([
                                'status' => $status,
                                'reconciled_at' => now(),
                                'updated_at' => now(),
                            ]);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Transactions reconciled successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Transaction reconciliation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get transaction details.
     */
    public function transactionDetails($id)
    {
        $transaction = DB::table('payment_transactions')
            ->join('users', 'payment_transactions.user_id', '=', 'users.id')
            ->leftJoin('loans', 'payment_transactions.loan_id', '=', 'loans.id')
            ->select(
                'payment_transactions.*',
                'users.name as user_name',
                'users.member_number',
                'users.email',
                'users.phone',
                'loans.loan_number'
            )
            ->where('payment_transactions.id', $id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

    /**
     * Query payment status by order reference.
     */
    public function queryPaymentStatus($orderReference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get("https://api.clickpesa.com/third-parties/payments/{$orderReference}");

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Query payment status error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Query all payments with filtering.
     */
    public function queryAllPayments(Request $request)
    {
        try {
            $params = [
                'orderBy' => $request->get('orderBy', 'DESC'),
                'limit' => $request->get('limit', 20),
                'skip' => $request->get('skip', 0),
            ];

            // Add optional filters
            if ($request->filled('startDate')) $params['startDate'] = $request->startDate;
            if ($request->filled('endDate')) $params['endDate'] = $request->endDate;
            if ($request->filled('status')) $params['status'] = $request->status;
            if ($request->filled('collectedCurrency')) $params['collectedCurrency'] = $request->collectedCurrency;
            if ($request->filled('channel')) $params['channel'] = $request->channel;
            if ($request->filled('orderReference')) $params['orderReference'] = $request->orderReference;
            if ($request->filled('clientId')) $params['clientId'] = $request->clientId;
            if ($request->filled('sortBy')) $params['sortBy'] = $request->sortBy;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/payments/all', $params);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Query all payments error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Preview mobile money payout.
     */
    public function previewMobileMoneyPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'phoneNumber' => $request->phoneNumber,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payouts/preview-mobile-money-payout', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Preview mobile money payout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Create mobile money payout.
     */
    public function createMobileMoneyPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'phoneNumber' => $request->phoneNumber,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payouts/create-mobile-money-payout', $payload);

            if ($response->successful()) {
                $this->logPayout($request, $response->json());
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mobile money payout created successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Create mobile money payout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Preview bank payout.
     */
    public function previewBankPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'accountNumber' => 'required|string|max:50',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:50',
            'bic' => 'required|string|max:11',
            'transferType' => 'required|in:ACH,RTGS',
            'accountCurrency' => 'required|in:TZS',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'accountNumber' => $request->accountNumber,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'bic' => $request->bic,
                'transferType' => $request->transferType,
                'accountCurrency' => $request->accountCurrency,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payouts/preview-bank-payout', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Preview bank payout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Create bank payout.
     */
    public function createBankPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'accountNumber' => 'required|string|max:50',
            'accountName' => 'required|string|max:100',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:50',
            'bic' => 'required|string|max:11',
            'transferType' => 'required|in:ACH,RTGS',
            'accountCurrency' => 'required|in:TZS',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'accountNumber' => $request->accountNumber,
                'accountName' => $request->accountName,
                'currency' => $request->currency,
                'orderReference' => $request->orderReference,
                'bic' => $request->bic,
                'transferType' => $request->transferType,
                'accountCurrency' => $request->accountCurrency,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payouts/create-bank-payout', $payload);

            if ($response->successful()) {
                $this->logPayout($request, $response->json());
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bank payout created successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Create bank payout error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Query payout status by order reference.
     */
    public function queryPayoutStatus($orderReference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get("https://api.clickpesa.com/third-parties/payouts/{$orderReference}");

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Query payout status error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Query all payouts with filtering.
     */
    public function queryAllPayouts(Request $request)
    {
        try {
            $params = [
                'orderBy' => $request->get('orderBy', 'DESC'),
                'limit' => $request->get('limit', 20),
                'skip' => $request->get('skip', 0),
            ];

            // Add optional filters
            if ($request->filled('startDate')) $params['startDate'] = $request->startDate;
            if ($request->filled('endDate')) $params['endDate'] = $request->endDate;
            if ($request->filled('channel')) $params['channel'] = $request->channel;
            if ($request->filled('currency')) $params['currency'] = $request->currency;
            if ($request->filled('orderReference')) $params['orderReference'] = $request->orderReference;
            if ($request->filled('status')) $params['status'] = $request->status;
            if ($request->filled('transferType')) $params['transferType'] = $request->transferType;
            if ($request->filled('clientId')) $params['clientId'] = $request->clientId;
            if ($request->filled('sortBy')) $params['sortBy'] = $request->sortBy;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/payouts/all', $params);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Query all payouts error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Retrieve banks list.
     */
    public function retrieveBanksList()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/list/banks');

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Retrieve banks list error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Create order control number.
     */
    public function createOrderControlNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billDescription' => 'required|string|max:500',
            'billPaymentMode' => 'required|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'billAmount' => 'required|numeric|min:0.01',
            'billReference' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'billDescription' => $request->billDescription,
                'billPaymentMode' => $request->billPaymentMode,
                'billAmount' => $request->billAmount,
            ];

            if ($request->filled('billReference')) {
                $payload['billReference'] = $request->billReference;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/billpay/create-order-control-number', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Order control number created successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Create order control number error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Create customer control number.
     */
    public function createCustomerControlNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customerName' => 'required|string|max:100',
            'customerEmail' => 'nullable|email|max:100',
            'customerPhone' => 'nullable|string|regex:/^[0-9]{12}$/',
            'billDescription' => 'required|string|max:500',
            'billPaymentMode' => 'required|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'billAmount' => 'required|numeric|min:0.01',
            'billReference' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'customerName' => $request->customerName,
                'billDescription' => $request->billDescription,
                'billPaymentMode' => $request->billPaymentMode,
                'billAmount' => $request->billAmount,
            ];

            if ($request->filled('customerEmail')) {
                $payload['customerEmail'] = $request->customerEmail;
            }
            if ($request->filled('customerPhone')) {
                $payload['customerPhone'] = $request->customerPhone;
            }
            if ($request->filled('billReference')) {
                $payload['billReference'] = $request->billReference;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/billpay/create-customer-control-number', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Customer control number created successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Create customer control number error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Bulk create order control numbers.
     */
    public function bulkCreateOrderControlNumbers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'controlNumbers' => 'required|array|min:1|max:50',
            'controlNumbers.*.billAmount' => 'required|numeric|min:0.01',
            'controlNumbers.*.billDescription' => 'required|string|max:500',
            'controlNumbers.*.billPaymentMode' => 'required|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'controlNumbers.*.billReference' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/billpay/bulk-create-order-control-numbers', $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bulk order control numbers created',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Bulk create order control numbers error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Bulk create customer control numbers.
     */
    public function bulkCreateCustomerControlNumbers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'controlNumbers' => 'required|array|min:1|max:50',
            'controlNumbers.*.customerName' => 'required|string|max:100',
            'controlNumbers.*.customerEmail' => 'nullable|email|max:100',
            'controlNumbers.*.customerPhone' => 'nullable|string|regex:/^[0-9]{12}$/',
            'controlNumbers.*.billAmount' => 'required|numeric|min:0.01',
            'controlNumbers.*.billDescription' => 'required|string|max:500',
            'controlNumbers.*.billPaymentMode' => 'required|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'controlNumbers.*.billReference' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/billpay/bulk-create-customer-control-numbers', $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bulk customer control numbers created',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Bulk create customer control numbers error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update BillPay reference.
     */
    public function updateBillPayReference(Request $request, $billPayNumber)
    {
        $validator = Validator::make($request->all(), [
            'billStatus' => 'nullable|in:ACTIVE,INACTIVE',
            'billAmount' => 'nullable|numeric|min:0.01',
            'billDescription' => 'nullable|string|max:500',
            'billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [];
            if ($request->filled('billStatus')) $payload['billStatus'] = $request->billStatus;
            if ($request->filled('billAmount')) $payload['billAmount'] = $request->billAmount;
            if ($request->filled('billDescription')) $payload['billDescription'] = $request->billDescription;
            if ($request->filled('billPaymentMode')) $payload['billPaymentMode'] = $request->billPaymentMode;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->patch("https://api.clickpesa.com/third-parties/billpay/{$billPayNumber}", $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'BillPay reference updated successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Update BillPay reference error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Query BillPay number details.
     */
    public function queryBillPayNumberDetails($billPayNumber)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get("https://api.clickpesa.com/third-parties/billpay/{$billPayNumber}");

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Query BillPay number details error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update BillPay number status.
     */
    public function updateBillPayNumberStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billPayNumber' => 'required|string|max:50',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->put('https://api.clickpesa.com/third-parties/billpay/update-status', $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'BillPay number status updated successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Update BillPay number status error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Generate checkout link.
     */
    public function generateCheckoutLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'totalPrice' => 'required|string',
            'orderReference' => 'required|string|max:50',
            'orderCurrency' => 'required|in:TZS,USD',
            'customerName' => 'nullable|string|max:100',
            'customerEmail' => 'nullable|email|max:100',
            'customerPhone' => 'nullable|string|regex:/^[0-9]{12}$/',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'totalPrice' => $request->totalPrice,
                'orderReference' => $request->orderReference,
                'orderCurrency' => $request->orderCurrency,
            ];

            if ($request->filled('customerName')) $payload['customerName'] = $request->customerName;
            if ($request->filled('customerEmail')) $payload['customerEmail'] = $request->customerEmail;
            if ($request->filled('customerPhone')) $payload['customerPhone'] = $request->customerPhone;
            if ($request->filled('description')) $payload['description'] = $request->description;

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/checkout-link/generate-checkout-url', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkout link generated successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Generate checkout link error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Generate payout link.
     */
    public function generatePayoutLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|string',
            'orderReference' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payload = [
                'amount' => $request->amount,
                'orderReference' => $request->orderReference,
            ];

            if (config('clickpesa.checksum_enabled', false)) {
                $payload['checksum'] = $this->generateChecksum($payload);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->post('https://api.clickpesa.com/third-parties/payout-link/generate-payout-url', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payout link generated successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Generate payout link error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Retrieve account balance.
     */
    public function retrieveAccountBalance()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/account/balance');

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Retrieve account balance error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Retrieve account statement.
     */
    public function retrieveAccountStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $params = ['currency' => $request->currency];
            if ($request->filled('startDate')) $params['startDate'] = $request->startDate;
            if ($request->filled('endDate')) $params['endDate'] = $request->endDate;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/account/statement', $params);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Retrieve account statement error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Retrieve exchange rates.
     */
    public function retrieveExchangeRates(Request $request)
    {
        try {
            $params = [];
            if ($request->filled('source')) $params['source'] = $request->source;
            if ($request->filled('target')) $params['target'] = $request->target;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getClickPesaToken(),
                'Content-Type' => 'application/json',
            ])->get('https://api.clickpesa.com/third-parties/exchange-rates/all', $params);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ClickPesa API error: ' . $response->status(),
                    'data' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Retrieve exchange rates error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get recent USSD pushes for sidebar.
     */
    public function getRecentUssdPushes()
    {
        try {
            $recentPushes = DB::table('payment_transactions')
                ->where('payment_method', 'ussd_push')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $recentPushes
            ]);
        } catch (\Exception $e) {
            Log::error('Get recent USSD pushes error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Export transactions.
     */
    public function exportTransactions(Request $request)
    {
        // Implementation for exporting transactions to CSV/Excel
        // This would typically use Laravel Excel or similar library
        return response()->json([
            'status' => 'success',
            'message' => 'Export functionality to be implemented'
        ]);
    }

    /**
     * Get ClickPesa token.
     */
    private function getClickPesaToken()
    {
        // Implementation to get ClickPesa token
        // This would typically cache the token and refresh when needed
        return config('clickpesa.api_token', 'your-token-here');
    }

    /**
     * Generate checksum for payload.
     */
    private function generateChecksum($payload)
    {
        // Implementation to generate checksum
        // This would use your secret key to generate HMAC
        return hash_hmac('sha256', json_encode($payload), config('clickpesa.secret_key'));
    }

    /**
     * Log transaction.
     */
    private function logTransaction($request, $response, $type)
    {
        DB::table('payment_transactions')->insert([
            'user_id' => auth()->id(),
            'transaction_reference' => $response['transactionId'] ?? null,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method' => $type,
            'phone_number' => $request->phoneNumber,
            'status' => 'pending',
            'order_reference' => $request->orderReference,
            'response_data' => json_encode($response),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Log card transaction.
     */
    private function logCardTransaction($request, $response, $type)
    {
        DB::table('card_transactions')->insert([
            'user_id' => auth()->id(),
            'transaction_reference' => $response['transactionId'] ?? null,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method' => $type,
            'cardholder_name' => $request->cardholderName,
            'last_four_digits' => substr($request->cardNumber, -4),
            'status' => 'pending',
            'order_reference' => $request->orderReference,
            'response_data' => json_encode($response),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Log payout.
     */
    private function logPayout($request, $response)
    {
        DB::table('payouts')->insert([
            'user_id' => auth()->id(),
            'transaction_reference' => $response['transactionId'] ?? null,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'phone_number' => $request->phoneNumber,
            'provider' => $request->provider,
            'status' => 'pending',
            'order_reference' => $request->orderReference,
            'response_data' => json_encode($response),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get recent control numbers.
     */
    public function getRecentControlNumbers()
    {
        try {
            // In a real implementation, this would query your database
            // For now, return sample data
            $recentNumbers = [
                [
                    'billPayNumber' => '55042914871931',
                    'billDescription' => 'Water Bill - July 2024',
                    'billAmount' => 90900,
                    'billPaymentMode' => 'EXACT',
                    'createdAt' => now()->subMinutes(45)
                ],
                [
                    'billPayNumber' => '55042914871932',
                    'billDescription' => 'Electricity Bill',
                    'billAmount' => 50000,
                    'billPaymentMode' => 'ALLOW_PARTIAL_AND_OVER_PAYMENT',
                    'createdAt' => now()->subHours(3)
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $recentNumbers
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting recent control numbers: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent control numbers'
            ], 500);
        }
    }

    /**
     * Get all control numbers.
     */
    public function getAllControlNumbers()
    {
        try {
            // In a real implementation, this would query your database with pagination
            // For now, return sample data
            $allNumbers = [
                [
                    'billPayNumber' => '55042914871931',
                    'billDescription' => 'Water Bill - July 2024',
                    'billAmount' => 90900,
                    'billPaymentMode' => 'EXACT',
                    'billCustomerName' => 'John Doe',
                    'createdAt' => now()->subMinutes(45)
                ],
                [
                    'billPayNumber' => '55042914871932',
                    'billDescription' => 'Electricity Bill',
                    'billAmount' => 50000,
                    'billPaymentMode' => 'ALLOW_PARTIAL_AND_OVER_PAYMENT',
                    'billCustomerName' => 'Jane Smith',
                    'createdAt' => now()->subHours(3)
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $allNumbers
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all control numbers: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve control numbers'
            ], 500);
        }
    }

    /**
     * Get recent checkout sessions.
     */
    public function getRecentCheckoutSessions()
    {
        try {
            // In a real implementation, this would query your database
            // For now, return sample data
            $recentSessions = [
                [
                    'session_id' => 'CHK_' . uniqid(),
                    'orderReference' => 'CHK-' . time(),
                    'totalPrice' => 10000,
                    'orderCurrency' => 'TZS',
                    'customer_name' => 'John Doe',
                    'status' => 'completed',
                    'created_at' => now()->subMinutes(15)
                ],
                [
                    'session_id' => 'CHK_' . uniqid(),
                    'orderReference' => 'CHK-' . (time() - 3600),
                    'totalPrice' => 25000,
                    'orderCurrency' => 'TZS',
                    'customer_name' => 'Jane Smith',
                    'status' => 'pending',
                    'created_at' => now()->subHours(1)
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $recentSessions
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting recent checkout sessions: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent checkout sessions'
            ], 500);
        }
    }

    /**
     * Get all checkout sessions.
     */
    public function getAllCheckoutSessions()
    {
        try {
            // In a real implementation, this would query your database with pagination
            // For now, return sample data
            $allSessions = [
                [
                    'session_id' => 'CHK_' . uniqid(),
                    'orderReference' => 'CHK-' . time(),
                    'totalPrice' => 10000,
                    'orderCurrency' => 'TZS',
                    'customer_name' => 'John Doe',
                    'customer_email' => 'john@example.com',
                    'customer_phone' => '255712345678',
                    'description' => 'Online purchase',
                    'status' => 'completed',
                    'created_at' => now()->subMinutes(15),
                    'completed_at' => now()->subMinutes(10),
                    'checkout_link' => 'https://checkout.clickpesa.com/checkout/123'
                ],
                [
                    'session_id' => 'CHK_' . uniqid(),
                    'orderReference' => 'CHK-' . (time() - 3600),
                    'totalPrice' => 25000,
                    'orderCurrency' => 'TZS',
                    'customer_name' => 'Jane Smith',
                    'customer_email' => 'jane@example.com',
                    'customer_phone' => '255765432109',
                    'description' => 'Service payment',
                    'status' => 'pending',
                    'created_at' => now()->subHours(1),
                    'completed_at' => null,
                    'checkout_link' => 'https://checkout.clickpesa.com/checkout/456'
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $allSessions
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all checkout sessions: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve checkout sessions'
            ], 500);
        }
    }

    /**
     * Get checkout session details.
     */
    public function getCheckoutSessionDetails($sessionId)
    {
        try {
            // In a real implementation, this would query your database
            // For now, return sample data
            $session = [
                'session_id' => $sessionId,
                'orderReference' => 'CHK-' . time(),
                'totalPrice' => 10000,
                'orderCurrency' => 'TZS',
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '255712345678',
                'description' => 'Online purchase',
                'status' => 'completed',
                'created_at' => now()->subMinutes(15),
                'completed_at' => now()->subMinutes(10),
                'checkout_link' => 'https://checkout.clickpesa.com/checkout/123'
            ];

            return response()->json([
                'status' => 'success',
                'data' => $session
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting checkout session details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve checkout session details'
            ], 500);
        }
    }

    /**
     * Get recent payouts.
     */
    public function getRecentPayouts()
    {
        try {
            // In a real implementation, this would query your database
            // For now, return sample data
            $recentPayouts = [
                [
                    'id' => 'PO_' . uniqid(),
                    'amount' => 10000,
                    'currency' => 'TZS',
                    'status' => 'SUCCESS',
                    'channel' => 'MOBILE_MONEY',
                    'channelProvider' => 'TIGO_PESA',
                    'beneficiary' => [
                        'accountName' => 'John Doe',
                        'accountNumber' => '255712345678'
                    ],
                    'createdAt' => now()->subMinutes(30)
                ],
                [
                    'id' => 'PO_' . uniqid(),
                    'amount' => 25000,
                    'currency' => 'TZS',
                    'status' => 'PENDING',
                    'channel' => 'BANK_TRANSFER',
                    'channelProvider' => 'CRDB',
                    'beneficiary' => [
                        'accountName' => 'Jane Smith',
                        'accountNumber' => '0151234567890'
                    ],
                    'createdAt' => now()->subHours(2)
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $recentPayouts
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting recent payouts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent payouts'
            ], 500);
        }
    }

    /**
     * Get all payouts.
     */
    public function getAllPayouts()
    {
        try {
            // In a real implementation, this would query your database with pagination
            // For now, return sample data
            $allPayouts = [
                [
                    'id' => 'PO_' . uniqid(),
                    'amount' => 10000,
                    'currency' => 'TZS',
                    'status' => 'SUCCESS',
                    'channel' => 'MOBILE_MONEY',
                    'channelProvider' => 'TIGO_PESA',
                    'beneficiary' => [
                        'accountName' => 'John Doe',
                        'accountNumber' => '255712345678'
                    ],
                    'createdAt' => now()->subMinutes(30),
                    'updatedAt' => now()->subMinutes(25)
                ],
                [
                    'id' => 'PO_' . uniqid(),
                    'amount' => 25000,
                    'currency' => 'TZS',
                    'status' => 'PENDING',
                    'channel' => 'BANK_TRANSFER',
                    'channelProvider' => 'CRDB',
                    'beneficiary' => [
                        'accountName' => 'Jane Smith',
                        'accountNumber' => '0151234567890'
                    ],
                    'createdAt' => now()->subHours(2),
                    'updatedAt' => now()->subHours(2)
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $allPayouts
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all payouts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve payouts'
            ], 500);
        }
    }

    /**
     * Download checkout receipt.
     */
    public function downloadCheckoutReceipt($sessionId)
    {
        try {
            // In a real implementation, this would generate and return a PDF receipt
            // For now, return a simple response
            return response()->json([
                'status' => 'success',
                'message' => 'Receipt download functionality to be implemented'
            ]);
        } catch (\Exception $e) {
            Log::error('Error downloading checkout receipt: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to download receipt'
            ], 500);
        }
    }
}
