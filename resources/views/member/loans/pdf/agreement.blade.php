<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Agreement - {{ $loan->loan_number }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.6; padding: 20px; text-align: justify; }
        .header { text-align: center; margin-bottom: 40px; }
        .logo { font-size: 28px; font-weight: bold; color: #015425; margin-bottom: 5px; }
        .doc-title { font-size: 20px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .section { margin-bottom: 25px; }
        .section-title { font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .content { margin-left: 20px; }
        .signature-section { margin-top: 50px; }
        .signature-grid { display: table; width: 100%; border-spacing: 20px 0; }
        .signature-row { display: table-row; }
        .signature-col { display: table-cell; width: 33%; vertical-align: bottom; }
        .signatue-line { border-top: 1px solid #000; margin-top: 60px; padding-top: 5px; text-align: center; font-size: 12px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FEEDTAN DIGITAL</div>
        <p>Plot No. 123, Financial District, Dar es Salaam, Tanzania</p>
        <div class="doc-title">MKATABA WA MKOPO (LOAN AGREEMENT)</div>
        <p><strong>Namba ya Mkataba:</strong> {{ $loan->loan_number }}</p>
    </div>

    <div class="section">
        <div class="section-title">1. VYAMA KWENYE MKATABA</div>
        <div class="content">
            Mkataba huu unafanyika leo tarehe <strong>{{ $loan->application_date->format('d/m/Y') }}</strong> kati ya:
            <br><br>
            <strong>MKOPESHAJI:</strong> FEEDTAN DIGITAL, Taasisi ya Kifedha ya Kidijitali, hapa inajulikana kama "Taasisi".
            <br><br>
            <strong>MKOPAJI:</strong> <strong>{{ strtoupper($loan->user->name) }}</strong>, Mwanachama namba {{ $loan->user->member_number ?? $loan->user->membership_code }}, hapa inajulikana kama "Mwanachama".
        </div>
    </div>

    <div class="section">
        <div class="section-title">2. KIASI CHA MKOPO NA RIBA</div>
        <div class="content">
            Taasisi inakubali kummkopesha Mwanachama kiasi cha <strong>TZS {{ number_format($loan->principal_amount, 2) }}</strong> (Kiasi cha Mtaji), kwa riba ya <strong>{{ $loan->interest_rate }}%</strong> kwa mwaka. 
            Jumla ya kiasi kinachopaswa kurejeshwa ni <strong>TZS {{ number_format($loan->total_amount, 2) }}</strong>.
        </div>
    </div>

    <div class="section">
        <div class="section-title">3. MUDA NA NYALIA YA MAREJESHO</div>
        <div class="content">
            Mkopo huu utarejeshwa ndani ya kipindi cha <strong>{{ $loan->term_months }} miezi</strong>. Marejesho yatafanyika kwa awamu za <strong>{{ $loan->payment_frequency }}</strong> kama ilivyoainishwa kwenye mpangilio wa marejesho (Repayment Schedule) uliounganishwa na mkataba huu.
        </div>
    </div>

    <div class="section">
        <div class="section-title">4. DHAMANA</div>
        <div class="content">
            Mkopo huu umedhaminiwa na:
            @if($loan->loan_type === 'Guaranteed Loan' && $loan->guarantor)
                Mwanachama <strong>{{ strtoupper($loan->guarantor->name) }}</strong> (Namba: {{ $loan->guarantor->member_number ?? $loan->guarantor->membership_code }}).
            @elseif($loan->collateral_description)
                Dhamana ya Mali: {{ $loan->collateral_description }} (Thamani: TZS {{ number_format($loan->collateral_value, 2) }}).
            @else
                Dhamana ya Akiba ya Mwanachama ndani ya Taasisi.
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">5. MAKUBALIANO YA JUMLA</div>
        <div class="content">
            Mkopaji anakubali kufuata kanuni na taratibu zote za mikopo za FeedTan Digital. Endapo marejesho yatachelewa, hatua za kisheria na faini zilizowekwa zitatumika. 
            Mkopaji anaidhinisha Taasisi kukata kiasi cha marejesho kutoka kwenye vyanzo vyake vya mapato vilivyoainishwa.
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-grid">
            <div class="signature-row">
                <div class="signature-col">
                    <div class="signatue-line">Saini ya Mkopaji</div>
                </div>
                <div class="signature-col">
                    @if($loan->guarantor)
                    <div class="signatue-line">Saini ya Mdhamini</div>
                    @endif
                </div>
                <div class="signature-col">
                    <div class="signatue-line">Kwa niaba ya FEEDTAN DIGITAL</div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Hati hii imetengenezwa kiotomatiki kwa njia ya kielektroniki.<br>
        Plot No. 123, Dar es Salaam | Simu: +255 XXX XXX XXX | Barua Pepe: info@feedtan.co.tz
    </div>
</body>
</html>
