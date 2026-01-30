<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SMS Logs Report</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #015425;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo-box {
            display: inline-block;
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .header-info {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            font-size: 8pt;
        }
        .stats-label {
            font-weight: bold;
            color: #015425;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }
        th {
            background: #015425;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .status-accepted {
            background: #d4edda;
            color: #155724;
        }
        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .message-content {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
            @if(isset($logoBase64) && $logoBase64)
            <img src="{{ $logoBase64 }}" alt="FeedTan Logo" style="max-height: 60px; max-width: 150px;">
            @else
            <div class="logo-box">FD</div>
            @endif
            <div style="flex: 1;">
                <strong style="font-size: 12pt; color: #015425;">FeedTan Community Microfinance Group</strong><br>
                <div class="header-info">
                    P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania<br>
                    Email: feedtan15@gmail.com | Phone: +255622239304
                </div>
            </div>
        </div>
        <div class="title">SMS Communication Logs Report</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            @if(!empty($filters))
            Filters Applied:
            @if(isset($filters['from'])) From: {{ $filters['from'] }} @endif
            @if(isset($filters['to'])) To: {{ $filters['to'] }} @endif
            @if(isset($filters['status'])) Status: {{ $filters['status'] }} @endif
            @if(isset($filters['sent_since'])) Since: {{ $filters['sent_since'] }} @endif
            @if(isset($filters['sent_until'])) Until: {{ $filters['sent_until'] }} @endif
            @endif
        </div>
    </div>

    @if($balance)
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">SMS Balance:</div>
            <div class="stats-cell">{{ number_format($balance['sms_balance'] ?? 0) }} ({{ $balance['display'] ?? '' }})</div>
        </div>
    </div>
    @endif

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Logs:</div>
            <div class="stats-cell">{{ number_format($stats['total']) }}</div>
            <div class="stats-cell stats-label">Successful:</div>
            <div class="stats-cell">{{ number_format($stats['success']) }}</div>
            <div class="stats-cell stats-label">Failed:</div>
            <div class="stats-cell">{{ number_format($stats['failed']) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Message ID</th>
                <th style="width: 6%;">From</th>
                <th style="width: 8%;">To</th>
                <th style="width: 20%;">Message</th>
                <th style="width: 8%;">Channel</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 8%;">Sent At</th>
                <th style="width: 8%;">Done At</th>
                <th style="width: 8%;">User</th>
                <th style="width: 6%;">Count</th>
                <th style="width: 6%;">Success</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="font-size: 7pt;">{{ Str::limit($log->message_id ?? 'N/A', 15) }}</td>
                <td>{{ $log->from ?? 'N/A' }}</td>
                <td>{{ $log->to }}</td>
                <td class="message-content">{{ Str::limit($log->message ?? 'N/A', 50) }}</td>
                <td>{{ Str::limit($log->channel ?? 'Internet SMS', 15) }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($log->status_group_name ?? 'unknown') }}">
                        {{ $log->status_group_name ?? 'N/A' }}
                    </span>
                </td>
                <td style="font-size: 7pt;">{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : 'N/A' }}</td>
                <td style="font-size: 7pt;">{{ $log->done_at ? $log->done_at->format('Y-m-d H:i') : 'N/A' }}</td>
                <td style="font-size: 7pt;">{{ $log->user ? Str::limit($log->user->name, 15) : 'N/A' }}</td>
                <td style="text-align: center;">{{ $log->sms_count ?? 1 }}</td>
                <td style="text-align: center;">{{ $log->success ? 'Yes' : 'No' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 20px;">No SMS logs found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - SMS Communication Logs</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $logs->count() }}</p>
    </div>
</body>
</html>

