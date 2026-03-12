<?php

namespace Tests\Feature;

use Tests\TestCase;
use FeedTan\ClickPesa\Facades\ClickPesa;

class ClickPesaTest extends TestCase
{
    /** @test */
    public function it_can_initiate_ussd_checkout()
    {
        $data = [
            'amount' => 10000,
            'phone_number' => '+255712345678',
            'currency' => 'TZS',
            'reference' => 'TEST-001',
            'description' => 'Test payment',
        ];

        $response = ClickPesa::initiateUssdCheckout($data);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('transaction_id', $response);
        $this->assertArrayHasKey('status', $response);
    }

    /** @test */
    public function it_can_initiate_card_payment()
    {
        $data = [
            'amount' => 10000,
            'card_number' => '4111111111111111',
            'card_expiry' => '1225',
            'card_cvv' => '123',
            'card_holder' => 'John Doe',
            'currency' => 'TZS',
            'reference' => 'TEST-002',
        ];

        $response = ClickPesa::initiateCardPayment($data);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('transaction_id', $response);
        $this->assertArrayHasKey('status', $response);
    }

    /** @test */
    public function it_can_query_payment_status()
    {
        $transactionId = 'TXN123456789';

        $response = ClickPesa::queryPaymentStatus($transactionId);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('amount', $response);
    }

    /** @test */
    public function it_can_get_wallet_balance()
    {
        $response = ClickPesa::getWalletBalance();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('balance', $response);
        $this->assertArrayHasKey('currency', $response);
    }

    /** @test */
    public function it_validates_webhook_signature()
    {
        $payload = json_encode(['test' => 'data']);
        $secret = 'test-secret';
        $signature = hash_hmac('sha256', $payload, $secret);

        $isValid = ClickPesa::validateWebhookSignature($payload, $signature);

        $this->assertTrue($isValid);
    }
}
