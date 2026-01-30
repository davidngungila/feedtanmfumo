@extends('pdf.base')

@section('content')
<div class="space-y-4">
    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Financial Summary</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 50%;">Metric</th>
                    <th style="width: 50%;">Amount (TZS)</th>
                </tr>
                <tr>
                    <td>Outstanding Loans</td>
                    <td>{{ number_format($stats['total_loans'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Savings</td>
                    <td>{{ number_format($stats['total_savings'], 2) }}</td>
                </tr>
                <tr>
                    <td>Active Investments</td>
                    <td>{{ number_format($stats['total_investments'], 2) }}</td>
                </tr>
                <tr>
                    <td>Welfare Fund</td>
                    <td>{{ number_format($stats['total_welfare_fund'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Loan Principal</td>
                    <td>{{ number_format($stats['total_principal'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Paid</td>
                    <td>{{ number_format($stats['total_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Revenue</td>
                    <td>{{ number_format($stats['total_revenue'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Members</td>
                    <td>{{ number_format($stats['total_members']) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Loan Statistics by Status -->
    @if($loanStats->count() > 0)
    <div class="section">
        <div class="section-header">Loan Statistics by Status</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($loanStats as $stat)
                <tr>
                    <td>{{ ucfirst($stat->status) }}</td>
                    <td>{{ number_format($stat->count) }}</td>
                    <td>{{ number_format($stat->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Savings Statistics by Type -->
    @if($savingsStats->count() > 0)
    <div class="section">
        <div class="section-header">Savings Statistics by Account Type</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Account Type</th>
                    <th>Count</th>
                    <th>Total Balance (TZS)</th>
                </tr>
                @foreach($savingsStats as $stat)
                <tr>
                    <td>{{ ucfirst($stat->account_type) }}</td>
                    <td>{{ number_format($stat->count) }}</td>
                    <td>{{ number_format($stat->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Loan Trends -->
    @if($monthlyLoans->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Loan Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($monthlyLoans as $month)
                <tr>
                    <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                    <td>{{ number_format($month->count) }}</td>
                    <td>{{ number_format($month->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Savings Trends -->
    @if($monthlySavings->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Savings Deposits ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Total Deposits (TZS)</th>
                </tr>
                @foreach($monthlySavings as $month)
                <tr>
                    <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                    <td>{{ number_format($month->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

