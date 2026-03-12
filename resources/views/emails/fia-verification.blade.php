<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FIA Payment Verification Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #015425;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #015425;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #015425;
            text-align: center;
            padding: 15px;
            background: #e8f5e8;
            border-radius: 8px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }
        .brand-name {
            font-weight: bold;
            color: #015425;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Feedtan CMG</h1>
        <p>FIA Payment Verification Received</p>
    </div>
    
    <div class="content">
        <h2>Payment Verification Details</h2>
        
        <div class="details">
            <p><strong>Reference:</strong> {{ $verification->payment_reference }}</p>
            <p><strong>Amount:</strong> TSh {{ $amount_formatted }}</p>
            <p><strong>Payment Date:</strong> {{ $payment_date_formatted }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($verification->payment_method) }}</p>
            <p><strong>Status:</strong> <span style="color: #f59e0b; font-weight: bold;">PENDING VERIFICATION</span></p>
            <p><strong>Submitted At:</strong> {{ $verification->submitted_at->format('d M Y, H:i') }}</p>
        </div>
        
        <div class="amount">
            TSh {{ $amount_formatted }}
        </div>
        
        <p>Thank you for submitting your FIA payment verification. Our team will review your submission and process the payment verification. You will receive notification once the verification is complete.</p>
        
        <p><strong>Next Steps:</strong></p>
        <ul>
            <li>Our team will verify your payment details</li>
            <li>You will receive SMS and email notification of the verification outcome</li>
            <li>If approved, your payment will be marked as verified in our system</li>
        </ul>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
