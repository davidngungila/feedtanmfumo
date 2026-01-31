@extends('pdf.base')

@section('title', 'Welfare Claim Analysis Report')

@php
    $serialNumber = 'FCMGWCAR-' . date('Ymd') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT);
@endphp

@push('styles')
<style>
    .stats {
        display: table;
        width: 100%;
        margin: 15px 0;
        border-collapse: collapse;
    }
    .stats-row {
        display: table-row;
    }
    .stats-cell {
        display: table-cell;
        padding: 8px;
        border: 1px solid #ddd;
        background: #f9f9f9;
        font-size: 8pt;
    }
    .stats-label {
        font-weight: bold;
        color: #015425;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
        font-size: 8pt;
    }
    th {
        background: #015425;
        color: white;
        padding: 8px 6px;
        text-align: left;
        font-weight: bold;
        border: 1px solid #015425;
    }
    td {
        padding: 6px;
        border: 1px solid #ddd;
        vertical-align: top;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    .section {
        margin: 15px 0;
        page-break-inside: avoid;
    }
    .section-header {
        background: #015425;
        color: white;
        padding: 8px 12px;
        font-weight: bold;
        font-size: 10pt;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="text-center mb-10" style="text-align: center; margin-bottom: 10px; font-family: 'Courier New', monospace; font-size: 8pt; color: #666;">
    Serial No: {{ $serialNumber }}
</div>

<div class="section">
    <div class="section-header">Report Summary</div>
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Claims</div>
            <div class="stats-cell">{{ number_format($stats['total_claims']) }}</div>
            <div class="stats-cell stats-label">Approval Rate</div>
            <div class="stats-cell">{{ number_format($stats['approval_rate'], 1) }}%</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Approved</div>
            <div class="stats-cell">{{ number_format($stats['approved_count']) }}</div>
            <div class="stats-cell stats-label">Avg Processing Days</div>
            <div class="stats-cell">{{ number_format($stats['avg_processing_days'], 1) }} days</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Rejected</div>
            <div class="stats-cell">{{ number_format($stats['rejected_count']) }}</div>
            <div class="stats-cell stats-label">Total Amount</div>
            <div class="stats-cell">{{ number_format($stats['total_amount'], 2) }} TZS</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-header">Status Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: right;">Count</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusBreakdown as $status)
            <tr>
                <td>{{ ucfirst($status->status) }}</td>
                <td style="text-align: right;">{{ number_format($status->count) }}</td>
                <td style="text-align: right;">{{ number_format($status->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ $stats['total_claims'] > 0 ? number_format(($status->count / $stats['total_claims']) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Benefit Type Analysis</div>
    <table>
        <thead>
            <tr>
                <th>Benefit Type</th>
                <th style="text-align: right;">Count</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">Average</th>
            </tr>
        </thead>
        <tbody>
            @foreach($benefitTypeBreakdown as $type)
            <tr>
                <td>{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                <td style="text-align: right;">{{ number_format($type->count) }}</td>
                <td style="text-align: right;">{{ number_format($type->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($type->average, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Monthly Claims Trend</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: right;">Approved</th>
                <th style="text-align: right;">Rejected</th>
                <th style="text-align: right;">Pending</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyClaims as $month)
            <tr>
                <td>{{ $month['month'] }}</td>
                <td style="text-align: right;">{{ number_format($month['total']) }}</td>
                <td style="text-align: right;">{{ number_format($month['approved']) }}</td>
                <td style="text-align: right;">{{ number_format($month['rejected']) }}</td>
                <td style="text-align: right;">{{ number_format($month['pending']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Recent Claims</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Member</th>
                <th>Type</th>
                <th>Status</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentClaims as $claim)
            <tr>
                <td>{{ $claim->transaction_date->format('M j, Y') }}</td>
                <td>{{ $claim->user->name }}</td>
                <td>{{ $claim->benefit_type_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($claim->status) }}</td>
                <td style="text-align: right;">{{ number_format($claim->amount, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

