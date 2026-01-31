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
            margin: 0 !important;
            padding: 0 !important;
            size: 164.41pt auto; /* 58mm width (164.41 points), auto height */
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 164.41pt; /* Exactly 58mm in points */
            max-width: 164.41pt;
            margin: 0 !important;
            padding: 0 !important;
        }
        body {
            font-family: 'Arial', 'Courier New', monospace;
            background: #ffffff;
            color: #000000;
            line-height: 1.2;
            font-size: 9px;
            padding: 8px 4px;
        }
        .receipt-container {
            width: 100%;
            max-width: 100%;
            background: white;
            padding: 0 3px;
        }
        .receipt-header {
            text-align: center;
            padding: 0;
            border-bottom: 1px dashed #015425;
            margin-bottom: 3px;
            margin-left: -3px;
            margin-right: -3px;
            background: white;
        }
        .logo-container {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .logo-container img {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }
        .receipt-title {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
            padding: 2px 0;
            border-top: 1px solid #015425;
            border-bottom: 1px solid #015425;
            background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
            color: white;
        }
        .welfare-number {
            text-align: center;
            padding: 4px;
            background: #015425;
            color: #fff;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 3px 0;
            border: 1px solid #015425;
            border-radius: 4px;
        }
        .receipt-section {
            margin: 3px 0;
            padding: 2px 0;
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
            padding: 1px 0;
            font-size: 8px;
            border-bottom: 1px dotted #ddd;
            margin-bottom: 1px;
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
            padding: 6px;
            background: linear-gradient(135deg, #f0f9f4 0%, #e6f7f0 100%);
            border: 1px solid #015425;
            border-radius: 4px;
            margin: 4px 0;
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
            margin: 2px 0;
            padding: 2px;
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
            margin: 3px 0;
            padding: 2px 0;
            border-top: 1px dashed #015425;
            border-bottom: 1px dashed #015425;
            color: #015425;
            font-weight: bold;
            font-size: 7px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 4px;
            padding-top: 4px;
            padding-bottom: 15px;
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
            padding: 4px;
            margin: 3px 0;
            border: 1px solid #015425;
            border-radius: 4px;
            background: white;
            color: #015425;
        }
        .qr-code-section {
            text-align: center;
            margin: 4px 0;
            padding: 3px 0;
        }
        .qr-code-container {
            display: inline-block;
            padding: 3px;
            background: white;
            border: 1px solid #015425;
            border-radius: 3px;
        }
        .qr-code-container img {
            width: 60px;
            height: 60px;
            display: block;
        }
        .qr-code-label {
            font-size: 7px;
            color: #015425;
            margin-top: 2px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            @if(isset($headerBase64) && $headerBase64)
            <div class="logo-container">
                <img src="{{ $headerBase64 }}" alt="FeedTan CMG Header">
            </div>
            @else
            <div class="logo-container">
                <img src="{{ asset('header-mfumo.png') }}" alt="FeedTan CMG Header">
            </div>
            @endif
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

        <!-- QR Code -->
        @if(isset($qrCodeUrl) && $qrCodeUrl)
        <div class="qr-code-section">
            <div class="qr-code-container">
                <img src="{{ $qrCodeUrl }}" alt="QR Code">
                <div class="qr-code-label">SCAN TO VERIFY</div>
            </div>
        </div>
        @endif

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
