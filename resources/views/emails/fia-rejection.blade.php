<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FIA Payment Verification Rejected</title>
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
        .details {
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
        <p>FIA Payment Verification Rejected</p>
    </div>
    
    <div class="content">
        <h2>Payment Verification Rejected</h2>
        
        <div class="details">
            <p><strong>Reference:</strong> {{ $verification->payment_reference }}</p>
            <p><strong>Amount:</strong> TSh {{ $amount_formatted }}</p>
            <p><strong>Payment Date:</strong> {{ $payment_date_formatted }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($verification->payment_method) }}</p>
            <p><strong>Status:</strong> <span class="status">REJECTED</span></p>
            @if($verification->verification_notes)
            <p><strong>Reason:</strong> {{ $verification->verification_notes }}</p>
            @endif
        </div>
        
        <div class="amount">
            TSh {{ $amount_formatted }}
        </div>
        
        <div class="action-required">
            <p><strong>Action Required:</strong> Your FIA payment verification has been rejected. Please review the reason below and contact our support team for assistance.</p>
            @if($verification->verification_notes)
            <p><strong>Rejection Reason:</strong> {{ $verification->verification_notes }}</p>
            @endif
        </div>
        
        <p>We apologize for the inconvenience. Please contact our support team if you believe this is an error or if you need assistance with resubmitting your verification.</p>
        
        <p><strong>Contact Information:</strong></p>
        <ul>
            <li>Email: support@feedtancmg.com</li>
            <li>Phone: +255 712 345 678</li>
        </ul>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
