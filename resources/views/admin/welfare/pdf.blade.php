<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welfare Record - {{ $welfare->welfare_number }}</title>
    <style>
        @page {
            margin: 5mm 8mm;
            size: 112mm auto;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #333;
            width: 112mm;
        }
        .header {
            border-bottom: 2px solid #015425;
            padding-bottom: 10px;
            margin-bottom: 10px;
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
            font-size: 14pt;
            font-weight: bold;
            color: #015425;
            margin: 10px 0 8px 0;
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
            margin: 10px 0;
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
            margin: 10px 0;
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
            padding: 10px;
            text-align: center;
            margin: 15px 0;
        }
        .amount-label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 18pt;
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
        .status-approved, .status-completed, .status-disbursed {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-rejected {
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
        <div class="title">{{ $documentTitle ?? 'Social Welfare Record' }}</div>
        @if(isset($documentSubtitle))
        <div style="font-size: 10pt; color: #666; margin-top: -5px; margin-bottom: 10px;">{{ $documentSubtitle }}</div>
        @endif
        <div class="serial-number">Welfare Number: {{ $welfare->welfare_number }}</div>
        <div class="header-info">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Amount Box -->
    <div class="amount-box">
        <div class="amount-label">{{ ucfirst($welfare->type) }} Amount</div>
        <div class="amount-value">{{ number_format($welfare->amount, 2) }} TZS</div>
    </div>

    <!-- Welfare Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Welfare Number:</div>
            <div class="stats-cell"><strong style="font-family: 'Courier New', monospace; font-size: 11pt;">{{ $welfare->welfare_number }}</strong></div>
            <div class="stats-cell stats-label">Type:</div>
            <div class="stats-cell"><strong>{{ ucfirst($welfare->type) }}</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Status:</div>
            <div class="stats-cell">
                <span class="status-badge status-{{ $welfare->status }}">
                    {{ strtoupper($welfare->status) }}
                </span>
            </div>
            <div class="stats-cell stats-label">Transaction Date:</div>
            <div class="stats-cell"><strong>{{ $welfare->transaction_date->format('F d, Y') }}</strong></div>
        </div>
    </div>

    @if($welfare->benefit_type)
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Benefit Type:</div>
            <div class="stats-cell"><strong>{{ $welfare->benefit_type_name }}</strong></div>
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
                    <td><strong>{{ strtoupper($welfare->user->name) }}</strong></td>
                </tr>
                <tr>
                    <td>Membership Code</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $welfare->user->membership_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td>{{ $welfare->user->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $welfare->user->phone ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Welfare Record Details -->
    <div class="section">
        <div class="section-header">Welfare Record Details</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Welfare Number</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $welfare->welfare_number }}</td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td><strong>{{ ucfirst($welfare->type) }}</strong></td>
                </tr>
                @if($welfare->benefit_type)
                <tr>
                    <td>Benefit Type</td>
                    <td><strong>{{ $welfare->benefit_type_name }}</strong></td>
                </tr>
                @endif
                <tr>
                    <td>Amount</td>
                    <td><strong style="font-size: 11pt; color: #015425;">{{ number_format($welfare->amount, 2) }} TZS</strong></td>
                </tr>
                <tr>
                    <td>Transaction Date</td>
                    <td><strong>{{ $welfare->transaction_date->format('F d, Y') }}</strong></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <span class="status-badge status-{{ $welfare->status }}">
                            {{ strtoupper($welfare->status) }}
                        </span>
                    </td>
                </tr>
                @if($welfare->approval_date)
                <tr>
                    <td>Approval Date</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $welfare->approval_date->format('F d, Y') }}</td>
                </tr>
                @endif
                @if($welfare->disbursement_date)
                <tr>
                    <td>Disbursement Date</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $welfare->disbursement_date->format('F d, Y') }}</td>
                </tr>
                @endif
                @if($welfare->description)
                <tr>
                    <td>Description</td>
                    <td>{{ $welfare->description }}</td>
                </tr>
                @endif
                @if($welfare->eligibility_notes)
                <tr>
                    <td>Eligibility Notes</td>
                    <td>{{ $welfare->eligibility_notes }}</td>
                </tr>
                @endif
                @if($welfare->rejection_reason)
                <tr>
                    <td>Rejection Reason</td>
                    <td style="color: #721c24;">{{ $welfare->rejection_reason }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Approval Information -->
    @if($welfare->approver)
    <div class="section">
        <div class="section-header">Approval Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Approved By</td>
                    <td><strong>{{ $welfare->approver->name }}</strong></td>
                </tr>
                @if($welfare->approval_date)
                <tr>
                    <td>Approval Date</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $welfare->approval_date->format('F d, Y H:i:s') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif

    <!-- Transaction History -->
    @if($welfare->transactions && $welfare->transactions->count() > 0)
    <div class="section">
        <div class="section-header">Transaction History</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Transaction #</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                @foreach($welfare->transactions as $transaction)
                <tr>
                    <td style="font-family: 'Courier New', monospace;">{{ $transaction->transaction_number }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                    <td><strong>{{ number_format($transaction->amount, 2) }} TZS</strong></td>
                    <td style="font-family: 'Courier New', monospace;">{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
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

    <div class="footer">
        <p><strong>{{ $organizationInfo['name'] }}</strong></p>
        <p>{{ $organizationInfo['address'] }}</p>
        <p>Email: {{ $organizationInfo['email'] }} | Phone: {{ $organizationInfo['phone'] }}</p>
        <p style="margin-top: 10px; font-size: 7pt; color: #999; font-style: italic;">
            This is a computer-generated document. Welfare Number: {{ $welfare->welfare_number }}
        </p>
        <p style="font-size: 7pt; color: #999;">
            Generated on {{ ($generatedAt ? \Carbon\Carbon::parse($generatedAt) : now())->format('F d, Y \a\t H:i:s') }}
        </p>
    </div>
</body>
</html>

