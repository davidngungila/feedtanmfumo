<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    protected $fillable = [
        'message_id',
        'reference',
        'from',
        'to',
        'message',
        'channel',
        'sms_count',
        'status_group_id',
        'status_group_name',
        'status_id',
        'status_name',
        'status_description',
        'sent_at',
        'done_at',
        'delivery',
        'api_response',
        'user_id',
        'sent_by',
        'template_id',
        'saving_behavior',
        'success',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'done_at' => 'datetime',
        'delivery' => 'array',
        'api_response' => 'array',
        'sms_count' => 'integer',
        'status_id' => 'integer',
        'status_group_id' => 'integer',
        'success' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sentByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(SmsMessageTemplate::class, 'template_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_group_name) {
            'ACCEPTED', 'DELIVERED' => 'bg-green-100 text-green-800',
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'REJECTED', 'FAILED' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
