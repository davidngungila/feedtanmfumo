<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payout Completed Successfully</title>
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
        .payment-details {
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
        <p>Payout Completed Successfully</p>
    </div>
    
    <div class="content">
        <h2>Payout Confirmation</h2>
        
        <div class="payment-details">
            <p><strong>Reference:</strong> {{ $reference }}</p>
            <p><strong>Amount:</strong> TSh {{ $amount }}</p>
            <p><strong>Fees:</strong> TSh {{ $fees }}</p>
            <p><strong>Net Amount:</strong> TSh {{ $netAmount }}</p>
            <p><strong>Status:</strong> <span class="status">COMPLETED</span></p>
            <p><strong>Completed At:</strong> {{ $completedAt }}</p>
        </div>
        
        <div class="amount">
            TSh {{ $netAmount }}
        </div>
        
        <p>Your payout has been processed successfully and the funds have been transferred to your account.</p>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
