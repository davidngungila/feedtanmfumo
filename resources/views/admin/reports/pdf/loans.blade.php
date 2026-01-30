<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Portfolio Report - FeedTan CMG</title>
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
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
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
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" class="header-image">
            @else
            <div style="background: #015425; color: white; padding: 8px 12px; font-weight: bold; font-size: 14pt; margin: 0 auto 10px auto; display: inline-block;">FD</div>
            @endif
        </div>
        <div class="title">{{ $documentTitle ?? 'Loan Portfolio Report' }}</div>
        @if(isset($documentSubtitle))
        <div style="font-size: 10pt; color: #666; margin-top: -5px; margin-bottom: 10px;">{{ $documentSubtitle }}</div>
        @endif
        <div class="serial-number">Serial No: FCMGLR{{ date('dmy') }}{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Loan Portfolio Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total']) }}</strong></div>
            <div class="stats-cell stats-label">Total Loan Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_amount'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Paid Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['paid_amount'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Remaining Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['remaining_amount'], 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Active Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['active_count']) }}</strong></div>
            <div class="stats-cell stats-label">Pending Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['pending_count']) }}</strong></div>
            <div class="stats-cell stats-label">Completed Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['completed_count']) }}</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Overdue Loans:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['overdue_count']) }}</strong></div>
            <div class="stats-cell stats-label">Average Loan Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['avg_loan_amount'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Recovery Rate:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['recovery_rate'], 2) }}%</strong></div>
        </div>
    </div>

    <!-- Loans by Status -->
    @if($loansByStatus->count() > 0)
    <div class="section">
        <div class="section-header">Loans by Status</div>
        <div class="section-content">
            <table class="info-table">
                @foreach($loansByStatus as $stat)
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

    <!-- Monthly Loan Trends -->
    @if($loansByMonth->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Loan Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($loansByMonth as $month)
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

    <!-- Loan Details -->
    @if($loans->count() > 0)
    <div class="section" style="page-break-before: always;">
        <div class="section-header">Loan Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Member</th>
                    <th>Loan ID</th>
                    <th>Principal</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Maturity Date</th>
                </tr>
                @foreach($loans->take(100) as $loan)
                <tr>
                    <td>{{ $loan->user->name ?? 'N/A' }}</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $loan->loan_number ?? $loan->id }}</td>
                    <td>{{ number_format($loan->principal_amount, 2) }}</td>
                    <td>{{ number_format($loan->paid_amount, 2) }}</td>
                    <td><strong>{{ number_format($loan->remaining_amount, 2) }}</strong></td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $loan->maturity_date ? \Carbon\Carbon::parse($loan->maturity_date)->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                @endforeach
            </table>
            @if($loans->count() > 100)
            <p style="margin-top: 10px; font-size: 8pt; color: #666; font-style: italic;">
                Showing first 100 loans. Total: {{ number_format($loans->count()) }} loans.
            </p>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Loan Portfolio Report</p>
        <p>Report generated on {{ ($generatedAt ? \Carbon\Carbon::parse($generatedAt) : now())->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGLR{{ date('dmy') }}{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
