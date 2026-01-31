<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Investment Report - FeedTan CMG</title>
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
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
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
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
        }
        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 35%;
            color: #374151;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
        }
        .info-table td:last-child {
            color: #1a1a1a;
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
        @if(isset($documentTitle))
        <div class="title">{{ $documentTitle }}</div>
        @else
        <div class="title">Investment Report</div>
        @endif
        <div class="serial-number">Serial No: FCMGIR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Investment Portfolio Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Investments:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total']) }}</strong></div>
            <div class="stats-cell stats-label">Total Principal Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_principal'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Profit Share:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_profit'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Active Investments:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['active_count']) }} ({{ number_format($stats['active'], 2) }} TZS)</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Matured Investments:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['matured_count']) }}</strong></div>
            <div class="stats-cell stats-label">Average Principal Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['avg_principal'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Average Interest Rate:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['avg_interest_rate'], 2) }}%</strong></div>
        </div>
    </div>

    <!-- Investments by Type -->
    @if($stats['by_type']->count() > 0)
    <div class="section">
        <div class="section-header">Investments by Plan Type</div>
        <div class="section-content">
            <table class="info-table">
                @foreach($stats['by_type'] as $type)
                <tr>
                    <td>{{ ucfirst($type->plan_type) }}</td>
                    <td>
                        <strong>{{ number_format($type->count) }}</strong> investments | 
                        <strong style="color: #015425;">{{ number_format($type->total, 2) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Investments by Status -->
    @if($stats['by_status']->count() > 0)
    <div class="section">
        <div class="section-header">Investments by Status</div>
        <div class="section-content">
            <table class="info-table">
                @foreach($stats['by_status'] as $status)
                <tr>
                    <td>{{ ucfirst($status->status) }}</td>
                    <td>
                        <strong>{{ number_format($status->count) }}</strong> investments | 
                        <strong style="color: #015425;">{{ number_format($status->total, 2) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Investment Trends -->
    @if($monthlyInvestments->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Investment Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Principal (TZS)</th>
                </tr>
                @foreach($monthlyInvestments as $month)
                <tr>
                    <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                    <td>{{ number_format($month->count) }}</td>
                    <td><strong>{{ number_format($month->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Investment Details -->
    @if($investments->count() > 0)
    <div class="section" style="page-break-before: always;">
        <div class="section-header">Investment Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Member</th>
                    <th>Investment ID</th>
                    <th>Plan Type</th>
                    <th>Principal</th>
                    <th>Interest Rate</th>
                    <th>Profit Share</th>
                    <th>Status</th>
                    <th>Maturity Date</th>
                </tr>
                @foreach($investments->take(100) as $investment)
                <tr>
                    <td>{{ $investment->user->name ?? 'N/A' }}</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $investment->investment_number ?? $investment->id }}</td>
                    <td>{{ ucfirst($investment->plan_type) }}</td>
                    <td>{{ number_format($investment->principal_amount, 2) }}</td>
                    <td>{{ number_format($investment->interest_rate, 2) }}%</td>
                    <td><strong>{{ number_format($investment->profit_share, 2) }}</strong></td>
                    <td>{{ ucfirst($investment->status) }}</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $investment->maturity_date ? \Carbon\Carbon::parse($investment->maturity_date)->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                @endforeach
            </table>
            @if($investments->count() > 100)
            <p style="margin-top: 10px; font-size: 8pt; color: #666; font-style: italic;">
                Showing first 100 investments. Total: {{ number_format($investments->count()) }} investments.
            </p>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Investment Report</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGIR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
