@extends('layouts.member')

@section('page-title', 'My Loans')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">My Loans</h1>
        <a href="{{ route('member.loans.create') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm sm:text-base">
            Apply for Loan
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Active Loans</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Amount</p>
            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ number_format($stats['total_amount'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Remaining</p>
            <p class="text-lg sm:text-2xl font-bold text-red-600">{{ number_format($stats['remaining_amount'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan #</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Amount</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Paid</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Remaining</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">
                                <div class="font-semibold">{{ $loan->loan_number }}</div>
                                <div class="text-gray-600 sm:hidden mt-1">{{ number_format($loan->principal_amount, 0) }} TZS</div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden sm:table-cell">{{ number_format($loan->principal_amount, 0) }} TZS</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-green-600 hidden md:table-cell">{{ number_format($loan->paid_amount, 0) }} TZS</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-red-600 hidden md:table-cell">{{ number_format($loan->remaining_amount, 0) }} TZS</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">{{ $loan->application_date->format('M d, Y') }}</td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                <a href="{{ route('member.loans.show', $loan) }}" class="text-[#015425] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 sm:px-6 py-8 text-center text-gray-500 text-sm">
                                <p>No loans found. <a href="{{ route('member.loans.create') }}" class="text-[#015425] hover:underline">Apply for a loan</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

