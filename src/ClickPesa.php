<?php

namespace FeedTan\ClickPesa;

use Illuminate\Support\Facades\Validator;

class ClickPesa
{
    protected ClickPesaClient $client;

    public function __construct(ClickPesaClient $client)
    {
        $this->client = $client;
    }

    /**
     * Initiate USSD checkout payment.
     */
    public function initiateUssdCheckout(array $data): array
    {
        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:100',
            'phone_number' => 'required|string|regex:/^[+][0-9]{12,15}$/',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'merchant_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        $payload = array_merge([
            'payment_method' => 'ussd',
            'merchant_id' => $this->client->config['merchant_id'],
            'callback_url' => $this->client->config['callback_url'],
            'return_url' => $this->client->config['return_url'],
        ], $data);

        return $this->client->post('/payments/checkout', $payload);
    }

    /**
     * Initiate card payment.
     */
    public function initiateCardPayment(array $data): array
    {
        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:100',
            'card_number' => 'required|string|regex:/^[0-9]{13,19}$/',
            'card_expiry' => 'required|string|regex:/^[0-9]{4}$/',
            'card_cvv' => 'required|string|regex:/^[0-9]{3,4}$/',
            'card_holder' => 'required|string|max:100',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            '3ds_required' => 'boolean',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'merchant_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        $payload = array_merge([
            'payment_method' => 'card',
            'merchant_id' => $this->client->config['merchant_id'],
            '3ds_required' => $this->client->config['payment_methods']['card']['3ds_required'] ?? true,
            'callback_url' => $this->client->config['callback_url'],
            'return_url' => $this->client->config['return_url'],
        ], $data);

        return $this->client->post('/payments/card', $payload);
    }

    /**
     * Initiate mobile money payment.
     */
    public function initiateMobilePayment(array $data): array
    {
        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:100',
            'phone_number' => 'required|string|regex:/^[+][0-9]{12,15}$/',
            'provider' => 'required|string|in:tigo,vodacom,airtel,halotel',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'merchant_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        $payload = array_merge([
            'payment_method' => 'mobile',
            'merchant_id' => $this->client->config['merchant_id'],
            'callback_url' => $this->client->config['callback_url'],
            'return_url' => $this->client->config['return_url'],
        ], $data);

        return $this->client->post('/payments/mobile', $payload);
    }

    /**
     * Query payment status.
     */
    public function queryPaymentStatus(string $transactionId): array
    {
        if (empty($transactionId)) {
            throw new \InvalidArgumentException('Transaction ID is required');
        }

        return $this->client->get("/payments/{$transactionId}/status");
    }

    /**
     * Query multiple payment statuses.
     */
    public function queryPaymentStatuses(array $transactionIds): array
    {
        if (empty($transactionIds)) {
            throw new \InvalidArgumentException('At least one transaction ID is required');
        }

        if (count($transactionIds) > 50) {
            throw new \InvalidArgumentException('Maximum 50 transaction IDs allowed per request');
        }

        return $this->client->post('/payments/status/batch', [
            'transaction_ids' => $transactionIds,
        ]);
    }

    /**
     * Get wallet balance.
     */
    public function getWalletBalance(string $walletId = null): array
    {
        $endpoint = '/wallet/balance';
        
        if ($walletId) {
            $endpoint .= '/' . $walletId;
        }

        return $this->client->get($endpoint);
    }

    /**
     * Get transaction history.
     */
    public function getTransactionHistory(array $filters = []): array
    {
        $validator = Validator::make($filters, [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:pending,completed,failed,cancelled',
            'payment_method' => 'nullable|string|in:ussd,card,mobile',
            'limit' => 'nullable|integer|min:1|max:100',
            'offset' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        return $this->client->get('/transactions', $filters);
    }

    /**
     * Refund payment.
     */
    public function refundPayment(string $transactionId, array $data): array
    {
        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
            'reference' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        return $this->client->post("/payments/{$transactionId}/refund", $data);
    }

    /**
     * Create payment link.
     */
    public function createPaymentLink(array $data): array
    {
        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'expiry_hours' => 'nullable|integer|min:1|max:168', // Max 7 days
            'customer_email' => 'nullable|email',
            'customer_name' => 'nullable|string|max:100',
            'merchant_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        $payload = array_merge([
            'merchant_id' => $this->client->config['merchant_id'],
        ], $data);

        return $this->client->post('/payments/link', $payload);
    }

    /**
     * Get payment link details.
     */
    public function getPaymentLink(string $linkId): array
    {
        if (empty($linkId)) {
            throw new \InvalidArgumentException('Payment link ID is required');
        }

        return $this->client->get("/payments/link/{$linkId}");
    }

    /**
     * Validate webhook signature.
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        if (empty($this->client->config['webhook']['secret'])) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->client->config['webhook']['secret']);
        return hash_equals($expectedSignature, $signature);
    }
}
