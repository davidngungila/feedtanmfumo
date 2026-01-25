<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder - FeedTan CMG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; }
        .email-container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; }
        .header { background: #006400; padding: 30px 25px; text-align: center; color: white; }
        .header .title { font-size: 26px; font-weight: 700; margin-bottom: 5px; }
        .header .sub-title { font-size: 14px; opacity: 0.9; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 15px; }
        .card { background-color: #f7fafc; border: 1px solid #edf2f7; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .card-header { display: flex; align-items: center; margin-bottom: 15px; }
        .card-header .icon { font-size: 24px; margin-right: 12px; color: #ff9800; }
        .card-header h4 { margin: 0; font-size: 16px; font-weight: 600; color: #2d3748; }
        .details-table { width: 100%; margin: 15px 0; }
        .details-table td { padding: 8px 0; font-size: 14px; color: #4a5568; }
        .details-table td:first-child { font-weight: 600; color: #2d3748; width: 40%; }
        .button-container { text-align: center; margin: 30px 0; }
        .action-button { display: inline-block; padding: 12px 25px; background-color: #006400; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; }
        .action-button:hover { background-color: #2e7d32; }
        .warning-box { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .warning-box h4 { margin-top: 0; font-size: 16px; color: #856404; font-weight: 600; }
        .signature { margin-top: 40px; font-size: 14px; color: #4a5568; }
        .footer { background-color: #006400; color: white; text-align: center; padding: 15px; font-size: 12px; letter-spacing: 0.5px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="title">FeedTan Community Microfinance Group</div>
            <div class="sub-title">{{ $organizationInfo['po_box'] ?? 'P.O.Box 7744' }}, {{ $organizationInfo['address'] ?? 'Ushirika Sokoine Road' }, {{ $organizationInfo['city'] ?? 'Moshi' }}, {{ $organizationInfo['region'] ?? 'Kilimanjaro' }}, {{ $organizationInfo['country'] ?? 'Tanzania' }}</div>
        </div>
        <div class="content">
            <p class="greeting">Habari {{ $name }},</p>
            <p style="font-size: 14px; color: #4a5568;">Kumbuka malipo yako ya mkopo yanayostahili hivi karibuni.</p>
            
            <div class="card">
                <div class="card-header">
                    <span class="icon">⏰</span>
                    <h4>Malipo Yanayostahili</h4>
                </div>
                <table class="details-table">
                    <tr>
                        <td>Kiasi cha Malipo:</td>
                        <td><strong>{{ number_format($paymentDetails['amount'] ?? 0, 0) }} TZS</strong></td>
                    </tr>
                    <tr>
                        <td>Tarehe ya Malipo:</td>
                        <td><strong>{{ isset($paymentDetails['due_date']) ? date('d/m/Y', strtotime($paymentDetails['due_date'])) : 'N/A' }}</strong></td>
                    </tr>
                    @if(isset($paymentDetails['days_overdue']) && $paymentDetails['days_overdue'] > 0)
                    <tr>
                        <td>Siku za Kuchelewa:</td>
                        <td><strong style="color: #dc3545;">{{ $paymentDetails['days_overdue'] }} siku</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <td>Namba ya Mkopo:</td>
                        <td>{{ $paymentDetails['loan_number'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            @if(isset($paymentDetails['days_overdue']) && $paymentDetails['days_overdue'] > 0)
            <div class="warning-box">
                <h4>⚠️ Malipo Yamechelewa</h4>
                <p style="font-size: 14px; color: #856404; margin: 0;">Tafadhali fanya malipo haraka iwezekanavyo ili kuepuka ada za kuchelewa na kudumisha hali nzuri ya mkopo wako.</p>
            </div>
            @endif

            <div class="card" style="background-color: #e8f5e9; border-color: #4caf50;">
                <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #2d3748;">Njia za Malipo:</h4>
                <ul style="font-size: 14px; color: #4a5568; line-height: 1.8; margin: 0; padding-left: 20px;">
                    <li>Fika ofisi yetu wakati wa masaa ya kazi</li>
                    <li>Malipo ya simu (M-Pesa, Tigo Pesa, Airtel Money)</li>
                    <li>Uhamisho wa benki kwenye akaunti yetu rasmi</li>
                </ul>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">Tafadhali fanya malipo yako kabla ya tarehe iliyoelezwa ili kuepuka ada za kuchelewa.</p>
            
            <div class="signature">
                <p>Wapendwa,<br><strong>Timu ya FeedTan CMG</strong></p>
                <p style="font-weight: 600; color: #006400;">Let's Grow Together! 🤝</p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG Loan Management System
        </div>
    </div>
</body>
</html>


