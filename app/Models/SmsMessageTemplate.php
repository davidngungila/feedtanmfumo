<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsMessageTemplate extends Model
{
    protected $fillable = [
        'template_name',
        'behavior_type',
        'message_content',
        'language',
        'priority',
        'variables',
        'last_modified',
    ];

    protected $casts = [
        'variables' => 'array',
        'last_modified' => 'datetime',
        'priority' => 'integer',
    ];

    public function getMessageForUser(\App\Models\User $user, array $additionalData = []): string
    {
        $message = $this->message_content;
        $variables = $this->variables ?? [];

        // Replace common variables
        $replacements = [
            '{{name}}' => explode(' ', $user->name)[0],
            '{{full_name}}' => $user->name,
            '{{organization_name}}' => $additionalData['organization_name'] ?? 'FeedTan CMG',
            '{{amount}}' => $additionalData['amount'] ?? '',
            '{{member_id}}' => $user->member_number ?? $user->membership_code ?? '',
        ];

        // Add custom variables from additional data
        foreach ($additionalData as $key => $value) {
            $replacements["{{{$key}}}"] = $value;
        }

        // Replace all variables in message
        foreach ($replacements as $placeholder => $replacement) {
            $message = str_replace($placeholder, $replacement, $message);
        }

        return $message;
    }
}
