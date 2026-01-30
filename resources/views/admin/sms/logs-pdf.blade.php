<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SMS Communication Logs Report</title>
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
            width: 50px;
            vertical-align: middle;
        }
        
        .logo-image img {
            max-width: 50px;
            max-height: 50px;
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
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            text-align: center;
            margin: 12px 0 4px 0;
        }
        
        .serial-number {
            font-size: 8pt;
            color: #666;
            text-align: center;
            margin-bottom: 8px;
            font-family: 'Courier New', monospace;
        }
        
        /* Stats Section */
        .stats-section {
            margin: 10px 0;
            page-break-inside: avoid;
        }
        
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8pt;
        }
        
        .stats-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .stats-label {
            font-weight: bold;
            color: #015425;
            width: 30%;
        }
        
        .stats-value {
            color: #1a1a1a;
        }
        
        /* Main Table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
            page-break-inside: avoid;
        }
        
        .main-table th {
            background: #015425;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
            font-size: 8pt;
        }
        
        .main-table td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .main-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
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
        
        .message-content {
            max-width: 200px;
            word-wrap: break-word;
            font-size: 7pt;
        }
        
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Document Header -->
    <div class="document-header">
        <div class="header-top">
            <div class="header-left">
                <div class="logo-section">
                    <div class="logo-image">
                        @php
                            $logoPath = public_path('feedtan_logo.png');
                            $logoPath = str_replace('\\', '/', $logoPath);
                        @endphp
                        <img src="{{ $logoPath }}" alt="FeedTan Logo" style="max-width: 50px; max-height: 50px;">
                    </div>
                    <div class="logo-text">
                        <div class="company-name-main">FeedTan</div>
                        <div class="company-name-sub">Community Microfinance Group</div>
                    </div>
                </div>
                <div class="contact-info">
                    P.o.Box 7744, Ushirika Sokoine Road, Moshi Kilimanjaro Tanzania<br>
                    Email: Feedtan15@gmail.com | Mobile: +255622239304
                </div>
            </div>
        </div>
        
        <div class="document-title">SMS Communication Logs</div>
        <div class="serial-number">Serial No: FCMGSMS{{ date('dmy') }}{{ str_pad($logs->count(), 4, '0', STR_PAD_LEFT) }}</div>
        <div class="contact-info" style="text-align: center; margin-top: 4px;">
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

    <!-- Statistics Section -->
    <div class="stats-section">
        <table class="stats-table">
            @if($balance)
            <tr>
                <td class="stats-label">SMS Balance:</td>
                <td class="stats-value">{{ number_format($balance['sms_balance'] ?? 0) }} ({{ $balance['display'] ?? '' }})</td>
            </tr>
            @endif
            <tr>
                <td class="stats-label">Total Logs:</td>
                <td class="stats-value">{{ number_format($stats['total']) }}</td>
                <td class="stats-label">Successful:</td>
                <td class="stats-value" style="color: #155724; font-weight: bold;">{{ number_format($stats['success']) }}</td>
                <td class="stats-label">Failed:</td>
                <td class="stats-value" style="color: #721c24; font-weight: bold;">{{ number_format($stats['failed']) }}</td>
            </tr>
        </table>
    </div>

    <!-- Main Logs Table -->
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 8%;">Message ID</th>
                <th style="width: 6%;">From</th>
                <th style="width: 8%;">To</th>
                <th style="width: 18%;">Message</th>
                <th style="width: 8%;">Channel</th>
                <th style="width: 7%;">Status</th>
                <th style="width: 9%;">Sent At</th>
                <th style="width: 9%;">Done At</th>
                <th style="width: 8%;">User</th>
                <th style="width: 5%;">Count</th>
                <th style="width: 6%;">Success</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="font-size: 7pt; font-family: 'Courier New', monospace;">{{ Str::limit($log->message_id ?? 'N/A', 12) }}</td>
                <td>{{ $log->from ?? 'N/A' }}</td>
                <td style="font-family: 'Courier New', monospace;">{{ $log->to }}</td>
                <td class="message-content">
                    @if($log->message)
                        {{ Str::limit($log->message, 40) }}
                    @else
                        <span style="color: #999; font-style: italic;">No message</span>
                    @endif
                </td>
                <td style="font-size: 7pt;">{{ Str::limit($log->channel ?? 'Internet SMS', 12) }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($log->status_group_name ?? 'unknown') }}">
                        {{ $log->status_group_name ?? 'N/A' }}
                    </span>
                </td>
                <td style="font-size: 7pt; font-family: 'Courier New', monospace;">{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : 'N/A' }}</td>
                <td style="font-size: 7pt; font-family: 'Courier New', monospace;">{{ $log->done_at ? $log->done_at->format('Y-m-d H:i') : 'N/A' }}</td>
                <td style="font-size: 7pt;">{{ $log->user ? Str::limit($log->user->name, 12) : 'N/A' }}</td>
                <td style="text-align: center;">{{ $log->sms_count ?? 1 }}</td>
                <td style="text-align: center; font-weight: bold; {{ $log->success ? 'color: #155724;' : 'color: #721c24;' }}">
                    {{ $log->success ? 'Yes' : 'No' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 20px; color: #999;">No SMS logs found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Document Footer -->
    <div class="footer">
        <div style="margin: 5px 0;"><strong>FeedTan Community Microfinance Group</strong></div>
        <div style="margin: 5px 0;">P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</div>
        <div style="margin: 5px 0;">Email: Feedtan15@gmail.com | Mobile: +255622239304</div>
        <div style="margin-top: 10px; font-size: 7pt; color: #999; font-style: italic;">
            This is a computer-generated document. Generated on {{ date('d F Y, H:i:s') }} | Serial No: FCMGSMS{{ date('dmy') }}{{ str_pad($logs->count(), 4, '0', STR_PAD_LEFT) }}
        </div>
    </div>
</body>
</html>
