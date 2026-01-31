@extends('pdf.base')

@section('title', 'Welfare Fund Status Report')

@php
    $serialNumber = 'FCMGWFSR-' . date('Ymd') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT);
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
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        background: white;
    }
    .info-table tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .info-table tr:last-child {
        border-bottom: none;
    }
    .info-table td {
        padding: 5px 8px;
        vertical-align: top;
        font-size: 8.5pt;
    }
    .info-table td:first-child {
        font-weight: 600;
        width: 35%;
        color: #374151;
        background: #f9fafb;
        border-right: 1px solid #e5e7eb;
    }
    .info-table td:last-child {
        color: #1a1a1a;
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
            <div class="stats-cell stats-label">Opening Balance</div>
            <div class="stats-cell">{{ number_format($stats['opening_balance'], 2) }} TZS</div>
            <div class="stats-cell stats-label">Total Contributions</div>
            <div class="stats-cell">{{ number_format($stats['total_contributions'], 2) }} TZS</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Benefits</div>
            <div class="stats-cell">{{ number_format($stats['total_benefits'], 2) }} TZS</div>
            <div class="stats-cell stats-label">Closing Balance</div>
            <div class="stats-cell">{{ number_format($stats['closing_balance'], 2) }} TZS</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-header">Monthly Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align: right;">Contributions</th>
                <th style="text-align: right;">Benefits</th>
                <th style="text-align: right;">Net</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $month)
            <tr>
                <td>{{ $month['month'] }}</td>
                <td style="text-align: right;">{{ number_format($month['contributions'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($month['benefits'], 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($month['contributions'] - $month['benefits'], 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Recent Contributions</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Member</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contributionRecords as $record)
            <tr>
                <td>{{ $record->transaction_date->format('M j, Y') }}</td>
                <td>{{ $record->user->name }}</td>
                <td style="text-align: right;">{{ number_format($record->amount, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Recent Benefits</div>
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
            @foreach($benefitRecords as $record)
            <tr>
                <td>{{ $record->transaction_date->format('M j, Y') }}</td>
                <td>{{ $record->user->name }}</td>
                <td>{{ $record->benefit_type_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($record->status) }}</td>
                <td style="text-align: right;">{{ number_format($record->amount, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

