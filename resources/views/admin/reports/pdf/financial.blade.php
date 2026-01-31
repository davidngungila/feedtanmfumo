<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report - FeedTan CMG</title>
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
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 6px;
            vertical-align: top;
        }
        .column:first-child {
            padding-left: 0;
        }
        .column:last-child {
            padding-right: 0;
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
        <div class="title">Financial Report</div>
        @endif
        <div class="serial-number">Serial No: FCMGFR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Financial Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Outstanding Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_loans'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Total Savings:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_savings'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Active Investments:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_investments'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Welfare Fund:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_welfare_fund'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Loan Principal:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_principal'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Total Paid:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_paid'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Total Revenue:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_revenue'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Members:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_members']) }}</strong></div>
        </div>
    </div>

    <!-- Loan Statistics by Status -->
    @if($loanStats->count() > 0)
    <div class="section">
        <div class="section-header">Loan Statistics by Status</div>
        <div class="section-content">
            <table class="info-table">
                @foreach($loanStats as $stat)
                <tr>
                    <td>{{ ucfirst($stat->status) }}</td>
                    <td>
                        <strong>{{ number_format($stat->count) }}</strong> loans | 
                        <strong style="color: #015425;">{{ number_format($stat->total, 2) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Savings Statistics by Type -->
    @if($savingsStats->count() > 0)
    <div class="section">
        <div class="section-header">Savings Statistics by Account Type</div>
        <div class="section-content">
            <table class="info-table">
                @foreach($savingsStats as $stat)
                <tr>
                    <td>{{ ucfirst($stat->account_type) }}</td>
                    <td>
                        <strong>{{ number_format($stat->count) }}</strong> accounts | 
                        <strong style="color: #015425;">{{ number_format($stat->total, 2) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Loan Trends -->
    @if($monthlyLoans->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Loan Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($monthlyLoans as $month)
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

    <!-- Monthly Savings Trends -->
    @if($monthlySavings->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Savings Deposits ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Total Deposits (TZS)</th>
                </tr>
                @foreach($monthlySavings as $month)
                <tr>
                    <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                    <td><strong>{{ number_format($month->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Financial Report</p>
        <p>Report generated on {{ ($generatedAt ? \Carbon\Carbon::parse($generatedAt) : now())->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGFR{{ date('dmy') }}{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
