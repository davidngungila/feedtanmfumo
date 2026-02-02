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
                size: 80mm auto; /* Standard receipt printer */
            }
        }
        @page {
            margin: 0;
            size: 80mm auto; /* Default to 80mm, can be changed to 58mm */
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', 'Courier New', monospace;
            margin: 0;
            padding: 8px 4px;
            background: #ffffff;
            color: #000000;
            line-height: 1.3;
            font-size: 11px;
            max-width: 80mm; /* Standard receipt width */
            margin: 0 auto;
        }
        .receipt-container {
            width: 100%;
            max-width: 80mm;
            background: white;
            padding: 0 3px;
        }
        .receipt-header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px dashed #16a34a;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
        }
        .logo-container {
            margin-bottom: 8px;
            width: 100%;
            padding: 0;
        }
        .logo-container img {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
        .receipt-header h1 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #16a34a;
        }
        .receipt-header p {
            font-size: 9px;
            margin: 2px 0;
            color: #2563eb;
        }
        .receipt-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
            text-transform: uppercase;
            padding: 5px 0;
            border-top: 2px solid #16a34a;
            border-bottom: 2px solid #16a34a;
            background: linear-gradient(135deg, #16a34a 0%, #2563eb 100%);
            color: white;
        }
        .welfare-number {
            text-align: center;
            padding: 8px;
            background: #16a34a;
            color: #fff;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 10px 0;
            border: 2px solid #16a34a;
            border-radius: 6px;
        }
        .receipt-section {
            margin: 8px 0;
            padding: 5px 0;
        }
        .section-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 2px;
            color: #16a34a;
        }
        .receipt-line {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 10px;
            border-bottom: 1px dotted #ddd;
        }
        .receipt-line:last-child {
            border-bottom: none;
        }
        .line-label {
            font-weight: 600;
            flex-shrink: 0;
            width: 35%;
            text-align: left;
            color: #2563eb;
        }
        .line-value {
            flex: 1;
            text-align: right;
            word-break: break-word;
            color: #111827;
        }
        .amount-box {
            text-align: center;
            padding: 12px;
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
            border: 2px solid #16a34a;
            border-radius: 6px;
            margin: 12px 0;
        }
        .amount-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #16a34a;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 18px;
            font-weight: bold;
            color: #16a34a;
            font-family: 'Courier New', monospace;
        }
        .receipt-block {
            margin: 6px 0;
            padding: 5px;
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
            border: 1px solid #16a34a;
            border-radius: 4px;
        }
        .block-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
            color: #16a34a;
        }
        .block-value {
            font-size: 10px;
            word-break: break-word;
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
        }
        .status-approved, .status-completed, .status-disbursed {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
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
            margin: 8px 0;
            padding: 5px 0;
            border-top: 2px dashed #16a34a;
            border-bottom: 2px dashed #16a34a;
            color: #16a34a;
            font-weight: bold;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #16a34a;
            font-size: 8px;
            color: #6b7280;
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
        }
        .receipt-footer p {
            margin: 3px 0;
            line-height: 1.4;
        }
        .barcode {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 8px;
            margin: 8px 0;
            border: 2px solid #16a34a;
            border-radius: 6px;
            background: white;
            color: #16a34a;
        }
        .qr-code-section {
            text-align: center;
            margin: 10px 0;
            padding: 8px;
            background: white;
            border: 2px solid #16a34a;
            border-radius: 6px;
        }
        .qr-code-label {
            font-size: 8px;
            font-weight: bold;
            color: #16a34a;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .qr-code {
            display: inline-block;
            width: 80px;
            height: 80px;
            border: 2px solid #16a34a;
            border-radius: 4px;
            padding: 3px;
            background: white;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #16a34a 0%, #2563eb 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: none;
            font-size: 14px;
        }
        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 20px -3px rgba(0, 0, 0, 0.2);
        }
        .compact-line {
            font-size: 9px;
            padding: 2px 0;
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
            <div class="qr-code-label">Scan QR Code for Verification</div>
            <div class="qr-code">
                <img src="{{ $qrCodeUrl }}" alt="QR Code">
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

    <button onclick="window.print()" class="print-button no-print">Print Receipt</button>

    <script>
        // Auto-print option (can be enabled)
        // window.onload = function() { 
        //     setTimeout(function() { window.print(); }, 500);
        // }
    </script>
</body>
</html>
