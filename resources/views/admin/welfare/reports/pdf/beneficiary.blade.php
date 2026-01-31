<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welfare Beneficiary Report - FeedTan CMG</title>
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
        <div class="title">{{ $documentTitle ?? 'Welfare Beneficiary Report' }}</div>
        <div class="serial-number">Serial No: FCMGWBR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }}<br>
            Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
        </div>
    </div>

    <!-- Report Summary -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Beneficiaries:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_beneficiaries']) }}</strong></div>
            <div class="stats-cell stats-label">Total Benefits:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_benefits'], 2) }} TZS</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Avg per Beneficiary:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['avg_benefit_per_beneficiary'], 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Total Claims:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_claims']) }}</strong></div>
        </div>
    </div>

    <!-- Top Beneficiaries by Amount -->
    <div class="section">
        <div class="section-header">Top Beneficiaries by Amount</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Member</th>
                        <th style="text-align: right;">Total Amount</th>
                        <th style="text-align: right;">Claims</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topBeneficiaries as $index => $beneficiary)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td>{{ $beneficiary->user->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">{{ number_format($beneficiary->total, 2) }} TZS</td>
                        <td style="text-align: right;">{{ number_format($beneficiary->count) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Distribution by Benefit Type -->
    <div class="section">
        <div class="section-header">Distribution by Benefit Type</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th style="text-align: right;">Beneficiaries</th>
                        <th style="text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beneficiaryByType as $type)
                    <tr>
                        <td>{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                        <td style="text-align: right;">{{ number_format($type->beneficiary_count) }}</td>
                        <td style="text-align: right;">{{ number_format($type->total, 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Distribution by Status -->
    <div class="section">
        <div class="section-header">Distribution by Status</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: right;">Beneficiaries</th>
                        <th style="text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beneficiaryByStatus as $status)
                    <tr>
                        <td>{{ ucfirst($status->status) }}</td>
                        <td style="text-align: right;">{{ number_format($status->beneficiary_count) }}</td>
                        <td style="text-align: right;">{{ number_format($status->total, 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Beneficiary Trend -->
    <div class="section">
        <div class="section-header">Monthly Beneficiary Trend</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th style="text-align: right;">Beneficiaries</th>
                        <th style="text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyBeneficiaries as $month)
                    <tr>
                        <td>{{ $month['month'] }}</td>
                        <td style="text-align: right;">{{ number_format($month['count']) }}</td>
                        <td style="text-align: right;">{{ number_format($month['total_amount'], 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Beneficiaries -->
    <div class="section">
        <div class="section-header">All Beneficiaries</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th style="text-align: right;">Total Amount</th>
                        <th style="text-align: right;">Claims</th>
                        <th>First Benefit</th>
                        <th>Last Benefit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beneficiaries as $beneficiary)
                    <tr>
                        <td>{{ $beneficiary->user->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">{{ number_format($beneficiary->total, 2) }} TZS</td>
                        <td style="text-align: right;">{{ number_format($beneficiary->count) }}</td>
                        <td>{{ \Carbon\Carbon::parse($beneficiary->first_benefit)->format('M j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($beneficiary->last_benefit)->format('M j, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Welfare Beneficiary Report</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGWBR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
