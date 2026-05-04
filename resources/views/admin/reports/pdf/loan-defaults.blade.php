<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle ?? 'Loan Defaults Report' }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            margin: 20px;
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #015425;
            padding-bottom: 20px;
        }
        .header h1 { 
            color: #015425; 
            margin: 0; 
            font-size: 24px;
        }
        .header h2 { 
            color: #666; 
            margin: 5px 0 0 0; 
            font-size: 14px;
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
        .risk-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 8px;
            padding: 8px;
            background: #f5f5f5;
        }
        .risk-item.critical { background: #ffebee; border-left: 4px solid #f44336; }
        .risk-item.high { background: #fff3e0; border-left: 4px solid #ff9800; }
        .risk-item.medium { background: #fffde7; border-left: 4px solid #ffc107; }
        .risk-item.low { background: #e3f2fd; border-left: 4px solid #2196f3; }
        .risk-item .label { 
            font-weight: bold; 
        }
        .risk-item .count { 
            font-weight: bold; 
            color: #015425;
        }
        .priority-item {
            margin-bottom: 10px;
            padding: 10px;
            border-left: 4px solid #ddd;
        }
        .priority-item.high { border-left-color: #f44336; background: #ffebee; }
        .priority-item.medium { border-left-color: #ff9800; background: #fff3e0; }
        .priority-item.low { border-left-color: #ffc107; background: #fffde7; }
        .priority-item .title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .priority-item .description {
            font-size: 11px;
            color: #666;
        }
        .priority-item .badge {
            float: right;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-high { background: #f44336; color: white; }
        .badge-medium { background: #ff9800; color: white; }
        .badge-low { background: #ffc107; color: #333; }
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
        .risk-level {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .risk-critical { background: #ffebee; color: #d32f2f; }
        .risk-high { background: #fff3e0; color: #f57c00; }
        .risk-medium { background: #fffde7; color: #f9a825; }
        .risk-low { background: #e3f2fd; color: #1976d2; }
        .action-btn {
            color: #015425;
            text-decoration: none;
            font-weight: bold;
            font-size: 11px;
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
            color: #d32f2f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $documentTitle ?? 'Loan Defaults Report' }}</h1>
        <h2>{{ $documentSubtitle ?? 'Analysis of overdue loans, default rates, and collection strategies' }}</h2>
        <div class="meta">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }} | 
            FeedTan Community Microfinance Group
        </div>
    </div>

    <!-- Default Overview -->
    <div class="section">
        <h3>Default Overview</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($defaultStats['total_overdue_loans']) }}</span>
                <span class="label">Overdue Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($defaultStats['total_overdue_amount'], 0) }} TZS</span>
                <span class="label">Overdue Amount</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($defaultStats['avg_overdue_days'], 0) }}</span>
                <span class="label">Avg Overdue Days</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($defaultStats['recovery_potential'], 0) }} TZS</span>
                <span class="label">Recovery Potential</span>
            </div>
        </div>
    </div>

    <!-- Risk Assessment -->
    <div class="section">
        <h3>Risk Assessment</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <h4 style="font-size: 14px; margin-bottom: 10px;">Default Severity Distribution</h4>
                <div class="risk-item critical">
                    <span class="label">Critical (>90 days)</span>
                    <span class="count">{{ $overdueLoans->where('maturity_date', '<', now()->subDays(90))->count() }} loans</span>
                </div>
                <div class="risk-item high">
                    <span class="label">High Risk (60-90 days)</span>
                    <span class="count">{{ $overdueLoans->where('maturity_date', '>=', now()->subDays(90))->where('maturity_date', '<', now()->subDays(60))->count() }} loans</span>
                </div>
                <div class="risk-item medium">
                    <span class="label">Medium Risk (30-60 days)</span>
                    <span class="count">{{ $overdueLoans->where('maturity_date', '>=', now()->subDays(60))->where('maturity_date', '<', now()->subDays(30))->count() }} loans</span>
                </div>
                <div class="risk-item low">
                    <span class="label">Low Risk (1-30 days)</span>
                    <span class="count">{{ $overdueLoans->where('maturity_date', '>=', now()->subDays(30))->where('maturity_date', '<', now())->count() }} loans</span>
                </div>
            </div>
            <div>
                <h4 style="font-size: 14px; margin-bottom: 10px;">Collection Priority</h4>
                <div class="priority-item high">
                    <div class="title">Immediate Action Required</div>
                    <div class="description">Loans overdue >90 days require immediate legal action</div>
                    <span class="badge badge-high">High Priority</span>
                </div>
                <div class="priority-item medium">
                    <div class="title">Intensive Follow-up</div>
                    <div class="description">Loans overdue 60-90 days need daily follow-up</div>
                    <span class="badge badge-medium">Medium Priority</span>
                </div>
                <div class="priority-item low">
                    <div class="title">Regular Monitoring</div>
                    <div class="description">Loans overdue <60 days need weekly monitoring</div>
                    <span class="badge badge-low">Low Priority</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Default Trend -->
    <div class="section">
        <h3>Monthly Default Trend ({{ date('Y') }})</h3>
        @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        @endphp
        @foreach($months as $index => $month)
            @php
                $monthData = $overdueByMonth->firstWhere('month', $index + 1);
                $count = $monthData->count ?? 0;
                $total = $monthData->total ?? 0;
            @endphp
            @if($count > 0)
                <div class="monthly-trend">
                    <span class="month">{{ $month }}</span>
                    <span class="data">
                        {{ $count }} overdue | {{ number_format($total / 1000, 1) }}K TZS
                    </span>
                    <div style="clear: both;"></div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Overdue Loans Details -->
    <div class="section">
        <h3>Overdue Loans Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Loan #</th>
                    <th>Member</th>
                    <th>Principal</th>
                    <th>Remaining</th>
                    <th>Maturity Date</th>
                    <th>Days Overdue</th>
                    <th>Risk Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overdueLoans as $loan)
                    @php
                        $daysOverdue = $loan->maturity_date ? now()->diffInDays($loan->maturity_date) : 0;
                        $riskLevel = $daysOverdue > 90 ? 'Critical' : ($daysOverdue > 60 ? 'High' : ($daysOverdue > 30 ? 'Medium' : 'Low'));
                        $riskClass = $daysOverdue > 90 ? 'critical' : ($daysOverdue > 60 ? 'high' : ($daysOverdue > 30 ? 'medium' : 'low'));
                    @endphp
                    <tr>
                        <td>{{ $loan->loan_number }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td class="text-right">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                        <td class="text-right">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                        <td>{{ $loan->maturity_date ? $loan->maturity_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="text-right" style="color: #d32f2f; font-weight: bold;">{{ $daysOverdue }} days</td>
                        <td>
                            <span class="risk-level risk-{{ $riskClass }}">
                                {{ $riskLevel }}
                            </span>
                        </td>
                        <td>
                            @if($daysOverdue > 90)
                                <span class="action-btn">Legal Action</span>
                            @elseif($daysOverdue > 60)
                                <span class="action-btn">Intensive Follow-up</span>
                            @else
                                <span class="action-btn">Send Reminder</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No overdue loans found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Loan Defaults Report</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>
