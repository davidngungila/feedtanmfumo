<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FIA Payment Verified Successfully</title>
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
            border-left: 4px solid #28a745;
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
        .status {
            color: #28a745;
            font-weight: bold;
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
        <p>FIA Payment Verified Successfully</p>
    </div>
    
    <div class="content">
        <h2>Payment Verification Completed</h2>
        
        <div class="details">
            <p><strong>Reference:</strong> {{ $verification->payment_reference }}</p>
            <p><strong>Amount:</strong> TSh {{ $amount_formatted }}</p>
            <p><strong>Payment Date:</strong> {{ $payment_date_formatted }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($verification->payment_method) }}</p>
            <p><strong>Status:</strong> <span class="status">VERIFIED</span></p>
            <p><strong>Verified At:</strong> {{ $verified_date_formatted }}</p>
        </div>
        
        <div class="amount">
            TSh {{ $amount_formatted }}
        </div>
        
        <p>Congratulations! Your FIA payment verification has been completed successfully. Your payment has been verified and recorded in our system.</p>
        
        <p><strong>Payment Details:</strong></p>
        <ul>
            <li>Payment reference: {{ $verification->payment_reference }}</li>
            <li>Amount: TSh {{ $amount_formatted }}</li>
            <li>Verification status: <span class="status">VERIFIED</span></li>
        </ul>
        
        <p>Thank you for using Feedtan CMG services. Your payment is now fully processed and verified.</p>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
