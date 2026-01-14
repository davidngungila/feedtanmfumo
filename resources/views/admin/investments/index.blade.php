@extends('layouts.admin')

@section('page-title', 'Investment Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Investment Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage all investment plans and track performance</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.investments.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Investment
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Total Investments</p>
            <p class="text-2xl font-bold text-[#015425]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Matured</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['matured'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Total Principal</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_principal'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Total Profit</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_profit'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Investments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Investment #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Principal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maturity Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($investments as $investment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $investment->investment_number }}</td>
                            <td class="px-4 py-3 text-sm">{{ $investment->user->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $investment->plan_type_name }}</td>
                            <td class="px-4 py-3 text-sm">{{ number_format($investment->principal_amount, 0) }} TZS</td>
                            <td class="px-4 py-3 text-sm">{{ $investment->maturity_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $investment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($investment->status === 'matured' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($investment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm space-x-2">
                                <a href="{{ route('admin.investments.show', $investment) }}" class="text-[#015425] hover:underline">View</a>
                                <a href="{{ route('admin.investments.edit', $investment) }}" class="text-blue-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No investments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $investments->links() }}
        </div>
    </div>
</div>
@endsection

