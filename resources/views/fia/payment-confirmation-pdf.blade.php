<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thibitisho la Malipo la FIA - FEEDTAN CMG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #015425;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #015425;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #015425;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .payment-table th,
        .payment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .payment-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .payment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #015425;
            color: white;
            font-weight: bold;
        }
        .distribution-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .distribution-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #015425;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .confirmation-id {
            background-color: #015425;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">FEEDTAN CMG</div>
        <div class="subtitle">Thibitisho la Malipo la FIA</div>
        <div class="confirmation-id">ID: {{ $confirmation->id }}</div>
    </div>

    <!-- Member Information -->
    <div class="section">
        <div class="section-title">Taarifa za Mwanachama</div>
        <div class="info-row">
            <span class="info-label">Jina la Mwanachama:</span>
            <span class="info-value">{{ $member->member_name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nambari ya Uanachama:</span>
            <span class="info-value">{{ $member->member_id ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Barua pepe:</span>
            <span class="info-value">{{ $confirmation->member_email ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nambari ya Simu:</span>
            <span class="info-value">{{ $confirmation->member_phone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tarehe ya Thibitisho:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($confirmation->created_at)->format('d M Y, H:i') }}</span>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="section">
        <div class="section-title">Gawio la Malipo ya FIA</div>
        <table class="payment-table">
            <thead>
                <tr>
                    <th> Maelezo </th>
                    <th> Kiasi (TZS) </th>
                </tr>
            </thead>
            <tbody>
                @if($payment_breakdown['gawio_la_fia'] > 0)
                <tr>
                    <td> Gawio la FIA </td>
                    <td class="amount"> {{ number_format($payment_breakdown['gawio_la_fia'], 2) }} </td>
                </tr>
                @endif
                @if($payment_breakdown['fia_iliyokomaa'] > 0)
                <tr>
                    <td> FIA Ilivyo Koma </td>
                    <td class="amount"> {{ number_format($payment_breakdown['fia_iliyokomaa'], 2) }} </td>
                </tr>
                @endif
                @if($payment_breakdown['jumla'] > 0)
                <tr>
                    <td> Jumla </td>
                    <td class="amount"> {{ number_format($payment_breakdown['jumla'], 2) }} </td>
                </tr>
                @endif
                @if($payment_breakdown['malipo_ya_vipande_yaliyokuwa_yamepelea'] > 0)
                <tr>
                    <td> Malipo ya Vipande </td>
                    <td class="amount"> {{ number_format($payment_breakdown['malipo_ya_vipande_yaliyokuwa_yamepelea'], 2) }} </td>
                </tr>
                @endif
                @if($payment_breakdown['loan'] > 0)
                <tr>
                    <td> LOAN </td>
                    <td class="amount"> {{ number_format($payment_breakdown['loan'], 2) }} </td>
                </tr>
                @endif
                @if($payment_breakdown['kiasi_baki'] > 0)
                <tr>
                    <td> Kiasi Baki </td>
                    <td class="amount"> {{ number_format($payment_breakdown['kiasi_baki'], 2) }} </td>
                </tr>
                @endif
                <tr class="total-row">
                    <td> Jumla ya Kiasi Kinachopatikana </td>
                    <td class="amount"> {{ number_format($payment_breakdown['kiasi_baki'], 2) }} </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Payment Distribution -->
    <div class="section">
        <div class="section-title">Mgawanyo wa Malipo</div>
        
        @if($distribution['savings_amount'] > 0)
        <div class="distribution-box">
            <div class="distribution-title"> Akiba </div>
            <div class="info-row">
                <span class="info-label"> Kiasi cha Akiba: </span>
                <span class="info-value"> TZS {{ number_format($distribution['savings_amount'], 2) }} </span>
            </div>
        </div>
        @endif

        @if($distribution['investment_amount'] > 0)
        <div class="distribution-box">
            <div class="distribution-title"> Uwekezaji (Vipande FIA) </div>
            <div class="info-row">
                <span class="info-label"> Kiasi cha Uwekezaji: </span>
                <span class="info-value"> TZS {{ number_format($distribution['investment_amount'], 2) }} </span>
            </div>
            @if($fia_type)
            <div class="info-row">
                <span class="info-label"> Muda wa Uwekezaji: </span>
                <span class="info-value"> {{ $fia_type == '4-years' ? 'Miaka 4' : 'Miaka 6' }} </span>
            </div>
            @endif
        </div>
        @endif

        @if($distribution['swf_amount'] > 0)
        <div class="distribution-box">
            <div class="distribution-title"> Nachangia SWF </div>
            <div class="info-row">
                <span class="info-label"> Kiasi cha SWF: </span>
                <span class="info-value"> TZS {{ number_format($distribution['swf_amount'], 2) }} </span>
            </div>
        </div>
        @endif

        @if($distribution['loan_repayment_amount'] > 0)
        <div class="distribution-box">
            <div class="distribution-title"> Nalipa Rejesho La Mkopo </div>
            <div class="info-row">
                <span class="info-label"> Kiasi cha Rejesho: </span>
                <span class="info-value"> TZS {{ number_format($distribution['loan_repayment_amount'], 2) }} </span>
            </div>
        </div>
        @endif

        @if($distribution['cash_amount'] > 0)
        <div class="distribution-box">
            <div class="distribution-title"> Cash (Pesa Taslimu) </div>
            <div class="info-row">
                <span class="info-label"> Kiasi cha Cash: </span>
                <span class="info-value"> TZS {{ number_format($distribution['cash_amount'], 2) }} </span>
            </div>
        </div>
        @endif
    </div>

    <!-- Payment Method -->
    <div class="section">
        <div class="section-title">Njia ya Malipo</div>
        <div class="info-row">
            <span class="info-label"> Njia Ilivyochaguliwa: </span>
            <span class="info-value"> 
                @if($payment_method == 'bank')
                    Benki (Bank Transfer)
                @else
                    Simu ya Mkononi (Mobile Money)
                @endif
            </span>
        </div>
        @if($payment_method == 'mobile' && $confirmation->mobile_provider)
        <div class="info-row">
            <span class="info-label"> Mtandao wa Simu: </span>
            <span class="info-value"> {{ $confirmation->mobile_provider }} </span>
        </div>
        @endif
        @if($payment_method == 'mobile' && $confirmation->mobile_number)
        <div class="info-row">
            <span class="info-label"> Nambari ya Simu: </span>
            <span class="info-value"> {{ $confirmation->mobile_number }} </span>
        </div>
        @endif
    </div>

    <!-- Notes -->
    @if($notes)
    <div class="section">
        <div class="section-title">Maelezo ya Ziada</div>
        <p>{{ $notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>FEEDTAN CMG - FIA Payment Confirmation</strong></p>
        <p>Hiki ndicho hati halisi ya thibitisho la malipo linalotokana na mfumo wetu.</p>
        <p>Kwa msaada wasiliana nasi: support@feedtancmg.com</p>
        <p>Imetengenezwa tarehe: {{ \Carbon\Carbon::now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html>
