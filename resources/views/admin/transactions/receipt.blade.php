<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Receipt - {{ $transaction->transaction_number }}</title>
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
        .amount-box {
            background: #f0f9ff;
            border: 2px solid #015425;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 24pt;
            font-weight: bold;
            color: #015425;
            font-family: 'Courier New', monospace;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
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
        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 40px auto 5px auto;
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
        <div class="title">Transaction Receipt</div>
        @endif
        <div class="serial-number">Serial No: FCMGTR-{{ date('Ymd') }}-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Receipt No: {{ $transaction->transaction_number }} | Date: {{ $transaction->transaction_date->format('F d, Y') }}
        </div>
    </div>

    <!-- Amount Box -->
    <div class="amount-box">
        <div class="amount-label">Transaction Amount</div>
        <div class="amount-value">{{ number_format($transaction->amount, 2) }} TZS</div>
    </div>

    <!-- Transaction Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Transaction Number:</div>
            <div class="stats-cell"><strong style="font-family: 'Courier New', monospace; font-size: 11pt;">{{ $transaction->transaction_number }}</strong></div>
            <div class="stats-cell stats-label">Status:</div>
            <div class="stats-cell">
                <span class="status-badge status-{{ $transaction->status }}">
                    {{ strtoupper($transaction->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Transaction Type:</div>
            <div class="stats-cell"><strong>{{ $transactionTypeLabel }}</strong></div>
            <div class="stats-cell stats-label">Payment Method:</div>
            <div class="stats-cell"><strong>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</strong></div>
        </div>
    </div>

    @if($transaction->reference_number)
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Reference Number:</div>
            <div class="stats-cell"><strong style="font-family: 'Courier New', monospace;">{{ $transaction->reference_number }}</strong></div>
        </div>
    </div>
    @endif

    <!-- Member Information -->
    <div class="section">
        <div class="section-header">Member Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Member Name</td>
                    <td><strong>{{ strtoupper($transaction->user->name) }}</strong></td>
                </tr>
                <tr>
                    <td>Membership Code</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $transaction->user->membership_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td>{{ $transaction->user->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $transaction->user->phone ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="section">
        <div class="section-header">Transaction Details</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Transaction Date</td>
                    <td><strong>{{ $transaction->transaction_date->format('F d, Y') }}</strong></td>
                </tr>
                <tr>
                    <td>Transaction Time</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $transaction->created_at->format('H:i:s') }}</td>
                </tr>
                <tr>
                    <td>Transaction Type</td>
                    <td><strong>{{ $transactionTypeLabel }}</strong></td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td><strong style="font-size: 14pt; color: #015425;">{{ number_format($transaction->amount, 2) }} TZS</strong></td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                </tr>
                @if($transaction->reference_number)
                <tr>
                    <td>Reference Number</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $transaction->reference_number }}</td>
                </tr>
                @endif
                <tr>
                    <td>Status</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                </tr>
                @if($transaction->description)
                <tr>
                    <td>Description</td>
                    <td>{{ $transaction->description }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Related Entity Details -->
    @if(!empty($relatedDetails))
    <div class="section">
        <div class="section-header">{{ $relatedDetails['type'] }} Details</div>
        <div class="section-content">
            <table class="info-table">
                @if(isset($relatedDetails['number']))
                <tr>
                    <td>{{ $relatedDetails['type'] }} Number</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $relatedDetails['number'] }}</td>
                </tr>
                @endif
                @if(isset($relatedDetails['account_type']))
                <tr>
                    <td>Account Type</td>
                    <td><strong>{{ $relatedDetails['account_type'] }}</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['plan_type']))
                <tr>
                    <td>Plan Type</td>
                    <td><strong>{{ $relatedDetails['plan_type'] }}</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['principal']))
                <tr>
                    <td>Principal Amount</td>
                    <td><strong>{{ $relatedDetails['principal'] }} TZS</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['remaining']))
                <tr>
                    <td>Remaining Amount</td>
                    <td><strong>{{ $relatedDetails['remaining'] }} TZS</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['balance']))
                <tr>
                    <td>Account Balance</td>
                    <td><strong style="color: #015425;">{{ $relatedDetails['balance'] }} TZS</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['welfare_type']))
                <tr>
                    <td>Welfare Type</td>
                    <td><strong>{{ $relatedDetails['welfare_type'] }}</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['benefit_type']))
                <tr>
                    <td>Benefit Type</td>
                    <td><strong>{{ $relatedDetails['benefit_type'] }}</strong></td>
                </tr>
                @endif
                @if(isset($relatedDetails['status']))
                <tr>
                    <td>Status</td>
                    <td>{{ $relatedDetails['status'] }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif

    <!-- Processing Information -->
    @if($transaction->processor)
    <div class="section">
        <div class="section-header">Processing Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Processed By</td>
                    <td><strong>{{ $transaction->processor->name }}</strong></td>
                </tr>
                <tr>
                    <td>Processed On</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $transaction->created_at->format('F d, Y H:i:s') }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    <!-- Organization Information -->
    <div class="section">
        <div class="section-header">Organization Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Organization Name</td>
                    <td><strong>{{ $organizationInfo['name'] }}</strong></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $organizationInfo['address'] }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $organizationInfo['email'] }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $organizationInfo['phone'] }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="font-size: 8pt; color: #666;">Member Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div style="font-size: 8pt; color: #666;">Authorized Signatory</div>
        </div>
    </div>

    <div class="footer">
        <p><strong>{{ $organizationInfo['name'] }}</strong></p>
        <p>{{ $organizationInfo['address'] }}</p>
        <p>Email: {{ $organizationInfo['email'] }} | Phone: {{ $organizationInfo['phone'] }}</p>
        <p style="margin-top: 10px; font-size: 7pt; color: #999; font-style: italic;">
            This is a computer-generated receipt. Receipt No: {{ $transaction->transaction_number }}
        </p>
        <p style="font-size: 7pt; color: #999;">
            Generated on {{ ($generatedAt ? \Carbon\Carbon::parse($generatedAt) : now())->format('F d, Y \a\t H:i:s') }}
        </p>
    </div>
</body>
</html>

