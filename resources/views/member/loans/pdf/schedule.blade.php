<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Repayment Schedule - {{ $loan->loan_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #015425; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; color: #015425; }
        .title { font-size: 18px; margin-top: 5px; color: #666; }
        .info-section { margin-bottom: 20px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-col { display: table-cell; padding: 5px; width: 50%; }
        .label { font-weight: bold; color: #015425; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #015425; color: white; padding: 10px; text-align: left; font-size: 12px; }
        td { border: 1px solid #eee; padding: 10px; font-size: 11px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 40px; font-size: 10px; text-align: center; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .total-row { font-weight: bold; background-color: #f0f0f0 !important; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FEEDTAN DIGITAL</div>
        <div class="title">Mpangilio wa Marejesho ya Mkopo (Repayment Schedule)</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-col"><span class="label">Namba ya Mkopo:</span> {{ $loan->loan_number }}</div>
                <div class="info-col"><span class="label">Jina la Mwanachama:</span> {{ $loan->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-col"><span class="label">Kiasi cha Mkopo:</span> {{ number_format($loan->principal_amount, 2) }} TZS</div>
                <div class="info-col"><span class="label">Riba (Annual):</span> {{ $loan->interest_rate }}%</div>
            </div>
            <div class="info-row">
                <div class="info-col"><span class="label">Muda wa Mkopo:</span> {{ $loan->term_months }} Miezi</div>
                <div class="info-col"><span class="label">Mzunguko wa Malipo:</span> {{ ucfirst($loan->payment_frequency) }}</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Tarehe ya Malipo</th>
                <th>Kiasi (Principal)</th>
                <th>Riba (Interest)</th>
                <th>Jumla (Total)</th>
                <th>Salio (Balance)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $remainingBalance = $loan->principal_amount;
                $monthlyInterestRate = ($loan->interest_rate / 100) / 12;
                $totalMonths = $loan->term_months;
                
                // Simple interest calculation for schedule display
                // Total interest = Principal * MonthlyRate * TotalMonths
                $totalInterest = $loan->total_amount - $loan->principal_amount;
                $monthlyPrincipal = $loan->principal_amount / $totalMonths;
                $monthlyInterest = $totalInterest / $totalMonths;
                $monthlyTotal = $monthlyPrincipal + $monthlyInterest;
                
                $date = \Carbon\Carbon::parse($loan->application_date);
            @endphp
            
            @for($i = 1; $i <= $totalMonths; $i++)
                @php
                    $date->addMonth();
                    $remainingBalance -= $monthlyPrincipal;
                    if ($remainingBalance < 0.01) $remainingBalance = 0;
                @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $date->format('d M, Y') }}</td>
                    <td>{{ number_format($monthlyPrincipal, 2) }}</td>
                    <td>{{ number_format($monthlyInterest, 2) }}</td>
                    <td>{{ number_format($monthlyTotal, 2) }}</td>
                    <td>{{ number_format($remainingBalance, 2) }}</td>
                </tr>
            @endfor
            
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">JUMLA:</td>
                <td>{{ number_format($loan->principal_amount, 2) }}</td>
                <td>{{ number_format($totalInterest, 2) }}</td>
                <td>{{ number_format($loan->total_amount, 2) }}</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Hati hii imetengenezwa kiotomatiki na Mfumo wa FeedTan Digital.<br>
        &copy; {{ date('Y') }} FEEDTAN DIGITAL. Haki zote zimehifadhiwa.
    </div>
</body>
</html>
