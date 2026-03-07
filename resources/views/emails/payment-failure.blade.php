<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Failed</title>
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
            background: #dc3545;
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
        .payment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            padding: 15px;
            background: #f8d7da;
            border-radius: 8px;
            margin: 15px 0;
        }
        .status {
            color: #dc3545;
            font-weight: bold;
        }
        .action-required {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }
        .action-required p {
            margin: 0;
            color: #856404;
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
        <p>Payment Failed</p>
    </div>
    
    <div class="content">
        <h2>Payment Failed</h2>
        
        <div class="payment-details">
            <p><strong>Reference:</strong> {{ $reference }}</p>
            <p><strong>Amount:</strong> TSh {{ $amount }}</p>
            <p><strong>Status:</strong> <span class="status">FAILED</span></p>
            <p><strong>Reason:</strong> {{ $failureReason }}</p>
        </div>
        
        <div class="amount">
            TSh {{ $amount }}
        </div>
        
        <div class="action-required">
            <p><strong>Action Required:</strong> Please check your payment details and try again, or contact support if the issue persists.</p>
        </div>
        
        <p>If you believe this is an error, please contact our support team for assistance.</p>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
