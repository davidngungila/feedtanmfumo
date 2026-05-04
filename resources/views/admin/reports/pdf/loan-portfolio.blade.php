<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle ?? 'Loan Portfolio Report' }}</title>
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
        .composition-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 8px;
            padding: 5px;
            background: #f5f5f5;
        }
        .composition-item .label { 
            font-weight: bold; 
        }
        .composition-item .percentage { 
            font-weight: bold; 
            color: #015425;
        }
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
        .progress-bar {
            width: 100%;
            height: 10px;
            background: #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #015425;
        }
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $documentTitle ?? 'Loan Portfolio Report' }}</h1>
        <h2>{{ $documentSubtitle ?? 'Comprehensive analysis of loan portfolio composition and risk exposure' }}</h2>
        <div class="meta">
            Generated: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }} | 
            FeedTan Community Microfinance Group
        </div>
    </div>

    <!-- Portfolio Overview -->
    <div class="section">
        <h3>Portfolio Overview</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['total_loans']) }}</span>
                <span class="label">Total Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['portfolio_value'], 0) }} TZS</span>
                <span class="label">Portfolio Value</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['active_loans']) }}</span>
                <span class="label">Active Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['risk_exposure'], 0) }} TZS</span>
                <span class="label">Risk Exposure</span>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['completed_loans']) }}</span>
                <span class="label">Completed Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['overdue_loans']) }}</span>
                <span class="label">Overdue Loans</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['total_principal'], 0) }} TZS</span>
                <span class="label">Total Principal</span>
            </div>
            <div class="stat-box">
                <span class="value">{{ number_format($portfolioStats['total_remaining'], 0) }} TZS</span>
                <span class="label">Total Remaining</span>
            </div>
        </div>
    </div>

    <!-- Portfolio Composition -->
    <div class="section">
        <h3>Portfolio Composition</h3>
        @php
            $totalLoans = $portfolioStats['total_loans'] > 0 ? $portfolioStats['total_loans'] : 1;
        @endphp
        <div class="composition-item">
            <span class="label">Active Loans</span>
            <span class="percentage">{{ number_format($portfolioStats['active_loans']) }} ({{ number_format(($portfolioStats['active_loans'] / $totalLoans) * 100, 1) }}%)</span>
        </div>
        <div class="composition-item">
            <span class="label">Completed Loans</span>
            <span class="percentage">{{ number_format($portfolioStats['completed_loans']) }} ({{ number_format(($portfolioStats['completed_loans'] / $totalLoans) * 100, 1) }}%)</span>
        </div>
        <div class="composition-item">
            <span class="label">Overdue Loans</span>
            <span class="percentage">{{ number_format($portfolioStats['overdue_loans']) }} ({{ number_format(($portfolioStats['overdue_loans'] / $totalLoans) * 100, 1) }}%)</span>
        </div>
    </div>

    <!-- Loan Portfolio Details -->
    <div class="section">
        <h3>Loan Portfolio Details</h3>
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
                    <th>Maturity Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    @php
                        $progress = $loan->principal_amount > 0 ? ($loan->paid_amount / $loan->principal_amount) * 100 : 0;
                    @endphp
                    <tr>
                        <td>{{ $loan->loan_number }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td class="text-right">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                        <td class="text-right">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                        <td class="text-right">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                        <td>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, $progress) }}%"></div>
                            </div>
                            <small>{{ number_format($progress, 1) }}%</small>
                        </td>
                        <td>
                            <span class="status-{{ $loan->status }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td>{{ $loan->maturity_date ? $loan->maturity_date->format('M d, Y') : 'N/A' }}</td>
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
        <p>FeedTan Community Microfinance Group - Loan Portfolio Report</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>
