<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP Code - FeedTan CMG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; }
        .email-container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; }
        .header { background: #006400; padding: 30px 25px; text-align: center; color: white; }
        .header .title { font-size: 26px; font-weight: 700; margin-bottom: 5px; }
        .header .sub-title { font-size: 14px; opacity: 0.9; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 15px; }
        .card { background-color: #f7fafc; border: 1px solid #edf2f7; border-radius: 8px; padding: 20px; margin-bottom: 25px; text-align: center; }
        .otp-code { font-size: 48px; font-weight: 700; color: #006400; letter-spacing: 8px; margin: 20px 0; padding: 20px; background: white; border: 2px dashed #006400; border-radius: 8px; }
        .security-notice { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .security-notice h4 { margin-top: 0; font-size: 16px; color: #856404; font-weight: 600; }
        .security-notice ul { margin: 10px 0; padding-left: 20px; color: #856404; font-size: 14px; }
        .signature { margin-top: 40px; font-size: 14px; color: #4a5568; }
        .footer { background-color: #006400; color: white; text-align: center; padding: 15px; font-size: 12px; letter-spacing: 0.5px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="title">FeedTan Community Microfinance Group</div>
            <div class="sub-title">{{ $organizationInfo['po_box'] ?? 'P.O.Box 7744' }}, {{ $organizationInfo['address'] ?? 'Ushirika Sokoine Road' }}, {{ $organizationInfo['city'] ?? 'Moshi' }}, {{ $organizationInfo['region'] ?? 'Kilimanjaro' }}, {{ $organizationInfo['country'] ?? 'Tanzania' }}</div>
        </div>
        <div class="content">
            <p class="greeting">Dear {{ $name }},</p>
            <p style="font-size: 14px; color: #4a5568;">You have requested to login to your account. Please use the following OTP code to complete your login:</p>
            
            <div class="card">
                <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #2d3748;">YOUR OTP CODE</h4>
                <div class="otp-code">{{ $otpCode }}</div>
                <p style="font-size: 14px; color: #4a5568; margin: 0;">This code will expire in 10 minutes.</p>
            </div>

            <div class="security-notice">
                <h4>🔒 IMPORTANT SECURITY NOTICE</h4>
                <ul>
                    <li>Do not share this code with anyone</li>
                    <li>Our staff will never ask for your OTP code</li>
                    <li>If you did not request this code, please ignore this email or contact us immediately</li>
                </ul>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">This is an automated security email from {{ $organizationInfo['name'] ?? 'FeedTan Community Microfinance Group' }}.</p>
            
            <div class="signature">
                <p>Best regards,<br><strong>Timu ya FeedTan CMG</strong></p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG Security System
        </div>
    </div>
</body>
</html>

