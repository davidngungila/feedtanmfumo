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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1a1a1a;
            background: #ffffff;
        }
        
        /* Header Styles - Matching Loan Statement */
        .document-header {
            border-bottom: 2px solid #015425;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 70%;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 30%;
        }
        
        .logo-section {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .logo-image {
            display: table-cell;
            vertical-align: middle;
            width: 50px;
            height: 50px;
        }
        
        .logo-image img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        
        .logo-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 8px;
        }
        
        .company-name-main {
            font-size: 16pt;
            font-weight: bold;
            color: #015425;
            margin-bottom: 1px;
            line-height: 1.2;
        }
        
        .company-name-sub {
            font-size: 11pt;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
            line-height: 1.2;
        }
        
        .contact-info {
            font-size: 8pt;
            color: #4a5568;
            line-height: 1.4;
        }
        
        .document-title {
            font-size: 16pt;
            font-weight: bold;
            color: #015425;
            text-align: center;
            margin: 12px 0 4px 0;
        }
        
        .serial-number {
            font-size: 9pt;
            color: #4a5568;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .header-info {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }
        /* Stats Section */
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
        
        /* Table Styles */
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
        
        /* Status Badges */
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
        
        /* Footer */
        .document-footer {
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
    <!-- Document Header - Matching Loan Statement Style -->
    <div class="document-header">
        <div class="header-top">
            <div class="header-left">
                <div class="logo-section">
                    <div class="logo-image">
                        @if(isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" alt="FeedTan Logo">
                        @else
                        <div style="width: 50px; height: 50px; background: #015425; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18pt;">FD</div>
                        @endif
                    </div>
                    <div class="logo-text">
                        <div class="company-name-main">FeedTan</div>
                        <div class="company-name-sub">Community Microfinance Group</div>
                    </div>
                </div>
                <div class="contact-info">
                    P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania<br>
                    Email: feedtan15@gmail.com | Mobile: +255622239304
                </div>
            </div>
        </div>
        
        <div class="document-title">SMS Communication Logs Report</div>
        <div class="serial-number">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
            @if(!empty($filters))
            | Filters: 
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

    <!-- Document Footer -->
    <div class="document-footer">
        <div style="margin: 5px 0;"><strong>FeedTan Community Microfinance Group</strong></div>
        <div style="margin: 5px 0;">P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</div>
        <div style="margin: 5px 0;">Email: feedtan15@gmail.com | Mobile: +255622239304</div>
        <div style="margin-top: 10px; font-size: 7pt; color: #999; font-style: italic;">
            This is a computer-generated document. Generated on {{ now()->format('d F Y, H:i:s') }} | Total Records: {{ $logs->count() }}
        </div>
    </div>
</body>
</html>

