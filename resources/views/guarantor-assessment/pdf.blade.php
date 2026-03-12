<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ukaguzi wa Dhamani - {{ $assessment->full_name }}</title>
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
        .agreement-text {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 9pt;
            line-height: 1.5;
        }
        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #015425;
            padding-top: 20px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 30px 0 10px 0;
            height: 30px;
        }
        .highlight-row {
            background: #f0fdf4;
            font-weight: bold;
        }
        .highlight-text {
            color: #015425;
            font-size: 11pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @if(isset($headerBase64) && $headerBase64)
                <img src="{{ $headerBase64 }}" alt="FeedTan Header" style="width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            @else
                <div class="logo-box">FEEDTAN DIGITAL</div>
            @endif
        </div>
        <div class="title">UKAGUZI WA DHAMANI (GUARANTOR ASSESSMENT)</div>
        <div class="serial-number">Serial No: FCMG-GA-{{ str_pad($assessment->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Tarehe: {{ $assessment->assessment_date->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="section-header">Taarifa ya Mkopo</div>
    <table class="summary-table">
        <tr>
            <td>Namba ya Mkopo</td>
            <td>{{ $loan->ulid }}</td>
        </tr>
        <tr>
            <td>Jina la Mkopaji</td>
            <td><strong>{{ strtoupper($loan->user->name) }}</strong></td>
        </tr>
        <tr>
            <td>Kiasi cha Mkopo</td>
            <td class="highlight-text">TZS {{ number_format($loan->amount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Tarehe ya Ukaguzi</td>
            <td>{{ $assessment->assessment_date->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="section-header">Taarifa za Mdhambi</div>
    <table class="summary-table">
        <tr>
            <td>Jina Kamili</td>
            <td><strong>{{ strtoupper($assessment->full_name) }}</strong></td>
        </tr>
        <tr>
            <td>Namba ya Uanachama</td>
            <td>{{ $assessment->member_code }}</td>
        </tr>
        <tr>
            <td>Namba ya Simu</td>
            <td><strong>{{ $assessment->phone }}</strong></td>
        </tr>
        <tr>
            <td>Barua Pepe (Email)</td>
            <td>{{ $assessment->email }}</td>
        </tr>
        <tr>
            <td>Uhusiano na Mkopaji</td>
            <td>{{ $assessment->relationship }}{{ $assessment->relationship_other ? ' (' . $assessment->relationship_other . ')' : '' }}</td>
        </tr>
        <tr>
            <td>Anwani</td>
            <td>{{ $assessment->address }}</td>
        </tr>
        <tr>
            <td>Kazi</td>
            <td>{{ $assessment->occupation }}</td>
        </tr>
        <tr>
            <td>Kipato cha Kila Mwezi</td>
            <td class="highlight-text">TZS {{ number_format($assessment->monthly_income, 2) }}</td>
        </tr>
    </table>

    <div class="section-header">Jibu la Ukaguzi</div>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Suala la Ukaguzi</th>
                <th>Jibu</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><strong>Kuelewa Madhumuni ya Mkopo</strong></td>
                <td>{{ $assessment->loan_purpose }}{{ $assessment->loan_purpose_other ? ' (' . $assessment->loan_purpose_other . ')' : '' }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Amechunguza Historia ya Malipo ya Mkopaji</td>
                <td>{{ $assessment->repayment_history }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Madeni Mengine yaliyomo</td>
                <td>{{ $assessment->existing_debts }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Akifa za Kutosha</td>
                <td>{{ $assessment->sufficient_savings }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Kuelewa Uwajibikisho Mmoja</td>
                <td>{{ $assessment->sole_responsibility }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Kuelewa Mchakato wa Ukusanyaji</td>
                <td>{{ $assessment->recovery_process }}</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Dhamani hiyo ni hiari</td>
                <td>{{ $assessment->voluntary_guarantee }}</td>
            </tr>
        </tbody>
    </table>

    @if($assessment->additional_comments)
    <div class="section-header">Maoni ya Ziada</div>
    <div style="padding: 10px; border: 1px solid #ddd; margin-top: 5px;">
        {{ $assessment->additional_comments }}
    </div>
    @endif

    <div class="section-header">Masharti ya Dhamani</div>
    <div class="agreement-text">
        <p><strong>1. Dhamani ya Uwekezaji:</strong> Mimi, chini chini, hapa kwa bidii na pasipo masharti, ninaliweka dhamani ya kudumu na isiyobadilika kwa Feedtan Community Microfinance Group (hapa inajulikana kama "Mdhamini") malipo ya kila ada, riba, gharama, na ada zote ambazo zinaweza kuwa zinadaiwa na mkopaji kwa Mdhamini chini ya mkataba wa mkopo ulioainishwa hapo juu.</p>
        
        <p><strong>2. Kipindi cha Dhamani:</strong> Dhamani hii itaendelea kwa nguvu kamili na athari hadi mkopo pamoja na riba zake zote, gharama, ada, na ada zingine zinazodaiwa na mkopaji kwa Mdhamini chini ya mkataba wa mkopo zitakapolipwa na kusuluhishwa kwa Mdhamini.</p>
        
        <p><strong>3. Dhamani Inayoendelea:</strong> Dhamani hii itaendelea kwa nguvu kamili na athari hadi mkopo, pamoja na riba zake zote, gharama, ada, na ada zingine zinazodaiwa na mkopaji kwa Mdhamini chini ya mkataba wa mkopo zitakapolipwa na kusuluhishwa kwa Mdhamini.</p>
        
        <p><strong>4. Hakuna Tahadhari Inayohitajika:</strong> Mdhamini hatahitaji kupewa tahadhari yoyote ya kushindwa kwa mkopaji au mahitaji yoyote yaliyowekwa kwa mkopaji, na mdhamini hapa anakanusha tahadhari hiyo yote.</p>
        
        <p><strong>5. Malipo ya Haraka:</strong> Kwa ombi la Mdhamini, mdhamini atalipa mara moja kwa Mdhamini kiasi chote kinachodaiwa na kinachodaiwa na mkopaji chini ya mkataba wa mkopo bila kuhitaji mahitaji yoyote au tahadhari kuwekwa kwa mkopaji.</p>
        
        <p><strong>6. Kanusho Haki:</strong> Mdhamini anakanusha haki zote za kudai Mdhamini kuendelea dhidi ya mkopaji au dhamani yoyote iliyo na Mdhamini kabla ya kuendelea dhidi ya mdhamini.</p>
        
        <p><strong>7. Ukakikishaji:</strong> Mdhamini anakiri kuwa amesoma na kuelewa masharti ya dhamani hii na kuwa anaiandika mkataba hii kwa hiari na bila shinikizo lolote la kushindwa au ushawishi.</p>
    </div>

    <div class="signature-section">
        <div class="section-header">Sahihi</div>
        <div class="signature-grid">
            <div class="signature-box">
                <div class="info-label">Sahihi ya Mdhamini</div>
                <div class="signature-line"></div>
                <div class="info-value">{{ $assessment->full_name }}</div>
                <div class="info-label" style="margin-top: 10px;">Tarehe: {{ $assessment->assessment_date->format('d M Y') }}</div>
            </div>
            <div class="signature-box">
                <div class="info-label">Sahihi ya Shuhuda</div>
                <div class="signature-line"></div>
                <div class="info-value">_________________________</div>
                <div class="info-label" style="margin-top: 10px;">Tarehe: _______________</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Huu ni uthibitisho wa kielektroniki. Imetolewa na Mfumo wa FeedTan Digital.</p>
        <p>FeedTan Community Microfinance Group</p>
        <p>Ripoti imetengenezwa tarehe {{ now()->format('d F, Y') }}</p>
    </div>
</body>
</html>
