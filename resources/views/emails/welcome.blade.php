<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to FeedTan CMG</title>
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
        
        .credentials-container { text-align: center; margin: 25px 0; padding: 30px; background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); border: 2px dashed #4caf50; border-radius: 12px; }
        .credentials-label { font-size: 14px; font-weight: 600; color: #2d3748; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .credentials-box { background: white; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); }
        .credentials-item { margin: 15px 0; text-align: left; }
        .credentials-item-label { font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .credentials-item-value { font-size: 18px; font-weight: 700; color: #006400; font-family: 'Courier New', monospace; word-break: break-all; }
        
        .security-notice { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .security-notice h4 { margin-top: 0; font-size: 16px; color: #856404; font-weight: 600; display: flex; align-items: center; }
        .security-notice .icon { font-size: 20px; margin-right: 8px; }
        .security-notice ul { margin: 10px 0; padding-left: 20px; color: #856404; font-size: 14px; line-height: 1.8; }
        .security-notice li { margin-bottom: 8px; }
        
        .info-box { background-color: #e3f2fd; border-left: 5px solid #2196f3; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .info-box p { margin: 0; font-size: 14px; color: #1565c0; line-height: 1.6; }
        
        .button-container { text-align: center; margin: 30px 0; }
        .action-button { display: inline-block; padding: 12px 25px; background-color: #006400; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; }
        .action-button:hover { background-color: #2e7d32; }
        
        .signature { margin-top: 40px; font-size: 14px; color: #4a5568; }
        .signature p { margin: 5px 0; }
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
            <p class="greeting">Karibu {{ $name }},</p>
            <p style="font-size: 14px; color: #4a5568;">Tunapenda kukukaribisha rasmi kwenye {{ $organizationInfo['name'] ?? 'FeedTan Community Microfinance Group' }}! Akaunti yako imeundwa kwa mafanikio na sasa unaweza kufikia huduma zote za kifedha zetu.</p>
            
            <div class="card">
                <div class="card-header">
                    <span class="icon">🎉</span>
                    <h4>Karibu kwenye Familia ya FeedTan!</h4>
                </div>
                
                <p style="font-size: 14px; color: #4a5568; margin-bottom: 20px;">
                    Akaunti yako imeundwa kwa mafanikio. Tafadhali zingatia maelezo yako ya kuingia hapa chini:
                </p>
                
                <div class="credentials-container">
                    <div class="credentials-label">Maelezo Yako ya Kuingia</div>
                    <div class="credentials-box">
                        <div class="credentials-item">
                            <div class="credentials-item-label">Barua Pepe (Email)</div>
                            <div class="credentials-item-value">{{ $email }}</div>
                        </div>
                        <div class="credentials-item">
                            <div class="credentials-item-label">Nenosiri (Password)</div>
                            <div class="credentials-item-value">{{ $password }}</div>
                        </div>
                    </div>
                    <p style="font-size: 12px; color: #4a5568; margin-top: 15px; font-style: italic;">
                        ⚠️ Tafadhali zingatia maelezo haya kwa usalama na usishirikishe na mtu yeyote.
                    </p>
                </div>
            </div>

            <div class="security-notice">
                <h4>
                    <span class="icon">🔒</span>
                    TAARIFA MUHIMU YA USALAMA
                </h4>
                <ul>
                    <li><strong>Usishirikishe</strong> maelezo yako ya kuingia na mtu yeyote</li>
                    <li><strong>Badilisha nenosiri lako</strong> mara tu baada ya kuingia kwa mara ya kwanza ikiwa inawezekana</li>
                    <li><strong>Wafanyakazi wetu</strong> hawatawahi kuomba nenosiri lako</li>
                    <li><strong>Ikiwa una shaka</strong> kuhusu usalama wa akaunti yako, wasiliana nasi mara moja</li>
                </ul>
            </div>

            <div class="info-box">
                <p>
                    <strong>💡 Kidokezo:</strong> Baada ya kuingia kwa mara ya kwanza, utaongozwa kwenye fomu ya maombi ya uanachama ili uweze kukamilisha usajili wako.
                </p>
            </div>

            <div class="button-container">
                <a href="{{ $loginUrl }}" class="action-button">Ingia kwenye Akaunti Yako</a>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">Tunafurahi kuwa na wewe kama sehemu ya familia yetu. Kama una maswali yoyote, tafadhali usisite kuwasiliana nasi.</p>
            
            <div class="signature">
                <p>Wapendwa,<br><strong>Timu ya FeedTan CMG</strong></p>
                <p style="font-weight: 600; color: #006400;">Let's Grow Together! 🤝</p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG - Welcome Email
        </div>
    </div>
</body>
</html>


