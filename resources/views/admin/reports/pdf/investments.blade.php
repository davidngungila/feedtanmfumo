@extends('pdf.base')

@section('content')
<div class="space-y-4">
    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Investment Portfolio Summary</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 50%;">Metric</th>
                    <th style="width: 50%;">Value</th>
                </tr>
                <tr>
                    <td>Total Investments</td>
                    <td>{{ number_format($stats['total']) }}</td>
                </tr>
                <tr>
                    <td>Total Principal Amount</td>
                    <td>{{ number_format($stats['total_principal'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Total Profit Share</td>
                    <td>{{ number_format($stats['total_profit'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Active Investments</td>
                    <td>{{ number_format($stats['active_count']) }} ({{ number_format($stats['active'], 2) }} TZS)</td>
                </tr>
                <tr>
                    <td>Matured Investments</td>
                    <td>{{ number_format($stats['matured_count']) }}</td>
                </tr>
                <tr>
                    <td>Average Principal Amount</td>
                    <td>{{ number_format($stats['avg_principal'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Average Interest Rate</td>
                    <td>{{ number_format($stats['avg_interest_rate'], 2) }}%</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Investments by Type -->
    @if($stats['by_type']->count() > 0)
    <div class="section">
        <div class="section-header">Investments by Plan Type</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Plan Type</th>
                    <th>Count</th>
                    <th>Total Principal (TZS)</th>
                </tr>
                @foreach($stats['by_type'] as $type)
                <tr>
                    <td>{{ ucfirst($type->plan_type) }}</td>
                    <td>{{ number_format($type->count) }}</td>
                    <td>{{ number_format($type->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Investments by Status -->
    @if($stats['by_status']->count() > 0)
    <div class="section">
        <div class="section-header">Investments by Status</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Principal (TZS)</th>
                </tr>
                @foreach($stats['by_status'] as $status)
                <tr>
                    <td>{{ ucfirst($status->status) }}</td>
                    <td>{{ number_format($status->count) }}</td>
                    <td>{{ number_format($status->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Investment Trends -->
    @if($monthlyInvestments->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Investment Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Principal (TZS)</th>
                </tr>
                @foreach($monthlyInvestments as $month)
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

    <!-- Investment Details -->
    @if($investments->count() > 0)
    <div class="section page-break">
        <div class="section-header">Investment Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Member</th>
                    <th>Investment ID</th>
                    <th>Plan Type</th>
                    <th>Principal</th>
                    <th>Interest Rate</th>
                    <th>Profit Share</th>
                    <th>Status</th>
                    <th>Maturity Date</th>
                </tr>
                @foreach($investments->take(100) as $investment)
                <tr>
                    <td>{{ $investment->user->name ?? 'N/A' }}</td>
                    <td>{{ $investment->investment_number ?? $investment->id }}</td>
                    <td>{{ ucfirst($investment->plan_type) }}</td>
                    <td>{{ number_format($investment->principal_amount, 2) }}</td>
                    <td>{{ number_format($investment->interest_rate, 2) }}%</td>
                    <td>{{ number_format($investment->profit_share, 2) }}</td>
                    <td>{{ ucfirst($investment->status) }}</td>
                    <td>{{ $investment->maturity_date ? \Carbon\Carbon::parse($investment->maturity_date)->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                @endforeach
            </table>
            @if($investments->count() > 100)
            <p style="margin-top: 10px; font-size: 8pt; color: #666; font-style: italic;">
                Showing first 100 investments. Total: {{ number_format($investments->count()) }} investments.
            </p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

