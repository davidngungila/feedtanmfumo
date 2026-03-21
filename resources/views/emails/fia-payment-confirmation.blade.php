<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FIA Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #015425;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-top: none;
        }
        .footer {
            background-color: #e9ecef;
            padding: 20px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            color: #666;
        }
        .amount {
            font-weight: bold;
            color: #015425;
            font-size: 18px;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #015425;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #015425;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FIA Payment Confirmation</h1>
        <p>Official Payment Receipt</p>
    </div>

    <div class="content">
        <p>Dear {{ $confirmation->member_name }},</p>
        
        <p>Thank you for submitting your FIA payment details. This email serves as confirmation that your payment information has been received and recorded in our system.</p>

        <div class="info-box">
            <h3>Payment Summary</h3>
            <p><strong>Member ID:</strong> {{ $confirmation->member_id }}</p>
            <p><strong>Member Name:</strong> {{ $confirmation->member_name }}</p>
            <p><strong>Total Amount:</strong> <span class="amount">{{ number_format($confirmation->amount_to_pay, 2) }} TZS</span></p>
            <p><strong>Submission Date:</strong> {{ \Carbon\Carbon::parse($confirmation->created_at)->format('d M Y, H:i') }}</p>
            <p><strong>Confirmation ID:</strong> #{{ str_pad($confirmation->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        @if($paymentRecord)
        <div class="info-box">
            <h3>Payment Breakdown</h3>
            <p><strong>Gawio la FIA:</strong> {{ number_format($paymentRecord->gawio_la_fia, 2) }} TZS</p>
            <p><strong>FIA Ilivyo Koma:</strong> {{ number_format($paymentRecord->fia_iliyokomaa, 2) }} TZS</p>
            <p><strong>Jumla:</strong> {{ number_format($paymentRecord->jumla, 2) }} TZS</p>
        </div>
        @endif

        @if(!empty($confirmation->notes))
        <div class="info-box">
            <h3>Additional Notes</h3>
            <p>{{ $confirmation->notes }}</p>
        </div>
        @endif

        <p>Your official payment confirmation PDF is attached to this email. Please keep it for your records.</p>

        <p>If you have any questions or need further assistance, please don't hesitate to contact our office.</p>
    </div>

    <div class="footer">
        <p>This is an automated message from the FIA Payment System.</p>
        <p>© {{ date('Y') }} FIA Administration. All rights reserved.</p>
        <p>Generated on: {{ $generated_at->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
