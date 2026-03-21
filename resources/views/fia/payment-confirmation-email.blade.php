<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thibitisho la Malipo la FIA - FEEDTAN CMG</title>
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
            background-color: #015425;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .confirmation-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #015425;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .highlight {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #015425;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .amount {
            font-weight: bold;
            color: #015425;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FEEDTAN CMG</div>
        <div>Thibitisho la Malipo la FIA</div>
    </div>

    <div class="content">
        <h2>Thibitisho la Malipo Limepokelewa!</h2>
        
        <p>Dear {{ $member_name }},</p>
        
        <p>Ombi lako la thibitisho la malipo la FIA limepokelewa kwa mafanikio. Ahsante kwa kuwasiliana nasi.</p>

        <div class="confirmation-details">
            <h3>Maelezo ya Thibitisho</h3>
            <div class="detail-row">
                <span class="detail-label">Nambari ya Uanachama:</span>
                <span class="detail-value">{{ $member_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">ID ya Thibitisho:</span>
                <span class="detail-value">#{{ $confirmation_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Kiasi cha Malipo:</span>
                <span class="detail-value amount">TZS {{ number_format($amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Njia ya Malipo:</span>
                <span class="detail-value">{{ $payment_method == 'bank' ? 'Benki (Bank Transfer)' : 'Simu ya Mkononi (Mobile Money)' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tarehe ya Kuombwa:</span>
                <span class="detail-value">{{ $confirmation_date }}</span>
            </div>
        </div>

        <div class="highlight">
            <h4>⚠️ Muhimu:</h4>
            <p>Hatimahi ya PDF imeshawekwa kwenye barua hii. Tafadhali pakua na kuhifadhi hati hiyo kwa ajili ya rekodi zako.</p>
            <p>Hati ya PDF ina maelezo kamili ya malipo yako pamoja na mgawanyo wa pesa.</p>
        </div>

        <p><strong>Hatua Zifuatazo:</strong></p>
        <ul>
            <li>Tutakagua na kuthibitisha taarifa zako</li>
            <li>Utapokea arifa kupitia barua pepe au SMS wakati utakapothibitishwa</li>
            <li>Malipo yako yatatengenezwa kulingana na njia uliyochagua</li>
        </ul>

        <p>Ikawa kuna maswali yoyote, tafadhali wasiliana nasi:</p>
        <p>
            📧 Email: support@feedtancmg.com<br>
            📞 Simu: +255 XXX XXX XXX
        </p>
    </div>

    <div class="footer">
        <p><strong>FEEDTAN CMG</strong></p>
        <p>Thibitisho la Malipo la FIA | Hiki ni barua ya otomatiki, tafadhali usijibu</p>
        <p>&copy; {{ date('Y') }} FEEDTAN CMG. Haki zote zimehifadhiwa.</p>
    </div>
</body>
</html>
