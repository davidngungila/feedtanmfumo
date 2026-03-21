<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Uthibitisho wa Malipo la FIA - {{ $confirmation->member_id }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #015425;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        .logo-box {
            display: inline-block;
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
        }
        .serial-number {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
        }
        th {
            background: #015425;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .summary-table td:first-child {
            background: #f9f9f9;
            font-weight: bold;
            width: 40%;
            color: #015425;
        }
        .section-header {
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 11pt;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .amount-row {
            font-weight: bold;
            background: #f0fdf4;
        }
        .amount-text {
            color: #015425;
            font-size: 11pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            <div class="logo-box">FEEDTAN DIGITAL</div>
        </div>
        <div class="title">UTHIBITISHO WA MALIPO LA FIA (FIA PAYMENT CONFIRMATION)</div>
        <div class="serial-number">Serial No: FCMG-FIA-{{ str_pad($confirmation->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Tarehe: {{ \Carbon\Carbon::parse($confirmation->created_at)->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="section-header">Taarifa za Mwanachama</div>
    <table class="summary-table">
        <tr>
            <td>ID ya Mwanachama</td>
            <td>{{ $confirmation->member_id }}</td>
        </tr>
        <tr>
            <td>Jina la Mwanachama</td>
            <td><strong>{{ strtoupper($member->member_name ?? 'N/A') }}</strong></td>
        </tr>
        <tr>
            <td>Aina ya Uanachama</td>
            <td>{{ $member->member_type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Barua Pepe (Email)</td>
            <td>{{ $confirmation->member_email }}</td>
        </tr>
        @if($confirmation->member_phone)
        <tr>
            <td>Namba ya Simu</td>
            <td>{{ $confirmation->member_phone }}</td>
        </tr>
        @endif
    </table>

    <div class="section-header">Gawio la Malipo ya FIA (FIA Payment Breakdown)</div>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Maelezo (Description)</th>
                <th class="text-right">Kiasi (Amount) - TZS</th>
            </tr>
        </thead>
        <tbody>
            @if($payment_breakdown['gawio_la_fia'] > 0)
            <tr>
                <td>1</td>
                <td>Gawio la FIA</td>
                <td class="text-right">{{ number_format($payment_breakdown['gawio_la_fia'], 2) }}</td>
            </tr>
            @endif
            @if($payment_breakdown['fia_iliyokomaa'] > 0)
            <tr>
                <td>2</td>
                <td>FIA Ilivyo Koma</td>
                <td class="text-right">{{ number_format($payment_breakdown['fia_iliyokomaa'], 2) }}</td>
            </tr>
            @endif
            @if($payment_breakdown['jumla'] > 0)
            <tr>
                <td>3</td>
                <td>Jumla</td>
                <td class="text-right">{{ number_format($payment_breakdown['jumla'], 2) }}</td>
            </tr>
            @endif
            @if($payment_breakdown['malipo_ya_vipande_yaliyokuwa_yamepelea'] > 0)
            <tr>
                <td>4</td>
                <td>Malipo ya Vipande</td>
                <td class="text-right">{{ number_format($payment_breakdown['malipo_ya_vipande_yaliyokuwa_yamepelea'], 2) }}</td>
            </tr>
            @endif
            @if($payment_breakdown['loan'] > 0)
            <tr>
                <td>5</td>
                <td>LOAN</td>
                <td class="text-right">{{ number_format($payment_breakdown['loan'], 2) }}</td>
            </tr>
            @endif
            @if($payment_breakdown['kiasi_baki'] > 0)
            <tr>
                <td>6</td>
                <td><strong>Kiasi Baki (Available Amount)</strong></td>
                <td class="text-right"><strong>{{ number_format($payment_breakdown['kiasi_baki'], 2) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-header">Mchanganuo wa Malipo (Payment Distribution)</div>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Maelezo (Description)</th>
                <th class="text-right">Kiasi (Amount) - TZS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><strong>Kiwango cha Jumla (Amount to be Paid)</strong></td>
                <td class="text-right"><strong>{{ number_format($confirmation->amount_to_pay, 2) }}</strong></td>
            </tr>
            @if($distribution['savings_amount'] > 0)
            <tr>
                <td>2</td>
                <td>Akiba (Savings)</td>
                <td class="text-right">{{ number_format($distribution['savings_amount'], 2) }}</td>
            </tr>
            @endif
            @if($distribution['swf_amount'] > 0)
            <tr>
                <td>3</td>
                <td>SWF Deduction (Mchango wa SWF)</td>
                <td class="text-right">{{ number_format($distribution['swf_amount'], 2) }}</td>
            </tr>
            @endif
            @if($distribution['loan_repayment_amount'] > 0)
            <tr>
                <td>4</td>
                <td>Loan Installment (Marejesho ya Mkopo)</td>
                <td class="text-right">{{ number_format($distribution['loan_repayment_amount'], 2) }}</td>
            </tr>
            @endif
            @if($distribution['investment_amount'] > 0)
            <tr>
                <td>5</td>
                <td>FIA Investment (Uwekezaji wa FIA - {{ $fia_type == '4-years' ? 'Miaka 4' : 'Miaka 6' }})</td>
                <td class="text-right">{{ number_format($distribution['investment_amount'], 2) }}</td>
            </tr>
            @endif
            <tr class="amount-row">
                <td colspan="2" class="text-right">
                    <strong>Cash (Furahia pesa yako)</strong><br>
                    <small style="font-weight: normal; font-style: italic;">Hii itatumwa kwako</small>
                </td>
                <td class="text-right amount-text">TZS {{ number_format($distribution['cash_amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($payment_method)
    <div class="section-header">Maelezo ya Malipo (Payment Method)</div>
    <table class="summary-table">
        <tr>
            <td>Njia iliyotumika</td>
            <td>{{ $payment_method == 'bank' ? 'Benki (Bank Transfer)' : 'Simu ya Mkononi (Mobile Money)' }}</td>
        </tr>
        @if($payment_method == 'mobile' && $confirmation->mobile_provider)
        <tr>
            <td>Mtandao</td>
            <td>{{ ucfirst($confirmation->mobile_provider) }}</td>
        </tr>
        @endif
        @if($payment_method == 'mobile' && $confirmation->mobile_number)
        <tr>
            <td>Namba ya Simu</td>
            <td><strong>{{ $confirmation->mobile_number }}</strong></td>
        </tr>
        @endif
    </table>
    @endif

    @if($notes)
    <div class="section-header">Maelezo ya Ziada</div>
    <div style="padding: 10px; border: 1px solid #ddd; margin-top: 5px;">
        {{ $notes }}
    </div>
    @endif

    <div class="footer">
        <p>Huu ni uthibitisho wa kielektroniki. Imetolewa na Mfumo wa FeedTan Digital.</p>
        <p>FeedTan Community Microfinance Group - FIA Payment Confirmation</p>
        <p>Ripoti imetengenezwa tarehe {{ \Carbon\Carbon::now()->format('d F, Y') }}</p>
    </div>
</body>
</html>
