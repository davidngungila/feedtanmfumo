@extends('pdf.base')

@section('title', 'Welfare Beneficiary Report')

@php
    $serialNumber = 'FCMGWBR-' . date('Ymd') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT);
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
            <div class="stats-cell stats-label">Total Beneficiaries</div>
            <div class="stats-cell">{{ number_format($stats['total_beneficiaries']) }}</div>
            <div class="stats-cell stats-label">Total Benefits</div>
            <div class="stats-cell">{{ number_format($stats['total_benefits'], 2) }} TZS</div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Avg per Beneficiary</div>
            <div class="stats-cell">{{ number_format($stats['avg_benefit_per_beneficiary'], 2) }} TZS</div>
            <div class="stats-cell stats-label">Total Claims</div>
            <div class="stats-cell">{{ number_format($stats['total_claims']) }}</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-header">Top Beneficiaries by Amount</div>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Member</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">Claims</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topBeneficiaries as $index => $beneficiary)
            <tr>
                <td>#{{ $index + 1 }}</td>
                <td>{{ $beneficiary->user->name ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($beneficiary->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($beneficiary->count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Distribution by Benefit Type</div>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th style="text-align: right;">Beneficiaries</th>
                <th style="text-align: right;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beneficiaryByType as $type)
            <tr>
                <td>{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                <td style="text-align: right;">{{ number_format($type->beneficiary_count) }}</td>
                <td style="text-align: right;">{{ number_format($type->total, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Distribution by Status</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: right;">Beneficiaries</th>
                <th style="text-align: right;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beneficiaryByStatus as $status)
            <tr>
                <td>{{ ucfirst($status->status) }}</td>
                <td style="text-align: right;">{{ number_format($status->beneficiary_count) }}</td>
                <td style="text-align: right;">{{ number_format($status->total, 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">Monthly Beneficiary Trend</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align: right;">Beneficiaries</th>
                <th style="text-align: right;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyBeneficiaries as $month)
            <tr>
                <td>{{ $month['month'] }}</td>
                <td style="text-align: right;">{{ number_format($month['count']) }}</td>
                <td style="text-align: right;">{{ number_format($month['total_amount'], 2) }} TZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-header">All Beneficiaries</div>
    <table>
        <thead>
            <tr>
                <th>Member</th>
                <th style="text-align: right;">Total Amount</th>
                <th style="text-align: right;">Claims</th>
                <th>First Benefit</th>
                <th>Last Benefit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beneficiaries as $beneficiary)
            <tr>
                <td>{{ $beneficiary->user->name ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($beneficiary->total, 2) }} TZS</td>
                <td style="text-align: right;">{{ number_format($beneficiary->count) }}</td>
                <td>{{ \Carbon\Carbon::parse($beneficiary->first_benefit)->format('M j, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($beneficiary->last_benefit)->format('M j, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

