<?php

namespace FeedTan\ClickPesa\Events;

use FeedTan\ClickPesa\Models\ClickPesaTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ClickPesaTransaction $transaction,
        public array $webhookData
    ) {}
}
