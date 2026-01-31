<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welfare Claim Analysis Report - FeedTan CMG</title>
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
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
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
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
        }
        .serial-number {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
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
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-header {
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 8px;
        }
        .section-content {
            padding: 8px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @if(isset($headerBase64) && $headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" style="width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            @else
            <div class="logo-box" style="margin: 0 auto 10px auto;">FD</div>
            @endif
        </div>
        <div class="title">{{ $documentTitle ?? 'Welfare Claim Analysis Report' }}</div>
        <div class="serial-number">Serial No: FCMGWCAR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }}<br>
            Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
        </div>
    </div>

    <!-- Report Summary -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Claims:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_claims']) }}</strong></div>
            <div class="stats-cell stats-label">Approval Rate:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['approval_rate'], 1) }}%</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Approved:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['approved_count']) }}</strong></div>
            <div class="stats-cell stats-label">Avg Processing Days:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['avg_processing_days'], 1) }} days</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Rejected:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['rejected_count']) }}</strong></div>
            <div class="stats-cell stats-label">Total Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_amount'], 2) }} TZS</strong></div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="section">
        <div class="section-header">Status Breakdown</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: right;">Count</th>
                        <th style="text-align: right;">Total Amount</th>
                        <th style="text-align: right;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusBreakdown as $status)
                    <tr>
                        <td>{{ ucfirst($status->status) }}</td>
                        <td style="text-align: right;">{{ number_format($status->count) }}</td>
                        <td style="text-align: right;">{{ number_format($status->total, 2) }} TZS</td>
                        <td style="text-align: right;">{{ $stats['total_claims'] > 0 ? number_format(($status->count / $stats['total_claims']) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Benefit Type Analysis -->
    <div class="section">
        <div class="section-header">Benefit Type Analysis</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Benefit Type</th>
                        <th style="text-align: right;">Count</th>
                        <th style="text-align: right;">Total Amount</th>
                        <th style="text-align: right;">Average</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($benefitTypeBreakdown as $type)
                    <tr>
                        <td>{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                        <td style="text-align: right;">{{ number_format($type->count) }}</td>
                        <td style="text-align: right;">{{ number_format($type->total, 2) }} TZS</td>
                        <td style="text-align: right;">{{ number_format($type->average, 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Claims Trend -->
    <div class="section">
        <div class="section-header">Monthly Claims Trend</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th style="text-align: right;">Total</th>
                        <th style="text-align: right;">Approved</th>
                        <th style="text-align: right;">Rejected</th>
                        <th style="text-align: right;">Pending</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyClaims as $month)
                    <tr>
                        <td>{{ $month['month'] }}</td>
                        <td style="text-align: right;">{{ number_format($month['total']) }}</td>
                        <td style="text-align: right;">{{ number_format($month['approved']) }}</td>
                        <td style="text-align: right;">{{ number_format($month['rejected']) }}</td>
                        <td style="text-align: right;">{{ number_format($month['pending']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Claims -->
    <div class="section">
        <div class="section-header">Recent Claims</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentClaims as $claim)
                    <tr>
                        <td>{{ $claim->transaction_date->format('M j, Y') }}</td>
                        <td>{{ $claim->user->name }}</td>
                        <td>{{ $claim->benefit_type_name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($claim->status) }}</td>
                        <td style="text-align: right;">{{ number_format($claim->amount, 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Welfare Claim Analysis Report</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGWCAR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
