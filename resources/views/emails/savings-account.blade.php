<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $eventTitle ?? 'Savings Account Update' }} - FeedTan CMG</title>
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
        .card-header .icon { font-size: 24px; margin-right: 12px; color: #4CAF50; }
        .card-header h4 { margin: 0; font-size: 16px; font-weight: 600; color: #2d3748; }
        .details-table { width: 100%; margin: 15px 0; }
        .details-table td { padding: 8px 0; font-size: 14px; color: #4a5568; }
        .details-table td:first-child { font-weight: 600; color: #2d3748; width: 40%; }
        .button-container { text-align: center; margin: 30px 0; }
        .action-button { display: inline-block; padding: 12px 25px; background-color: #006400; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; }
        .action-button:hover { background-color: #2e7d32; }
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
            <p class="greeting">Habari {{ $name }},</p>
            <p style="font-size: 14px; color: #4a5568;">{{ $mainMessage }}</p>
            
            <div class="card">
                <div class="card-header">
                    <span class="icon">{{ $icon ?? '💰' }}</span>
                    <h4>{{ $cardTitle ?? 'Akaunti ya Akiba' }}</h4>
                </div>
                <table class="details-table">
                    @foreach($details as $label => $value)
                    <tr>
                        <td>{{ $label }}:</td>
                        <td><strong>{{ $value }}</strong></td>
                    </tr>
                    @endforeach
                </table>
                <div class="button-container">
                    <a href="{{ url('/member/savings') }}" class="action-button">Angalia Akaunti Yako</a>
                </div>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">Endelea kuweka akiba kwa mwendo mzuri!</p>
            
            <div class="signature">
                <p>Wapendwa,<br><strong>Timu ya FeedTan CMG</strong></p>
                <p style="font-weight: 600; color: #006400;">Let's Grow Together! 🤝</p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG Savings Management System
        </div>
    </div>
</body>
</html>


