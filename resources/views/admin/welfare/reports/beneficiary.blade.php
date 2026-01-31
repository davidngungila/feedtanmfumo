@extends('layouts.admin')

@section('page-title', 'Welfare Beneficiary Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Beneficiary Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive beneficiary statistics and analysis</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.welfare.reports.beneficiary.pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.welfare.reports') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.welfare.reports.beneficiary') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Beneficiaries</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_beneficiaries']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Unique beneficiaries</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Benefits</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_benefits'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Total amount disbursed</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Avg per Beneficiary</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['avg_benefit_per_beneficiary'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Average benefit amount</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Claims</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_claims']) }}</p>
            <p class="text-xs text-gray-500 mt-1">All benefit claims</p>
        </div>
    </div>

    <!-- Top Beneficiaries by Amount -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Top Beneficiaries by Amount</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Member</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Total Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Claims</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topBeneficiaries as $index => $beneficiary)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $beneficiary->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-right text-green-600 font-semibold">{{ number_format($beneficiary->total, 2) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($beneficiary->count) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Beneficiary Distribution by Type -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Distribution by Benefit Type</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#015425] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase">Beneficiaries</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($beneficiaryByType as $type)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $type->benefit_type_name ?? ucfirst($type->benefit_type) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($type->beneficiary_count) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($type->total, 2) }} TZS</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Distribution by Status</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#015425] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase">Beneficiaries</th>
                            <th class="px-4 py-3 text-right text-xs font-medium uppercase">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($beneficiaryByStatus as $status)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ ucfirst($status->status) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($status->beneficiary_count) }}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($status->total, 2) }} TZS</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Beneficiary Trend -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Monthly Beneficiary Trend</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Beneficiaries</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyBeneficiaries as $month)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $month['month'] }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($month['count']) }}</td>
                        <td class="px-4 py-3 text-sm text-right text-green-600 font-semibold">{{ number_format($month['total_amount'], 2) }} TZS</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Beneficiaries -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">All Beneficiaries</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Member</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Total Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Claims</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">First Benefit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Last Benefit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($beneficiaries as $beneficiary)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $beneficiary->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-right text-green-600 font-semibold">{{ number_format($beneficiary->total, 2) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900">{{ number_format($beneficiary->count) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($beneficiary->first_benefit)->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($beneficiary->last_benefit)->format('M j, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">No beneficiaries found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $beneficiaries->links() }}
        </div>
    </div>
</div>
@endsection

