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
                // Log transaction
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
     * Export transactions.
     */
    public function exportTransactions(Request $request)
    {
        try {
            $query = DB::table('payment_transactions')
                ->join('users', 'payment_transactions.user_id', '=', 'users.id')
                ->select(
                    'payment_transactions.*',
                    'users.name as user_name',
                    'users.member_number',
                    'users.email'
                );

            // Apply filters
            if ($request->filled('status')) {
                $query->where('payment_transactions.status', $request->status);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('payment_transactions.created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('payment_transactions.created_at', '<=', $request->date_to);
            }

            $transactions = $query->orderBy('payment_transactions.created_at', 'desc')->get();

            // Generate CSV
            $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://memory', 'w');

            // CSV header
            fputcsv($handle, [
                'Transaction ID', 'User Name', 'Member Number', 'Amount', 
                'Currency', 'Status', 'Payment Method', 'Reference', 'Created At'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->id,
                    $transaction->user_name,
                    $transaction->member_number,
                    $transaction->amount,
                    $transaction->currency,
                    $transaction->status,
                    $transaction->payment_method,
                    $transaction->transaction_reference,
                    $transaction->created_at
                ]);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename={$filename}");

        } catch (\Exception $e) {
            Log::error('Export transactions error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export transactions'
            ], 500);
        }
    }

    // ========================================
    // CLICKPESA API INTEGRATION METHODS
    // ========================================

    /**
     * Get authorization headers for ClickPesa API.
     */
    private function getClickPesaHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . config('clickpesa.api_token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Make HTTP request to ClickPesa API.
     */
    private function makeClickPesaRequest($method, $endpoint, $data = null)
    {
        $url = config('clickpesa.api_url') . $endpoint;
        
        try {
            $response = Http::withHeaders($this->getClickPesaHeaders())
                ->timeout(config('clickpesa.timeout', 30))
                ->{$method}($url, $data);

            Log::info("ClickPesa API Request: {$method} {$url}", [
                'data' => $data,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error("ClickPesa API Error: {$e->getMessage()}", [
                'method' => $method,
                'endpoint' => $endpoint,
                'data' => $data
            ]);
            throw $e;
        }
    }

    // ========================================
    // PAYMENT METHODS
    // ========================================

    /**
     * Query payment status by order reference.
     */
    public function queryPaymentStatus($orderReference)
    {
        try {
            $endpoint = str_replace('{orderReference}', $orderReference, config('clickpesa.endpoints.query_payment_status'));
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payment status',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error querying payment status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payment status'
            ], 500);
        }
    }

    /**
     * Query all payments with filtering.
     */
    public function queryAllPayments(Request $request)
    {
        try {
            $params = $request->only([
                'startDate', 'endDate', 'status', 'collectedCurrency', 
                'channel', 'orderReference', 'clientId', 'sortBy', 
                'orderBy', 'skip', 'limit'
            ]);

            $endpoint = config('clickpesa.endpoints.query_all_payments') . '?' . http_build_query($params);
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payments',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error querying all payments: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payments'
            ], 500);
        }
    }

    // ========================================
    // PAYOUT METHODS
    // ========================================

    /**
     * Preview mobile money payout.
     */
    public function previewMobileMoneyPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:100',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.preview_mobile_money_payout');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to preview mobile money payout',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error previewing mobile money payout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to preview payout'
            ], 500);
        }
    }

    /**
     * Create mobile money payout.
     */
    public function createMobileMoneyPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'phoneNumber' => 'required|string|regex:/^[0-9]{12}$/',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:100',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.create_mobile_money_payout');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create mobile money payout',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error creating mobile money payout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payout'
            ], 500);
        }
    }

    /**
     * Preview bank payout.
     */
    public function previewBankPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'accountNumber' => 'required|string|max:50',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:100',
            'bic' => 'required|string|max:11',
            'transferType' => 'required|in:ACH,RTGS',
            'accountCurrency' => 'nullable|in:TZS',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.preview_bank_payout');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to preview bank payout',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error previewing bank payout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to preview payout'
            ], 500);
        }
    }

    /**
     * Create bank payout.
     */
    public function createBankPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'accountNumber' => 'required|string|max:50',
            'accountName' => 'required|string|max:100',
            'currency' => 'required|in:TZS,USD',
            'orderReference' => 'required|string|max:100',
            'bic' => 'required|string|max:11',
            'transferType' => 'required|in:ACH,RTGS',
            'accountCurrency' => 'nullable|in:TZS',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.create_bank_payout');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create bank payout',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error creating bank payout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create payout'
            ], 500);
        }
    }

    /**
     * Query payout status by order reference.
     */
    public function queryPayoutStatus($orderReference)
    {
        try {
            $endpoint = str_replace('{orderReference}', $orderReference, config('clickpesa.endpoints.query_payout_status'));
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payout status',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error querying payout status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payout status'
            ], 500);
        }
    }

    /**
     * Query all payouts with filtering.
     */
    public function queryAllPayouts(Request $request)
    {
        try {
            $params = $request->only([
                'startDate', 'endDate', 'channel', 'currency', 'orderReference',
                'status', 'transferType', 'clientId', 'sortBy', 'orderBy', 'skip', 'limit'
            ]);

            $endpoint = config('clickpesa.endpoints.query_all_payouts') . '?' . http_build_query($params);
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payouts',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error querying all payouts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query payouts'
            ], 500);
        }
    }

    // ========================================
    // BILLPAY METHODS
    // ========================================

    /**
     * Create order control number.
     */
    public function createOrderControlNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billDescription' => 'required|string|max:500',
            'billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'billAmount' => 'nullable|numeric|min:0.01',
            'billReference' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.create_order_control_number');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order control number',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error creating order control number: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create control number'
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
            'billDescription' => 'nullable|string|max:500',
            'billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'billAmount' => 'nullable|numeric|min:0.01',
            'billReference' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.create_customer_control_number');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create customer control number',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error creating customer control number: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create control number'
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
            'controlNumbers.*.billDescription' => 'required|string|max:500',
            'controlNumbers.*.billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'controlNumbers.*.billAmount' => 'nullable|numeric|min:0.01',
            'controlNumbers.*.billReference' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.bulk_create_order_control_numbers');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk create order control numbers',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error bulk creating order control numbers: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk create control numbers'
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
            'controlNumbers.*.billDescription' => 'nullable|string|max:500',
            'controlNumbers.*.billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT',
            'controlNumbers.*.billAmount' => 'nullable|numeric|min:0.01',
            'controlNumbers.*.billReference' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.bulk_create_customer_control_numbers');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk create customer control numbers',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error bulk creating customer control numbers: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk create control numbers'
            ], 500);
        }
    }

    /**
     * Query BillPay number details.
     */
    public function queryBillPayDetails($billPayNumber)
    {
        try {
            $endpoint = str_replace('{billPayNumber}', $billPayNumber, config('clickpesa.endpoints.query_billpay_details'));
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query BillPay details',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error querying BillPay details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to query BillPay details'
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
            'billPaymentMode' => 'nullable|in:ALLOW_PARTIAL_AND_OVER_PAYMENT,EXACT'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = str_replace('{billPayNumber}', $billPayNumber, config('clickpesa.endpoints.update_billpay_reference'));
            $response = $this->makeClickPesaRequest('patch', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update BillPay reference',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error updating BillPay reference: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update BillPay reference'
            ], 500);
        }
    }

    // ========================================
    // CHECKOUT AND PAYOUT LINKS
    // ========================================

    /**
     * Generate checkout link.
     */
    public function generateCheckoutLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'totalPrice' => 'required|string',
            'orderReference' => 'required|string|max:100',
            'orderCurrency' => 'required|in:TZS,USD',
            'customerName' => 'nullable|string|max:100',
            'customerEmail' => 'nullable|email|max:100',
            'customerPhone' => 'nullable|string|regex:/^[0-9]{12}$/',
            'description' => 'nullable|string|max:500',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.generate_checkout_link');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate checkout link',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error generating checkout link: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate checkout link'
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
            'orderReference' => 'required|string|max:100',
            'checksum' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $endpoint = config('clickpesa.endpoints.generate_payout_link');
            $response = $this->makeClickPesaRequest('post', $endpoint, $request->all());

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate payout link',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error generating payout link: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate payout link'
            ], 500);
        }
    }

    // ========================================
    // ACCOUNT METHODS
    // ========================================

    /**
     * Get account balance.
     */
    public function getAccountBalance()
    {
        try {
            $endpoint = config('clickpesa.endpoints.account_balance');
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get account balance',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error getting account balance: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get account balance'
            ], 500);
        }
    }

    /**
     * Get account statement.
     */
    public function getAccountStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:3',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $params = $request->only(['currency', 'startDate', 'endDate']);
            $endpoint = config('clickpesa.endpoints.account_statement') . '?' . http_build_query($params);
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get account statement',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error getting account statement: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get account statement'
            ], 500);
        }
    }

    // ========================================
    // UTILITY METHODS
    // ========================================

    /**
     * Get exchange rates.
     */
    public function getExchangeRates(Request $request)
    {
        try {
            $params = $request->only(['source', 'target']);
            $endpoint = config('clickpesa.endpoints.exchange_rates') . '?' . http_build_query($params);
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get exchange rates',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error getting exchange rates: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get exchange rates'
            ], 500);
        }
    }

    /**
     * Get banks list.
     */
    public function getBanksList()
    {
        try {
            $endpoint = config('clickpesa.endpoints.banks_list');
            $response = $this->makeClickPesaRequest('get', $endpoint);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get banks list',
                'errors' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error('Error getting banks list: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get banks list'
            ], 500);
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get ClickPesa token.
     */
    private function getClickPesaToken()
    {
        // In a real implementation, this would generate or retrieve a JWT token
        // For now, return the configured token
        return config('clickpesa.api_token');
    }

    /**
     * Generate checksum for payload.
     */
    private function generateChecksum($payload)
    {
        // Implement checksum generation based on ClickPesa documentation
        // This is a placeholder implementation
        return hash_hmac('sha256', json_encode($payload), config('clickpesa.secret_key'));
    }

    /**
     * Log transaction to database.
     */
    private function logTransaction($request, $response, $method)
    {
        DB::table('payment_transactions')->insert([
            'user_id' => auth()->id(),
            'transaction_reference' => $response['id'] ?? $request->orderReference,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method' => $method,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Log card transaction to database.
     */
    private function logCardTransaction($request, $response, $method)
    {
        DB::table('card_transactions')->insert([
            'user_id' => auth()->id(),
            'transaction_reference' => $response['id'] ?? $request->orderReference,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'card_number' => substr($request->cardNumber, -4),
            'cardholder_name' => $request->cardholderName,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Log payout to database.
     */
    private function logPayout($request, $response)
    {
        DB::table('payouts')->insert([
            'user_id' => auth()->id(),
            'payout_reference' => $response['id'] ?? $request->orderReference,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'provider' => $request->provider,
            'recipient_phone' => $request->phoneNumber,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ========================================
    // DATA RETRIEVAL METHODS
    // ========================================

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
            Log::error('Error getting recent USSD pushes: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent USSD pushes'
            ], 500);
        }
    }

    /**
     * Get recent control numbers for sidebar.
     */
    public function getRecentControlNumbers()
    {
        try {
            $recentControlNumbers = DB::table('bill_payments')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $recentControlNumbers
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
            $allControlNumbers = DB::table('bill_payments')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $allControlNumbers
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
     * Get recent checkout sessions for sidebar.
     */
    public function getRecentCheckoutSessions()
    {
        try {
            $recentSessions = DB::table('checkout_sessions')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

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
            $allSessions = DB::table('checkout_sessions')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

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
            $session = DB::table('checkout_sessions')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Checkout session not found'
                ], 404);
            }

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

    /**
     * Get recent payouts for sidebar.
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
}
