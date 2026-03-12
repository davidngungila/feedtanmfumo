<?php

namespace FeedTan\ClickPesa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array initiateUssdCheckout(array $data)
 * @method static array initiateCardPayment(array $data)
 * @method static array initiateMobilePayment(array $data)
 * @method static array queryPaymentStatus(string $transactionId)
 * @method static array queryPaymentStatuses(array $transactionIds)
 * @method static array getWalletBalance(string $walletId = null)
 * @method static array getTransactionHistory(array $filters = [])
 * @method static array refundPayment(string $transactionId, array $data)
 * @method static array createPaymentLink(array $data)
 * @method static array getPaymentLink(string $linkId)
 * @method static bool validateWebhookSignature(string $payload, string $signature)
 */
class ClickPesa extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'clickpesa';
    }
}
