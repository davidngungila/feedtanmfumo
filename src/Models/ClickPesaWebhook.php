<?php

namespace FeedTan\ClickPesa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClickPesaWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'transaction_id',
        'payload',
        'signature',
        'processed',
        'processing_error',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
    ];

    /**
     * Mark webhook as processed.
     */
    public function markAsProcessed(): void
    {
        $this->update([
            'processed' => true,
            'processing_error' => null,
        ]);
    }

    /**
     * Mark webhook as failed with error.
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'processed' => false,
            'processing_error' => $error,
        ]);
    }
}
