<?php

namespace FeedTan\ClickPesa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use FeedTan\ClickPesa\Facades\ClickPesa;
use FeedTan\ClickPesa\Models\ClickPesaTransaction;

class WebhookController
{
    /**
     * Handle ClickPesa webhook.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('X-ClickPesa-Signature');

        // Validate webhook signature
        if (!ClickPesa::validateWebhookSignature($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);

        if (!$data) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        // Store webhook for processing
        \FeedTan\ClickPesa\Models\ClickPesaWebhook::create([
            'event_type' => $data['event_type'] ?? 'unknown',
            'transaction_id' => $data['transaction_id'] ?? null,
            'payload' => $payload,
            'signature' => $signature,
        ]);

        // Process the webhook based on event type
        $this->processWebhook($data);

        return response()->json(['status' => 'success']);
    }

    /**
     * Process webhook based on event type.
     */
    protected function processWebhook(array $data): void
    {
        try {
            match($data['event_type']) {
                'payment.completed' => $this->handlePaymentCompleted($data),
                'payment.failed' => $this->handlePaymentFailed($data),
                'payment.cancelled' => $this->handlePaymentCancelled($data),
                'payment.refunded' => $this->handlePaymentRefunded($data),
                default => $this->handleUnknownEvent($data),
            };
        } catch (\Exception $e) {
            \Log::error('Webhook processing failed', [
                'event_type' => $data['event_type'] ?? 'unknown',
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Handle payment completed event.
     */
    protected function handlePaymentCompleted(array $data): void
    {
        $transaction = ClickPesaTransaction::where('transaction_id', $data['transaction_id'])->first();
        
        if ($transaction) {
            $transaction->update([
                'status' => 'completed',
                'completed_at' => now(),
                'response_data' => $data,
            ]);

            // Trigger payment completed event
            event(new \FeedTan\ClickPesa\Events\PaymentCompleted($transaction, $data));
        }
    }

    /**
     * Handle payment failed event.
     */
    protected function handlePaymentFailed(array $data): void
    {
        $transaction = ClickPesaTransaction::where('transaction_id', $data['transaction_id'])->first();
        
        if ($transaction) {
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => $data['failure_reason'] ?? 'Unknown error',
                'response_data' => $data,
            ]);

            // Trigger payment failed event
            event(new \FeedTan\ClickPesa\Events\PaymentFailed($transaction, $data));
        }
    }

    /**
     * Handle payment cancelled event.
     */
    protected function handlePaymentCancelled(array $data): void
    {
        $transaction = ClickPesaTransaction::where('transaction_id', $data['transaction_id'])->first();
        
        if ($transaction) {
            $transaction->update([
                'status' => 'cancelled',
                'response_data' => $data,
            ]);

            // Trigger payment cancelled event
            event(new \FeedTan\ClickPesa\Events\PaymentCancelled($transaction, $data));
        }
    }

    /**
     * Handle payment refunded event.
     */
    protected function handlePaymentRefunded(array $data): void
    {
        $transaction = ClickPesaTransaction::where('transaction_id', $data['transaction_id'])->first();
        
        if ($transaction) {
            $transaction->update([
                'status' => 'refunded',
                'response_data' => $data,
            ]);

            // Trigger payment refunded event
            event(new \FeedTan\ClickPesa\Events\PaymentRefunded($transaction, $data));
        }
    }

    /**
     * Handle unknown event type.
     */
    protected function handleUnknownEvent(array $data): void
    {
        \Log::warning('Unknown webhook event received', [
            'event_type' => $data['event_type'] ?? 'unknown',
            'data' => $data,
        ]);
    }

    /**
     * Handle ClickPesa callback.
     */
    public function callback(Request $request): JsonResponse
    {
        $data = $request->validate([
            'transaction_id' => 'required|string',
            'status' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string',
        ]);

        $transaction = ClickPesaTransaction::where('transaction_id', $data['transaction_id'])->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->update([
            'status' => $data['status'],
            'response_data' => $data,
            'completed_at' => $data['status'] === 'completed' ? now() : null,
        ]);

        return response()->json(['status' => 'success']);
    }
}
