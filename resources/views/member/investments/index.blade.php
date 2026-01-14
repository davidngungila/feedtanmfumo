@extends('layouts.member')

@section('page-title', 'My Investments')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">My Investments</h1>
        <a href="{{ route('member.investments.create') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm sm:text-base">
            Start Investment
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Investments</p>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Active</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Principal</p>
            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ number_format($stats['total_principal'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Profit</p>
            <p class="text-lg sm:text-2xl font-bold text-purple-600">{{ number_format($stats['total_profit'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Investments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Investment #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Principal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected Return</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($investments as $investment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $investment->investment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $investment->plan_type_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($investment->principal_amount, 0) }} TZS</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ number_format($investment->expected_return, 0) }} TZS</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600">{{ number_format($investment->profit_share, 0) }} TZS</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $investment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($investment->status === 'matured' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($investment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('member.investments.show', $investment) }}" class="text-[#015425] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <p>No investments found. <a href="{{ route('member.investments.create') }}" class="text-[#015425] hover:underline">Start an investment</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

