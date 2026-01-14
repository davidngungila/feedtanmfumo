@extends('layouts.admin')

@section('page-title', 'Welfare Reports')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welfare Reports</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive reports and analytics for welfare operations</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.welfare.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to Welfare
                </a>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.welfare.reports') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Contributions</p>
            <p class="text-2xl font-bold text-green-600">TZS {{ number_format($stats['total_contributions'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['total_contribution_records'] }} records</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Benefits</p>
            <p class="text-2xl font-bold text-red-600">TZS {{ number_format($stats['total_benefits'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['total_benefit_records'] }} records</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Net Balance</p>
            <p class="text-2xl font-bold {{ $stats['net_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                TZS {{ number_format($stats['net_balance'], 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">Fund balance</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-sm font-medium text-gray-600 mb-1">Avg Contribution</p>
            <p class="text-2xl font-bold text-purple-600">TZS {{ number_format($stats['average_contribution'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Per transaction</p>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Monthly Trends (Last 12 Months)</h2>
        <div class="space-y-4">
            @foreach($monthlyData as $month)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">{{ $month['month'] }}</span>
                        <span class="text-sm font-semibold {{ ($month['contributions'] - $month['benefits']) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Net: TZS {{ number_format($month['contributions'] - $month['benefits'], 2) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Contributions</span>
                                <span class="font-semibold text-green-600">TZS {{ number_format($month['contributions'], 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['total_contributions'] > 0 ? min(100, ($month['contributions'] / $stats['total_contributions'] * 100)) : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Benefits</span>
                                <span class="font-semibold text-red-600">TZS {{ number_format($month['benefits'], 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $stats['total_benefits'] > 0 ? min(100, ($month['benefits'] / $stats['total_benefits'] * 100)) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Benefit Type Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Benefit Type Breakdown</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($benefitTypes as $type)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ ucfirst($type->benefit_type ?? 'Other') }}</h4>
                    <p class="text-2xl font-bold text-blue-600 mb-1">TZS {{ number_format($type->total, 2) }}</p>
                    <p class="text-sm text-gray-600">{{ $type->count }} claims</p>
                    @if($stats['total_benefits'] > 0)
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($type->total / $stats['total_benefits'] * 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format(($type->total / $stats['total_benefits'] * 100), 1) }}% of total</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Status Breakdown</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($statusBreakdown as $status)
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">{{ ucfirst($status->status) }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $status->count }}</p>
                    <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($status->total, 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Beneficiaries -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Top 10 Beneficiaries</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Benefits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number of Claims</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topBeneficiaries as $index => $beneficiary)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-[#015425] text-white text-sm font-semibold rounded-full">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $beneficiary->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $beneficiary->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                TZS {{ number_format($beneficiary->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $beneficiary->count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                TZS {{ number_format($beneficiary->total / $beneficiary->count, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No beneficiaries found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Contributors -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Top 10 Contributors</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Contributions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Number of Contributions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topContributors as $index => $contributor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-[#015425] text-white text-sm font-semibold rounded-full">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $contributor->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $contributor->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                TZS {{ number_format($contributor->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contributor->count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                TZS {{ number_format($contributor->total / $contributor->count, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No contributors found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

