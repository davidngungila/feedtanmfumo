<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle ?? 'Loan Performance Report' }}</title>
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
        .indicator-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 10px;
            padding: 8px;
            background: #f5f5f5;
        }
        .indicator-item .label { 
            font-weight: bold; 
        }
        .indicator-item .percentage { 
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
        .progress-fill-red { background: #dc3545; height: 100%; }
        .progress-fill-blue { background: #007bff; height: 100%; }
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
        .performance-excellent { color: #28a745; font-weight: bold; }
        .performance-good { color: #007bff; font-weight: bold; }
        .performance-fair { color: #ffc107; font-weight: bold; }
        .performance-poor { color: #dc3545; font-weight: bold; }
        .status-active { color: #28a745; font-weight: bold; }
        .status-completed { color: #007bff; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
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
        <h1>{{ $documentTitle ?? 'Loan Performance Report' }}</h1>
        <h2>{{ $documentSubtitle ?? 'Analysis of loan repayment patterns, default rates, and overall portfolio performance' }}</h2>
        <div class="meta">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }} | 
            FeedTan Community Microfinance Group
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="section">
        <h3>Performance Overview</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['total_loans']) }}</span>
                <span class="label">Total Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['paid_loans']) }}</span>
                <span class="label">Paid Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['recovery_rate'], 1) }}%</span>
                <span class="label">Recovery Rate</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['default_rate'], 1) }}%</span>
                <span class="label">Default Rate</span>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['active_loans']) }}</span>
                <span class="label">Active Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['avg_loan_size'], 0) }} TZS</span>
                <span class="label">Avg Loan Size</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($performanceStats['total_revenue'], 0) }} TZS</span>
                <span class="label">Total Revenue</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format(($performanceStats['paid_loans'] / max(1, $performanceStats['total_loans'])) * 100, 1) }}%</span>
                <span class="label">Completion Rate</span>
            </div>
        </div>
    </div>

    <!-- Performance Indicators -->
    <div class="section">
        <h3>Performance Indicators</h3>
        <div class="indicator-item">
            <span class="label">Recovery Rate</span>
            <span class="percentage">{{ number_format($performanceStats['recovery_rate'], 1) }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill-green" style="width: {{ min(100, $performanceStats['recovery_rate']) }}%"></div>
        </div>
        
        <div class="indicator-item">
            <span class="label">Default Rate</span>
            <span class="percentage">{{ number_format($performanceStats['default_rate'], 1) }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill-red" style="width: {{ min(100, $performanceStats['default_rate']) }}%"></div>
        </div>
        
        <div class="indicator-item">
            <span class="label">Loan Completion Rate</span>
            <span class="percentage">{{ number_format(($performanceStats['paid_loans'] / max(1, $performanceStats['total_loans'])) * 100, 1) }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill-blue" style="width: {{ ($performanceStats['paid_loans'] / max(1, $performanceStats['total_loans'])) * 100 }}%"></div>
        </div>
    </div>

    <!-- Monthly Performance Trend -->
    <div class="section">
        <h3>Monthly Performance Trend ({{ date('Y') }})</h3>
        @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        @endphp
        @foreach($months as $index => $month)
            @php
                $monthData = $monthlyPerformance->firstWhere('month', $index + 1);
                $totalLoans = $monthData->total_loans ?? 0;
                $totalAmount = $monthData->total_amount ?? 0;
                $paidAmount = $monthData->paid_amount ?? 0;
                $recoveryRate = $totalAmount > 0 ? ($paidAmount / $totalAmount) * 100 : 0;
            @endphp
            @if($totalLoans > 0)
                <div class="monthly-trend">
                    <span class="month">{{ $month }}</span>
                    <span class="data">
                        {{ $totalLoans }} loans | 
                        {{ number_format($totalAmount, 0) }} TZS | 
                        {{ number_format($recoveryRate, 1) }}% recovery
                    </span>
                    <div style="clear: both;"></div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Loan Performance Details -->
    <div class="section">
        <h3>Loan Performance Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Loan #</th>
                    <th>Member</th>
                    <th>Principal</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    @php
                        $progress = $loan->principal_amount > 0 ? ($loan->paid_amount / $loan->principal_amount) * 100 : 0;
                        $performanceLabel = $progress >= 90 ? 'Excellent' : ($progress >= 75 ? 'Good' : ($progress >= 50 ? 'Fair' : 'Poor'));
                        $performanceClass = $progress >= 90 ? 'excellent' : ($progress >= 75 ? 'good' : ($progress >= 50 ? 'fair' : 'poor'));
                    @endphp
                    <tr>
                        <td>{{ $loan->loan_number }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td class="text-right">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                        <td class="text-right">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                        <td class="text-right">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill-{{ $progress >= 75 ? 'green' : ($progress >= 50 ? 'yellow' : 'red') }}" style="width: {{ min(100, $progress) }}%"></div>
                            </div>
                            <small>{{ number_format($progress, 1) }}%</small>
                        </td>
                        <td>
                            <span class="status-{{ $loan->status }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="performance-{{ $performanceClass }}">
                                {{ $performanceLabel }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No loans found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Loan Performance Report</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>
