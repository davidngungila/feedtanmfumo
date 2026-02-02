<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Application Submitted - FeedTan CMG</title>
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
        
        .success-box { background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); border: 2px solid #4caf50; border-radius: 12px; padding: 30px; margin: 25px 0; text-align: center; }
        .success-icon { font-size: 48px; margin-bottom: 15px; }
        .success-title { font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 10px; }
        .success-message { font-size: 14px; color: #4a5568; line-height: 1.8; }
        
        .info-box { background-color: #e3f2fd; border-left: 5px solid #2196f3; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .info-box p { margin: 0; font-size: 14px; color: #1565c0; line-height: 1.6; }
        
        .waiting-box { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .waiting-box h4 { margin-top: 0; font-size: 16px; color: #856404; font-weight: 600; display: flex; align-items: center; }
        .waiting-box .icon { font-size: 20px; margin-right: 8px; }
        .waiting-box ul { margin: 10px 0; padding-left: 20px; color: #856404; font-size: 14px; line-height: 1.8; }
        .waiting-box li { margin-bottom: 8px; }
        
        .membership-code-box { background: white; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        .membership-code-label { font-size: 12px; font-weight: 600; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .membership-code-value { font-size: 24px; font-weight: 700; color: #006400; font-family: 'Courier New', monospace; }
        
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
            <p class="greeting">Habari {{ $name }},</p>
            <p style="font-size: 14px; color: #4a5568;">Asante sana kwa kuwasilisha maombi yako ya uanachama kwenye {{ $organizationInfo['name'] ?? 'FeedTan Community Microfinance Group' }}. Tumepokea maombi yako kwa mafanikio na tunafanya kazi kuyakagua.</p>
            
            <div class="success-box">
                <div class="success-icon">✅</div>
                <div class="success-title">Maombi Yako Yamepokelewa!</div>
                <div class="success-message">
                    Maombi yako ya uanachama yamepokelewa kwa mafanikio na yamehifadhiwa katika mfumo wetu. Tunaendelea kukagua taarifa zote na tutakujulisha matokeo hivi karibuni.
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="icon">📋</span>
                    <h4>Taarifa za Maombi Yako</h4>
                </div>
                
                <div class="membership-code-box">
                    <div class="membership-code-label">Nambari ya Uanachama</div>
                    <div class="membership-code-value">{{ $membershipCode }}</div>
                </div>
                
                <p style="font-size: 14px; color: #4a5568; margin-top: 20px;">
                    Tafadhali zingatia nambari hii ya uanachama kwa rekodi zako. Itakusaidia kufuatilia maombi yako na kujua hali yake.
                </p>
            </div>

            <div class="waiting-box">
                <h4>
                    <span class="icon">⏳</span>
                    NINI KINACHOFUATIA?
                </h4>
                <ul>
                    <li><strong>Ukaguzi wa Taarifa:</strong> Tunaendelea kukagua taarifa zote ulizoziwasilisha ili kuhakikisha zote ni sahihi na kamili</li>
                    <li><strong>Uthibitishaji:</strong> Tutathibitisha taarifa zako na kukusanya nyaraka zote zinazohitajika</li>
                    <li><strong>Idhini:</strong> Baada ya ukaguzi, tutakujulisha matokeo ya maombi yako</li>
                    <li><strong>Ujumbe wa Kuthibitisha:</strong> Utapokea ujumbe wa barua pepe na SMS mara tu baada ya idhini yako kupitishwa</li>
                </ul>
            </div>

            <div class="info-box">
                <p>
                    <strong>💡 Kidokezo:</strong> Unaweza kufuatilia hali ya maombi yako kwa kuingia kwenye dashboard yako. Tunaendelea kukusanya taarifa za ziada ikiwa ni lazima na tutakujulisha mara tu.
                </p>
            </div>

            <div class="button-container">
                <a href="{{ $dashboardUrl }}" class="action-button">Angalia Dashboard Yako</a>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">Tunashukuru kwa kuwa na subira wakati tunakagua maombi yako. Kama una maswali yoyote, tafadhali usisite kuwasiliana nasi.</p>
            
            <div class="signature">
                <p>Wapendwa,<br><strong>Timu ya FeedTan CMG</strong></p>
                <p style="font-weight: 600; color: #006400;">Let's Grow Together! 🤝</p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG - Membership Application Submission Confirmation
        </div>
    </div>
</body>
</html>


