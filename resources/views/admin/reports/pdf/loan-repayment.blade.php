<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle ?? 'Loan Repayment Report' }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 10px; 
            line-height: 1.4;
            margin: 0;
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 3px solid #015425;
            padding-bottom: 15px;
            position: relative;
        }
        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .header h1 { 
            color: #015425; 
            margin: 10px 0 5px 0; 
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 { 
            color: #666; 
            margin: 0 0 10px 0; 
            font-size: 12px;
            font-weight: normal;
        }
        .header .meta {
            margin-top: 10px;
            font-size: 11px;
            color: #888;
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 15px; 
            margin-bottom: 30px; 
        }
        .stat-box { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
            background: #f9f9f9;
        }
        .stat-box .value { 
            font-size: 18px; 
            font-weight: bold; 
            color: #015425; 
            display: block;
        }
        .stat-box .label { 
            font-size: 10px; 
            color: #666; 
            text-transform: uppercase;
        }
        .section { 
            margin-bottom: 25px; 
        }
        .section h3 { 
            color: #015425; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 5px; 
            margin-bottom: 15px;
            font-size: 16px;
        }
        .summary-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 8px;
            padding: 5px;
            background: #f5f5f5;
        }
        .summary-item .label { 
            font-weight: bold; 
        }
        .summary-item .percentage { 
            font-weight: bold; 
            color: #015425;
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #ddd;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }
        .progress-fill-green { background: #28a745; height: 100%; }
        .progress-fill-orange { background: #fd7e14; height: 100%; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
            font-size: 11px;
        }
        th { 
            background-color: #015425; 
            color: white; 
            font-weight: bold;
        }
        tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        .payment-method {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            background: #e3f2fd;
            color: #1976d2;
        }
        .status-completed {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            background: #e8f5e9;
            color: #2e7d32;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid #ddd; 
            text-align: center; 
            font-size: 10px; 
            color: #888; 
        }
        .monthly-trend {
            margin-bottom: 8px;
            padding: 5px;
            background: #f5f5f5;
        }
        .monthly-trend .month {
            font-weight: bold;
            display: inline-block;
            width: 60px;
        }
        .monthly-trend .data {
            float: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('header-mfumo.png') }}" alt="FeedTan CMG Header" style="max-width: 100%; height: auto;">
        <h1>{{ $documentTitle ?? 'Loan Repayment Report' }}</h1>
        <h2>{{ $documentSubtitle ?? 'Detailed analysis of loan repayment patterns, schedules, and collection efficiency' }}</h2>
        <div class="meta">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }} | 
            FeedTan Community Microfinance Group
        </div>
    </div>

    <!-- Repayment Overview -->
    <div class="section">
        <h3>Repayment Overview</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['total_repayments']) }}</span>
                <span class="label">Total Repayments</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['total_amount_paid'], 0) }} TZS</span>
                <span class="label">Amount Paid</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['avg_repayment_amount'], 0) }} TZS</span>
                <span class="label">Avg Repayment</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['loans_with_repayments']) }}</span>
                <span class="label">Loans with Repayments</span>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['on_time_repayments']) }}</span>
                <span class="label">On-Time Repayments</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($repaymentStats['late_repayments']) }}</span>
                <span class="label">Late Repayments</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ $repaymentStats['total_repayments'] > 0 ? number_format(($repaymentStats['on_time_repayments'] / $repaymentStats['total_repayments']) * 100, 1) : 0 }}%</span>
                <span class="label">On-Time Rate</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ $repaymentStats['total_repayments'] > 0 ? number_format(($repaymentStats['late_repayments'] / $repaymentStats['total_repayments']) * 100, 1) : 0 }}%</span>
                <span class="label">Late Rate</span>
            </div>
        </div>
    </div>

    <!-- Repayment Summary -->
    <div class="section">
        <h3>Repayment Summary</h3>
        @php
            $totalRepayments = $repaymentStats['total_repayments'];
            $onTimeRate = $totalRepayments > 0 ? ($repaymentStats['on_time_repayments'] / $totalRepayments) * 100 : 0;
            $lateRate = $totalRepayments > 0 ? ($repaymentStats['late_repayments'] / $totalRepayments) * 100 : 0;
        @endphp
        <div class="summary-item">
            <span class="label">On-Time Payments</span>
            <span class="percentage">{{ number_format($repaymentStats['on_time_repayments']) }} ({{ number_format($onTimeRate, 1) }}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill-green" style="width: {{ $onTimeRate }}%"></div>
        </div>
        
        <div class="summary-item">
            <span class="label">Late Payments</span>
            <span class="percentage">{{ number_format($repaymentStats['late_repayments']) }} ({{ number_format($lateRate, 1) }}%)</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill-orange" style="width: {{ $lateRate }}%"></div>
        </div>
    </div>

    <!-- Monthly Repayment Trend -->
    <div class="section">
        <h3>Monthly Repayment Trend ({{ date('Y') }})</h3>
        @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        @endphp
        @foreach($months as $index => $month)
            @php
                $monthData = $monthlyRepayments->firstWhere('month', $index + 1);
                $count = $monthData->count ?? 0;
                $total = $monthData->total ?? 0;
            @endphp
            @if($count > 0)
                <div class="monthly-trend">
                    <span class="month">{{ $month }}</span>
                    <span class="data">
                        {{ $count }} repayments | 
                        {{ number_format($total, 0) }} TZS | 
                        Avg: {{ number_format($total / $count, 0) }} TZS
                    </span>
                    <div style="clear: both;"></div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Repayment Transaction Details -->
    <div class="section">
        <h3>Repayment Transaction Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Member</th>
                    <th>Loan #</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($repayments as $repayment)
                    <tr>
                        <td>#{{ str_pad($repayment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $repayment->loan->user->name ?? 'N/A' }}</td>
                        <td>{{ $repayment->loan->loan_number ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($repayment->amount, 0) }} TZS</td>
                        <td>
                            <span class="payment-method">
                                {{ ucfirst($repayment->payment_method ?? 'Cash') }}
                            </span>
                        </td>
                        <td>{{ $repayment->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="status-completed">
                                Completed
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No repayment transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Loan Repayment Report</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>
