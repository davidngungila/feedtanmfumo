<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - FEEDTAN CMG</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 5px 0 0 0; opacity: 0.9; font-size: 14px; }
        .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .receipt-details { margin-bottom: 30px; }
        .detail-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { font-weight: 500; text-align: right; }
        .amount { font-size: 24px; font-weight: 700; color: #16a34a; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .footer p { margin: 0; }
        .powered-by { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; margin-top: 20px; }
        .powered-by strong { color: #16a34a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Receipt</h1>
            <p>FEEDTAN CMG - Secured Payment Gateway</p>
        </div>

        <div class="content">
            <div class="receipt-details">
                <div class="detail-row">
                    <span class="detail-label">Receipt Number:</span>
                    <span class="detail-value">{{ $receipt['receipt_number'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Reference:</span>
                    <span class="detail-value">{{ $receipt['payment_reference'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction Date:</span>
                    <span class="detail-value">{{ $receipt['transaction_date'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value">{{ $receipt['customer_name'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $receipt['customer_email'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $receipt['customer_phone'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ ucfirst($receipt['payment_method']) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value amount">{{ $receipt['currency'] }} {{ number_format($receipt['amount'], 0) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction Fee:</span>
                    <span class="detail-value">{{ $receipt['currency'] }} {{ number_format($receipt['fee'], 0) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value amount">{{ $receipt['currency'] }} {{ number_format($receipt['total_amount'], 0) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: {{ $receipt['status'] == 'completed' ? '#16a34a' : '#dc2626' }}; font-weight: 600;">
                        {{ ucfirst($receipt['status']) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="powered-by">
            <strong>{{ $receipt['powered_by'] }}</strong><br>
            <small>For support, contact: {{ $receipt['support_email'] }} | {{ $receipt['support_phone'] }}</small>
        </div>

        <div class="footer">
            <p>&copy; 2026 FEEDTAN CMG. All rights reserved.</p>
            <p>This is an automated receipt. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
