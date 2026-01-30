@extends('pdf.base')

@section('content')
<div class="space-y-4">
    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Savings Portfolio Summary</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 50%;">Metric</th>
                    <th style="width: 50%;">Value</th>
                </tr>
                <tr>
                    <td>Total Accounts</td>
                    <td>{{ number_format($stats['total_accounts']) }}</td>
                </tr>
                <tr>
                    <td>Total Balance</td>
                    <td>{{ number_format($stats['total_balance'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Active Accounts</td>
                    <td>{{ number_format($stats['active_accounts']) }}</td>
                </tr>
                <tr>
                    <td>Average Balance</td>
                    <td>{{ number_format($stats['avg_balance'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Total Deposits</td>
                    <td>{{ number_format($stats['total_deposits'], 2) }} TZS</td>
                </tr>
                <tr>
                    <td>Total Withdrawals</td>
                    <td>{{ number_format($stats['total_withdrawals'], 2) }} TZS</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Savings by Type -->
    @if($stats['by_type']->count() > 0)
    <div class="section">
        <div class="section-header">Savings by Account Type</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Account Type</th>
                    <th>Count</th>
                    <th>Total Balance (TZS)</th>
                </tr>
                @foreach($stats['by_type'] as $type)
                <tr>
                    <td>{{ ucfirst($type->account_type) }}</td>
                    <td>{{ number_format($type->count) }}</td>
                    <td>{{ number_format($type->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Savings by Status -->
    @if($stats['by_status']->count() > 0)
    <div class="section">
        <div class="section-header">Savings by Status</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Total Balance (TZS)</th>
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

    <!-- Monthly Deposits -->
    @if($monthlyDeposits->count() > 0)
    <div class="section">
        <div class="section-header">Monthly Savings Deposits ({{ date('Y') }})</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Month</th>
                    <th>Total Deposits (TZS)</th>
                </tr>
                @foreach($monthlyDeposits as $month)
                <tr>
                    <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                    <td>{{ number_format($month->total, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif

    <!-- Account Details -->
    @if($accounts->count() > 0)
    <div class="section page-break">
        <div class="section-header">Savings Account Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th>Member</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>Balance (TZS)</th>
                    <th>Status</th>
                    <th>Created Date</th>
                </tr>
                @foreach($accounts->take(100) as $account)
                <tr>
                    <td>{{ $account->user->name ?? 'N/A' }}</td>
                    <td>{{ $account->account_number ?? $account->id }}</td>
                    <td>{{ ucfirst($account->account_type) }}</td>
                    <td>{{ number_format($account->balance, 2) }}</td>
                    <td>{{ ucfirst($account->status) }}</td>
                    <td>{{ $account->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </table>
            @if($accounts->count() > 100)
            <p style="margin-top: 10px; font-size: 8pt; color: #666; font-style: italic;">
                Showing first 100 accounts. Total: {{ number_format($accounts->count()) }} accounts.
            </p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

