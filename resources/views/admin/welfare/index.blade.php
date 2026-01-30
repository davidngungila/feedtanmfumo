@extends('layouts.admin')

@section('page-title', 'Social Welfare Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">All Welfare</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive welfare management system for contributions and benefits</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.welfare.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Record
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.welfare.fund-management') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Fund Management</h3>
                    <p class="text-sm text-gray-600">Manage welfare fund balance and transactions</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.welfare.services') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Welfare Services</h3>
                    <p class="text-sm text-gray-600">View available services and eligibility</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.welfare.claims-processing') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Claims Processing</h3>
                    <p class="text-sm text-gray-600">Process and approve benefit claims</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.welfare.reports') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Welfare Reports</h3>
                    <p class="text-sm text-gray-600">Comprehensive reports and analytics</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 mb-1">Total Contributions</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_contributions'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-600 mb-1">Total Benefits</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_benefits'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Records</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 mb-1">Approved</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Records</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-600 mb-1">Total Records</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_records'] }}</p>
            <p class="text-xs text-gray-500 mt-1">All Types</p>
        </div>
    </div>

    <!-- Benefit Type Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Medical Support</p>
            <p class="text-xl font-bold text-blue-600">{{ number_format($stats['medical'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Funeral Support</p>
            <p class="text-xl font-bold text-purple-600">{{ number_format($stats['funeral'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <p class="text-sm text-gray-600">Educational Support</p>
            <p class="text-xl font-bold text-green-600">{{ number_format($stats['educational'], 0) }} TZS</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.welfare.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Welfare #, Name, Email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Types</option>
                    <option value="contribution" {{ request('type') === 'contribution' ? 'selected' : '' }}>Contribution</option>
                    <option value="benefit" {{ request('type') === 'benefit' ? 'selected' : '' }}>Benefit</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="disbursed" {{ request('status') === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Type</label>
                <select name="benefit_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">All Types</option>
                    <option value="medical" {{ request('benefit_type') === 'medical' ? 'selected' : '' }}>Medical</option>
                    <option value="funeral" {{ request('benefit_type') === 'funeral' ? 'selected' : '' }}>Funeral</option>
                    <option value="educational" {{ request('benefit_type') === 'educational' ? 'selected' : '' }}>Educational</option>
                    <option value="other" {{ request('benefit_type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Welfare Records Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Welfare #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Benefit Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($welfares as $welfare)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $welfare->welfare_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $welfare->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $welfare->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                    $welfare->type === 'contribution' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                                }}">
                                    {{ ucfirst($welfare->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $welfare->benefit_type_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold {{ $welfare->type === 'contribution' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($welfare->amount, 0) }} TZS
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $welfare->transaction_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                    $welfare->status === 'approved' || $welfare->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                    ($welfare->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($welfare->status === 'disbursed' ? 'bg-blue-100 text-blue-800' :
                                    ($welfare->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))
                                }}">
                                    {{ ucfirst($welfare->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.welfare.show', $welfare) }}" class="inline-flex items-center px-3 py-1.5 text-[#015425] hover:text-[#013019] font-medium rounded-md hover:bg-green-50 transition">
                                        View
                                    </a>
                                    <a href="{{ route('admin.welfare.edit', $welfare) }}" class="inline-flex items-center px-3 py-1.5 text-blue-600 hover:text-blue-800 font-medium rounded-md hover:bg-blue-50 transition">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.welfare.pdf', $welfare) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-[#015425] text-white text-xs font-medium rounded-md hover:bg-[#027a3a] transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-gray-400 mb-2">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p>No welfare records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $welfares->links() }}
        </div>
    </div>
</div>
@endsection
