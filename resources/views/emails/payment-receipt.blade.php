<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt - {{ $payment->reference }}</title>
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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #015425;
            margin-bottom: 8px;
            border-bottom: 1px solid #015425;
            padding-bottom: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #015425;
            margin-bottom: 2px;
        }
        .info-value {
            color: #333;
        }
        .payment-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .amount {
            font-size: 16pt;
            font-weight: bold;
            color: #015425;
            text-align: center;
            padding: 15px;
            background: #e8f5e8;
            border-radius: 8px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        .brand-name {
            font-size: 10pt;
            font-weight: bold;
            color: #015425;
        }
        .powered-by {
            font-size: 8pt;
            color: #999;
            margin-top: 10px;
        }
        @if ($isReceipt ?? false)
        .receipt-badge {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10pt;
            display: inline-block;
            margin-bottom: 10px;
        }
        @endif
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-box">FC</div>
        <div class="header-info">
            <strong>FEEDTAN CMG</strong><br>
            <span class="brand-name">Feedtan Community Microfinance Group</span><br>
            <span>P.O. Box 1234, Dar es Salaam, Tanzania</span><br>
            <span>Email: info@feedtancmg.com | Phone: +255 712 345 678</span>
        </div>
    </div>

    @if ($isReceipt ?? false)
    <div class="receipt-badge">PAYMENT RECEIPT</div>
    @else
    <div class="title">Membership Application - {{ $user->membership_code ?? $user->id }}</div>
    @endif

    <div class="serial-number">
        Receipt #: {{ $payment->reference ?? 'PAY' . date('YmdHis') }}
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $customerName ?? $user->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $customerEmail ?? $user->email }}</div>
            </div>
        </div>
        @if ($phoneNumber ?? $user->phone)
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $phoneNumber ?? $user->phone }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Reference Code:</div>
                <div class="info-value">{{ $payment->reference ?? $user->membership_code }}</div>
            </div>
        </div>
        @endif
    </div>

    @if ($payment ?? isset($payment))
    <div class="section">
        <div class="section-title">Payment Details</div>
        <div class="payment-details">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Payment Type:</div>
                    <div class="info-value">{{ strtoupper($payment->payment_type ?? 'MOBILE MONEY') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">{{ strtoupper($payment->status ?? 'COMPLETED') }}</div>
                </div>
            </div>
            @if ($payment->paid_at ?? $payment->paid_at)
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Payment Date:</div>
                    <div class="info-value">{{ $payment->paid_at ?? now()->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Transaction ID:</div>
                    <div class="info-value">{{ $payment->reference ?? uniqid() }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if ($payment ?? isset($payment))
    <div class="amount">
        TSh {{ number_format($payment->amount ?? $amount ?? 0) }}
    </div>
    @endif

    <div class="footer">
        <div class="brand-name">FEEDTAN CMG</div>
        <div>Feedtan Community Microfinance Group</div>
        <div>Thank you for your payment!</div>
        <div class="powered-by">Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
    </div>
</body>
</html>
