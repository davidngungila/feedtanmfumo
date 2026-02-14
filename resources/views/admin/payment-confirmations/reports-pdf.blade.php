<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $documentTitle }} - FeedTan CMG</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4 landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.3;
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
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 10px 0 5px 0;
        }
        .sub-title {
            font-size: 10pt;
            color: #666;
            margin-bottom: 10px;
        }
        .header-info {
            font-size: 9pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th {
            background: #015425;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
            font-size: 7.5pt;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .status-badge {
            font-size: 7pt;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 10px;">
            @if(isset($headerBase64) && $headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" style="width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            @else
            <div class="logo-box">FEEDTAN</div>
            @endif
        </div>
        <div class="title">{{ $documentTitle }}</div>
        <div class="sub-title">{{ $documentSubtitle }}</div>
        <div class="header-info">
            Generated On: {{ $generatedAt }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Member ID</th>
                <th>Member Name</th>
                <th class="amount">Gross</th>
                <th class="amount">SWF</th>
                <th class="amount">Loan</th>
                <th class="amount">Capital</th>
                <th class="amount">Fine</th>
                <th class="amount">FIA/Dep</th>
                <th class="amount">Net Pay</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalGross = 0; 
                $totalNet = 0;
            @endphp
            @foreach($confirmations as $c)
            @php 
                $totalGross += $c->amount_to_pay;
                $totalNet += $c->cash_amount;
            @endphp
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->member_id }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->member_name, 25) }}</td>
                <td class="amount">{{ number_format($c->amount_to_pay, 0) }}</td>
                <td class="amount">{{ number_format($c->swf_contribution, 0) }}</td>
                <td class="amount">{{ number_format($c->loan_repayment, 0) }}</td>
                <td class="amount">{{ number_format($c->capital_contribution, 0) }}</td>
                <td class="amount">{{ number_format($c->fine_penalty, 0) }}</td>
                <td class="amount">{{ number_format($c->re_deposit + $c->fia_investment, 0) }}</td>
                <td class="amount"><strong>{{ number_format($c->cash_amount, 0) }}</strong></td>
                <td>{{ $c->payment_method ? ucfirst($c->payment_method) : 'N/A' }}</td>
                <td>{{ $c->payment_method ? 'Confirmed' : 'Pending' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f0fdf4; font-weight: bold;">
                <td colspan="3" style="text-align: right;">TOTALS:</td>
                <td class="amount">{{ number_format($totalGross, 0) }}</td>
                <td colspan="5"></td>
                <td class="amount">{{ number_format($totalNet, 0) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - System Generated Report</p>
        <p>&copy; {{ date('Y') }} FeedTan CMG. All rights reserved.</p>
    </div>
</body>
</html>
