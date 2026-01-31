@extends('layouts.admin')

@section('page-title', 'Welfare Fund Status Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Fund Status Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive welfare fund status and financial overview</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.welfare.reports.fund-status.pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
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
        <form method="GET" action="{{ route('admin.welfare.reports.fund-status') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <p class="text-sm font-medium text-gray-600 mb-1">Opening Balance</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['opening_balance'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Balance at start of period</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Contributions</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_contributions'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['contribution_count']) }} records</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Benefits</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_benefits'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['benefit_count']) }} records</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Closing Balance</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['closing_balance'], 2) }} TZS</p>
            <p class="text-xs text-gray-500 mt-1">Net: {{ number_format($stats['net_balance'], 2) }} TZS</p>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Monthly Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Contributions</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Benefits</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Net</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyData as $month)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $month['month'] }}</td>
                        <td class="px-4 py-3 text-sm text-right text-green-600">{{ number_format($month['contributions'], 2) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-right text-red-600">{{ number_format($month['benefits'], 2) }} TZS</td>
                        <td class="px-4 py-3 text-sm text-right font-semibold {{ ($month['contributions'] - $month['benefits']) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($month['contributions'] - $month['benefits'], 2) }} TZS
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Contribution Records -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Contributions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Member</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contributionRecords as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record->transaction_date->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record->user->name }}</td>
                        <td class="px-4 py-3 text-sm text-right text-green-600 font-semibold">{{ number_format($record->amount, 2) }} TZS</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-sm text-center text-gray-500">No contributions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $contributionRecords->links() }}
        </div>
    </div>

    <!-- Benefit Records -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Recent Benefits</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#015425] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($benefitRecords as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record->transaction_date->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record->user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $record->benefit_type_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $record->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $record->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-right text-red-600 font-semibold">{{ number_format($record->amount, 2) }} TZS</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">No benefits found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $benefitRecords->links() }}
        </div>
    </div>
</div>
@endsection

