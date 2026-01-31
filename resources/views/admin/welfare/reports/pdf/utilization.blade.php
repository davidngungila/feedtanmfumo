@extends('pdf.base')

@section('title', 'Welfare Utilization Statistics Report')

@php
    $serialNumber = 'FCMGWUSR-' . date('Ymd') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT);
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
            <div class="stats-cell stats-label">Total Contributions</div>
            <div class="stats-cell">{{ number_format($stats['total_contributions'], 2) }} TZS</div>
            <div class="stats-cell stats-label">Total Benefits</div>
            <div class="stats-cell">{{ number_format($stats['total_benefits'], 2) }} TZS</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Utilization Rate</div>
            <div class="stats-cell">{{ number_format($stats['utilization_rate'], 2) }}%</div>
            <div class="stats-cell stats-label">Avg Utilization Rate</div>
            <div class="stats-cell">{{ number_format($stats['avg_utilization_rate'], 2) }}%</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Net Balance</div>
            <div class="stats-cell">{{ number_format($stats['net_balance'], 2) }} TZS</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-header">Utilization by Benefit Type</div>
    <table>
        <thead>
            <tr>
                <th>Benefit Type</th>
                <th style="text-align: right;">Count</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">% of Contributions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($benefitTypeUtilization as $type)
            <tr>
                <td>{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                <td style="text-align: right;">{{ number_format($type->count) }}</td>
                <td style="text-align: right;">{{ number_format($type->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($type->percentage, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Monthly Utilization Trend</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align: right;">Contributions</th>
                <th style="text-align: right;">Benefits</th>
                <th style="text-align: right;">Utilization Rate</th>
                <th style="text-align: right;">Net</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyUtilization as $month)
            <tr>
                <td>{{ $month['month'] }}</td>
                <td style="text-align: right;">{{ number_format($month['contributions'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($month['benefits'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($month['utilization_rate'], 2) }}%</td>
                <td style="text-align: right;">{{ number_format($month['net'], 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Peak Utilization Periods</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align: right;">Contributions</th>
                <th style="text-align: right;">Benefits</th>
                <th style="text-align: right;">Utilization Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peakPeriods as $period)
            <tr>
                <td>{{ $period['month'] }}</td>
                <td style="text-align: right;">{{ number_format($period['contributions'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($period['benefits'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($period['utilization_rate'], 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Utilization by Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: right;">Count</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">% of Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utilizationByStatus as $status)
            <tr>
                <td>{{ ucfirst($status->status) }}</td>
                <td style="text-align: right;">{{ number_format($status->count) }}</td>
                <td style="text-align: right;">{{ number_format($status->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ $stats['total_benefits'] > 0 ? number_format(($status->total / $stats['total_benefits']) * 100, 2) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

