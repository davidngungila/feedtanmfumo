<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welfare Receipt - {{ $welfare->welfare_number }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page {
                margin: 0;
                size: 58mm auto; /* 58mm receipt printer */
            }
        }
        @page {
            margin: 0;
            size: 58mm auto; /* 58mm receipt width - exact size */
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', 'Courier New', monospace;
            margin: 0;
            padding: 8px;
            background: #ffffff;
            color: #000000;
            line-height: 1.2;
            font-size: 9px;
            max-width: 58mm; /* 58mm receipt width - exact size */
            margin: 0 auto;
        }
        .receipt-container {
            width: 100%;
            max-width: 58mm;
            background: white;
        }
        .receipt-header {
            text-align: center;
            padding: 6px 0;
            border-bottom: 1px dashed #015425;
            margin-bottom: 6px;
            background: linear-gradient(135deg, #f0f9f4 0%, #e6f7f0 100%);
        }
        .logo-container {
            margin-bottom: 4px;
        }
        .logo-container img {
            max-width: 60px;
            height: auto;
            background: white;
            padding: 3px;
            border-radius: 4px;
            display: block;
            margin: 0 auto;
        }
        .receipt-header h1 {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #015425;
        }
        .receipt-header p {
            font-size: 7px;
            margin: 1px 0;
            color: #027a3a;
        }
        .receipt-title {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
            padding: 3px 0;
            border-top: 1px solid #015425;
            border-bottom: 1px solid #015425;
            background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
            color: white;
        }
        .welfare-number {
            text-align: center;
            padding: 5px;
            background: #015425;
            color: #fff;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 6px 0;
            border: 1px solid #015425;
            border-radius: 4px;
        }
        .receipt-section {
            margin: 5px 0;
            padding: 3px 0;
        }
        .section-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
            border-bottom: 1px solid #015425;
            padding-bottom: 1px;
            color: #015425;
        }
        .receipt-line {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 8px;
            border-bottom: 1px dotted #ddd;
        }
        .receipt-line:last-child {
            border-bottom: none;
        }
        .line-label {
            font-weight: 600;
            flex-shrink: 0;
            width: 40%;
            text-align: left;
            color: #027a3a;
            font-size: 7px;
        }
        .line-value {
            flex: 1;
            text-align: right;
            word-break: break-word;
            color: #111827;
            font-size: 8px;
        }
        .amount-box {
            text-align: center;
            padding: 8px;
            background: linear-gradient(135deg, #f0f9f4 0%, #e6f7f0 100%);
            border: 1px solid #015425;
            border-radius: 4px;
            margin: 8px 0;
        }
        .amount-label {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            color: #015425;
            margin-bottom: 3px;
        }
        .amount-value {
            font-size: 14px;
            font-weight: bold;
            color: #015425;
            font-family: 'Courier New', monospace;
        }
        .receipt-block {
            margin: 4px 0;
            padding: 3px;
            background: linear-gradient(135deg, #f0f9f4 0%, #e6f7f0 100%);
            border: 1px solid #015425;
            border-radius: 3px;
        }
        .block-label {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            color: #015425;
        }
        .block-value {
            font-size: 8px;
            word-break: break-word;
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border: 1px solid #000;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
        }
        .status-approved, .status-completed, .status-disbursed {
            background: #015425;
            color: #fff;
            border-color: #015425;
        }
        .status-pending {
            background: #fbbf24;
            color: #000;
            border-color: #fbbf24;
        }
        .status-rejected {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }
        .receipt-divider {
            text-align: center;
            margin: 5px 0;
            padding: 3px 0;
            border-top: 1px dashed #015425;
            border-bottom: 1px dashed #015425;
            color: #015425;
            font-weight: bold;
            font-size: 7px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px dashed #015425;
            font-size: 6px;
            color: #6b7280;
            background: linear-gradient(135deg, #f0f9f4 0%, #e6f7f0 100%);
        }
        .receipt-footer p {
            margin: 2px 0;
            line-height: 1.3;
        }
        .barcode {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #015425;
            border-radius: 4px;
            background: white;
            color: #015425;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            @if(isset($headerBase64) && $headerBase64)
            <div class="logo-container">
                <img src="{{ $headerBase64 }}" alt="FeedTan CMG">
            </div>
            @endif
            <h1>FeedTan CMG</h1>
            <p>FeedTan Community Microfinance Group</p>
            <p>Welfare Receipt</p>
        </div>

        <!-- Welfare Number -->
        <div class="welfare-number">
            {{ $welfare->welfare_number }}
        </div>

        <!-- Amount Box -->
        <div class="amount-box">
            <div class="amount-label">{{ ucfirst($welfare->type) }} Amount</div>
            <div class="amount-value">{{ number_format($welfare->amount, 2) }} TZS</div>
        </div>

        <!-- Welfare Details -->
        <div class="receipt-section">
            <div class="section-label">WELFARE DETAILS</div>
            <div class="receipt-line">
                <span class="line-label">Type:</span>
                <span class="line-value">{{ ucfirst($welfare->type) }}</span>
            </div>
            @if($welfare->benefit_type)
            <div class="receipt-line">
                <span class="line-label">Benefit Type:</span>
                <span class="line-value">{{ $welfare->benefit_type_name }}</span>
            </div>
            @endif
            <div class="receipt-line">
                <span class="line-label">Status:</span>
                <span class="line-value">
                    <span class="status-badge status-{{ $welfare->status }}">{{ strtoupper($welfare->status) }}</span>
                </span>
            </div>
            <div class="receipt-line">
                <span class="line-label">Date:</span>
                <span class="line-value">{{ $welfare->transaction_date->format('M j, Y') }}</span>
            </div>
            @if($welfare->approval_date)
            <div class="receipt-line">
                <span class="line-label">Approved:</span>
                <span class="line-value">{{ $welfare->approval_date->format('M j, Y') }}</span>
            </div>
            @endif
            @if($welfare->disbursement_date)
            <div class="receipt-line">
                <span class="line-label">Disbursed:</span>
                <span class="line-value">{{ $welfare->disbursement_date->format('M j, Y') }}</span>
            </div>
            @endif
        </div>

        <div class="receipt-divider">--------------------------------</div>

        <!-- Member Information -->
        <div class="receipt-section">
            <div class="section-label">MEMBER INFO</div>
            <div class="receipt-line">
                <span class="line-label">Name:</span>
                <span class="line-value">{{ strtoupper($welfare->user->name) }}</span>
            </div>
            <div class="receipt-line">
                <span class="line-label">Member ID:</span>
                <span class="line-value" style="font-family: 'Courier New', monospace;">{{ $welfare->user->membership_code ?? 'N/A' }}</span>
            </div>
            <div class="receipt-line">
                <span class="line-label">Email:</span>
                <span class="line-value">{{ $welfare->user->email }}</span>
            </div>
            <div class="receipt-line">
                <span class="line-label">Phone:</span>
                <span class="line-value" style="font-family: 'Courier New', monospace;">{{ $welfare->user->phone ?? 'N/A' }}</span>
            </div>
        </div>

        @if($welfare->description)
        <div class="receipt-section">
            <div class="section-label">DESCRIPTION</div>
            <div class="receipt-block">
                <div class="block-value">{{ $welfare->description }}</div>
            </div>
        </div>
        @endif

        @if($welfare->eligibility_notes)
        <div class="receipt-section">
            <div class="section-label">ELIGIBILITY NOTES</div>
            <div class="receipt-block">
                <div class="block-value">{{ $welfare->eligibility_notes }}</div>
            </div>
        </div>
        @endif

        @if($welfare->rejection_reason)
        <div class="receipt-section">
            <div class="section-label">REJECTION REASON</div>
            <div class="receipt-block">
                <div class="block-value" style="color: #ef4444;">{{ $welfare->rejection_reason }}</div>
            </div>
        </div>
        @endif

        @if($welfare->approver)
        <div class="receipt-section">
            <div class="section-label">APPROVAL INFO</div>
            <div class="receipt-line">
                <span class="line-label">Approved By:</span>
                <span class="line-value">{{ $welfare->approver->name }}</span>
            </div>
        </div>
        @endif

        <!-- Barcode -->
        <div class="barcode">
            {{ $welfare->welfare_number }}
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>--------------------------------</p>
            <p><strong>KEEP THIS RECEIPT</strong></p>
            <p>{{ $organizationInfo['name'] }}</p>
            <p>{{ $organizationInfo['address'] }}</p>
            <p>Email: {{ $organizationInfo['email'] }}</p>
            <p>Phone: {{ $organizationInfo['phone'] }}</p>
            <p>Generated: {{ ($generatedAt ? \Carbon\Carbon::parse($generatedAt) : now())->format('M j, Y g:i A') }}</p>
            <p>Copyright {{ date('Y') }} FeedTan CMG</p>
            <p>--------------------------------</p>
        </div>
    </div>
</body>
</html>
