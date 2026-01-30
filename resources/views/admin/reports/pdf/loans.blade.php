@extends('pdf.base')

@section('content')
<div class="space-y-4">
    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Loan Portfolio Summary</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 50%;">Metric</th>
                    <th style="width: 50%;">Value</th>
                </tr>
                <tr>
                    <td>Total Loans</td>
                    <td>{{ number_format($stats['total']) }}</td>
                </tr>
                <tr>
                    <td>Total Loan Amount</td>
                    <td>{{ number_format($stats['total_amount'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Total Paid Amount</td>
                    <td>{{ number_format($stats['paid_amount'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Remaining Amount</td>
                    <td>{{ number_format($stats['remaining_amount'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Active Loans</td>
                    <td>{{ number_format($stats['active_count']) }}</td>
                </tr>
                <tr>
                    <td>Pending Loans</td>
                    <td>{{ number_format($stats['pending_count']) }}</td>
                </tr>
                <tr>
                    <td>Completed Loans</td>
                    <td>{{ number_format($stats['completed_count']) }}</td>
                </tr>
                <tr>
                    <td>Overdue Loans</td>
                    <td>{{ number_format($stats['overdue_count']) }}</td>
                </tr>
                <tr>
                    <td>Average Loan Amount</td>
                    <td>{{ number_format($stats['avg_loan_amount'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Recovery Rate</td>
                    <td>{{ number_format($stats['recovery_rate'], 2) }}%</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Loans by Status -->
    @if($loansByStatus->count() > 0)
    <div class="section">
        <div class="section-header">Loans by Status</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($loansByStatus as $stat)
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

    <!-- Monthly Loan Trends -->
    @if($loansByMonth->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Loan Trends ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Count</th>
                    <th>Total Amount (TZS)</th>
                </tr>
                @foreach($loansByMonth as $month)
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

    <!-- Loan Details -->
    @if($loans->count() > 0)
    <div class="section page-break">
        <div class="section-header">Loan Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Member</th>
                    <th>Loan ID</th>
                    <th>Principal</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Maturity Date</th>
                </tr>
                @foreach($loans->take(100) as $loan)
                <tr>
                    <td>{{ $loan->user->name ?? 'N/A' }}</td>
                    <td>{{ $loan->loan_number ?? $loan->id }}</td>
                    <td>{{ number_format($loan->principal_amount, 2) }}</td>
                    <td>{{ number_format($loan->paid_amount, 2) }}</td>
                    <td>{{ number_format($loan->remaining_amount, 2) }}</td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td>{{ $loan->maturity_date ? \Carbon\Carbon::parse($loan->maturity_date)->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                @endforeach
            </table>
            @if($loans->count() > 100)
            <p style="margin-top: 10px; font-size: 8pt; color: #666; font-style: italic;">
                Showing first 100 loans. Total: {{ number_format($loans->count()) }} loans.
            </p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

