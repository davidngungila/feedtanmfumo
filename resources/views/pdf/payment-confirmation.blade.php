<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Uthibitisho wa Malipo - {{ $paymentConfirmation->member_id }}</title>
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
        <div style="text-align: center; margin-bottom: 10px;">
            <div class="logo-box">FEEDTAN DIGITAL</div>
        </div>
        <div class="title">UTHIBITISHO WA MALIPO (PAYMENT CONFIRMATION)</div>
        <div class="serial-number">Serial No: FCMG-PC-{{ str_pad($paymentConfirmation->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Tarehe: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="section-header">Taarifa za Mwanachama</div>
    <table class="summary-table">
        <tr>
            <td>ID ya Mwanachama</td>
            <td>{{ $paymentConfirmation->member_id }}</td>
        </tr>
        <tr>
            <td>Jina la Mwanachama</td>
            <td><strong>{{ strtoupper($paymentConfirmation->member_name) }}</strong></td>
        </tr>
        <tr>
            <td>Aina ya Uanachama</td>
            <td>{{ $paymentConfirmation->member_type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Barua Pepe (Email)</td>
            <td>{{ $paymentConfirmation->member_email }}</td>
        </tr>
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
                <td class="text-right"><strong>{{ number_format($paymentConfirmation->amount_to_pay, 2) }}</strong></td>
            </tr>
            <tr>
                <td>2</td>
                <td>SWF Deduction (Mchango wa SWF)</td>
                <td class="text-right">{{ number_format($paymentConfirmation->swf_contribution, 2) }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Loan Installment (Marejesho ya Mkopo)</td>
                <td class="text-right">{{ number_format($paymentConfirmation->loan_repayment, 2) }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Capital Contribution (Hisa)</td>
                <td class="text-right">{{ number_format($paymentConfirmation->capital_contribution, 2) }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Fine/Penalty (Faini/Adhabu)</td>
                <td class="text-right">{{ number_format($paymentConfirmation->fine_penalty, 2) }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Re-deposit (Akiba ya Ziada)</td>
                <td class="text-right">{{ number_format($paymentConfirmation->re_deposit, 2) }}</td>
            </tr>
            @if($paymentConfirmation->fia_investment > 0)
            <tr>
                <td>7</td>
                <td>FIA Investment (Uwekezaji wa FIA - {{ $paymentConfirmation->fia_type === '4_year' ? 'Miaka 4' : 'Miaka 6' }})</td>
                <td class="text-right">{{ number_format($paymentConfirmation->fia_investment, 2) }}</td>
            </tr>
            @endif
            <tr class="amount-row">
                <td colspan="2" class="text-right">NET PAYMENT (KIASI UNAPOKEA CASH)</td>
                <td class="text-right amount-text">TZS {{ number_format($paymentConfirmation->cash_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($paymentConfirmation->cash_amount > 0)
    <div class="section-header">Njia ya Malipo ya Cash</div>
    <table class="summary-table">
        <tr>
            <td>Njia ya Malipo</td>
            <td>{{ $paymentConfirmation->payment_method === 'bank' ? 'Benki (Bank Transfer)' : 'Simu ya Mkononi (Mobile Money)' }}</td>
        </tr>
        @if($paymentConfirmation->payment_method === 'bank')
        <tr>
            <td>Namba ya Akaunti ya Benki</td>
            <td><strong>{{ $paymentConfirmation->bank_account_number }}</strong></td>
        </tr>
        @else
        <tr>
            <td>Mtandao wa Simu</td>
            <td>{{ ucfirst($paymentConfirmation->mobile_provider) }}</td>
        </tr>
        <tr>
            <td>Namba ya Simu</td>
            <td><strong>{{ $paymentConfirmation->mobile_number }}</strong></td>
        </tr>
        @endif
    </table>
    @endif

    @if($paymentConfirmation->notes)
    <div class="section-header">Maelezo ya Ziada</div>
    <div style="padding: 10px; border: 1px solid #ddd; margin-top: 5px;">
        {{ $paymentConfirmation->notes }}
    </div>
    @endif

    <div class="footer">
        <p>Huu ni uthibitisho wa kielektroniki. Imetolewa na Mfumo wa FeedTan Digital.</p>
        <p>FeedTan Community Microfinance Group</p>
        <p>Ripoti imetengenezwa tarehe {{ now()->format('d F, Y') }}</p>
    </div>
</body>
</html>
