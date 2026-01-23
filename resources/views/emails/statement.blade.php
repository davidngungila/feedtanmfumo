<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Statement - FeedTan CMG</title>
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
        .button-container { text-align: center; margin: 30px 0; }
        .download-button { display: inline-block; padding: 12px 25px; background-color: #438a5e; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; }
        .download-button:hover { background-color: #2e7d32; }
        
        .special-section { background-color: #fff8e1; border-left: 5px solid #FFC107; padding: 25px; border-radius: 8px; margin: 25px 0; }
        .special-section h4 { margin-top: 0; font-size: 18px; display: flex; align-items: center; color: #c09e4f; font-weight: 600; }
        .special-section .icon { font-size: 24px; margin-right: 10px; color: #c09e4f; }
        .special-section p { margin: 10px 0; font-size: 14px; }
        
        .invest-button { display: inline-block; padding: 12px 25px; background-color: #006400; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; margin-top: 15px; }
        .invest-button:hover { background-color: #2e7d32; }
        .signature { margin-top: 40px; font-size: 14px; color: #4a5568; }
        .footer { background-color: #006400; color: white; text-align: center; padding: 15px; font-size: 12px; letter-spacing: 0.5px; opacity: 0.8; }
        
        .savings-tips { margin-top: 25px; background-color: #f7fafc; padding: 15px; border-left: 5px solid #38a169; border-radius: 10px; }
        .savings-tips h4 { color: #2f855a; margin-bottom: 10px; }
        .savings-tips ul { font-size: 14px; color: #4a5568; line-height: 1.6; margin-left: 20px; }
        .savings-tips li { margin-bottom: 8px; }
        .savings-tips p { font-size: 13px; color: #2f855a; font-style: italic; margin-top: 10px; }
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
            <p style="font-size: 14px; color: #4a5568;">Tunatumai ujumbe huu unakufikia ukiwa na afya njema. Taarifa yako ya mwezi ya amana imekamilika na sasa iko tayari kwako kuipitia.</p>
            
            <div class="card">
                <div class="card-header">
                    <span class="icon">📅</span>
                    <h4>Taarifa Yako</h4>
                </div>
                <p style="font-size: 14px; color: #4a5568;">Taarifa yako ya <strong>Amana (Deposit Statement)</strong> kwa kipindi cha <strong>{{ $periodStart ?? 'Machi 2025' }}</strong> hadi <strong>{{ $period }}</strong> imekamilika.</p>
                <div class="button-container">
                    <a href="{{ $pdfLink }}" class="download-button" target="_blank">Pakua Taarifa Yako</a>
                </div>
            </div>

            <div class="savings-tips">
                <h4>💰 Vidokezo vya Akiba (Savings Tips)</h4>
                <ul>
                    <li>💡 Weka akiba angalau <strong>10%</strong> ya kipato chako kila mwezi.</li>
                    <li>📅 Tumia kanuni ya <strong>"Jilippe Kwanza"</strong> — weka akiba kabla ya matumizi.</li>
                    <li>🎯 Weka malengo maalum ya kifedha (mfano: gawio, biashara, au nyumba).</li>
                    <li>📉 Epuka madeni yasiyo ya lazima — deni ni adui wa uhuru wa kifedha.</li>
                    <li>🌱 Wekeza sehemu ya akiba yako kwenye miradi yenye tija kama FIA.</li>
                </ul>
                <p>
                    "Uchumi wa kweli huanza na nidhamu ya akiba." 💱
                </p>
            </div>
            
            <p style="font-size: 14px; color: #4a5568;">Usisite kuwasiliana na afisa wetu wa amana endapo utakuwa na swali lolote kuhusu taarifa yako.</p>
            
            <div class="signature">
                <p>Wapendwa,<br><strong>Timu ya FeedTan CMG</strong></p>
                <p style="font-weight: 600; color: #006400;">Let's Grow Together! 🤝</p>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG Statement System V1.1.0.2025
        </div>
    </div>
</body>
</html>

