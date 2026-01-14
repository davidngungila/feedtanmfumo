@extends('layouts.admin')

@section('page-title', 'Loan Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage all loan applications and track their status</p>
            </div>
            <a href="{{ route('admin.loans.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Loan Application
            </a>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Loans</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total']) }}</p>
            <p class="text-xs text-gray-500 mt-1">All time</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Pending</p>
            <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ number_format($stats['pending']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Awaiting approval</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Active</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ number_format($stats['active']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Currently active</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Overdue</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['overdue']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Requires attention</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Outstanding</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($stats['total_amount'] / 1000, 1) }}K</p>
            <p class="text-xs text-gray-500 mt-1">TZS</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search by loan number, member name..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="sm:w-48">
                    <select id="status-filter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="active">Active</option>
                        <option value="overdue">Overdue</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </div>
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $loan->loan_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $loan->application_date->format('M d, Y') }}</p>
                                    @if($loan->maturity_date)
                                    <p class="text-xs text-gray-400 mt-1">
                                        <span class="inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $loan->maturity_date->format('M d, Y') }}
                                        </span>
                                    </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $loan->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ number_format($loan->principal_amount, 0) }} TZS</p>
                                    <p class="text-xs text-gray-500">{{ $loan->interest_rate }}% / {{ $loan->term_months }}m</p>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $loan->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($loan->status === 'approved' ? 'bg-indigo-100 text-indigo-800' :
                                    ($loan->status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                    ($loan->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                    ($loan->status === 'rejected' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-800')))))
                                }}">
                                    {{ strtoupper($loan->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($loan->total_amount > 0)
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min(($loan->paid_amount / $loan->total_amount) * 100, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">{{ round(($loan->paid_amount / $loan->total_amount) * 100, 0) }}%</span>
                                </div>
                                @else
                                <span class="text-xs text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-semibold {{ $loan->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($loan->remaining_amount, 0) }} TZS
                                    </p>
                                    <p class="text-xs text-gray-500">of {{ number_format($loan->total_amount, 0) }} TZS</p>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.loans.show', $loan) }}" 
                                       class="text-[#015425] hover:text-[#013019] transition" 
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.loans.edit', $loan) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition" 
                                       title="Edit Loan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($loan->status === 'pending')
                                    <button class="text-green-600 hover:text-green-800 transition" title="Approve">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium">No loans found</p>
                                <p class="text-sm text-gray-400 mt-1">Get started by creating a new loan application</p>
                                <a href="{{ route('admin.loans.create') }}" class="inline-block mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                                    Create New Loan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($loans->hasPages())
        <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $loans->firstItem() }}</span> to <span class="font-medium">{{ $loans->lastItem() }}</span> of <span class="font-medium">{{ $loans->total() }}</span> loans
                </div>
                <div>
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Recent Loans</h3>
            <div class="space-y-3">
                @foreach($loans->take(5) as $recentLoan)
                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded transition">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $recentLoan->loan_number }}</p>
                        <p class="text-xs text-gray-500">{{ $recentLoan->user->name }}</p>
                    </div>
                    <span class="text-xs font-semibold {{ 
                        $recentLoan->status === 'active' ? 'text-green-600' : 
                        ($recentLoan->status === 'pending' ? 'text-yellow-600' : 'text-gray-600')
                    }}">
                        {{ ucfirst($recentLoan->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Status Breakdown</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-700">Pending</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-700">Active</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['active'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-700">Overdue</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ $stats['overdue'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-700">Completed</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ \App\Models\Loan::where('status', 'completed')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.loans.create') }}" class="block w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center font-medium">
                    Create New Loan
                </a>
                <a href="{{ route('admin.reports.loans') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-center font-medium">
                    View Reports
                </a>
                <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                    Export Data
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    
    // Simple client-side filtering (can be enhanced with AJAX)
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statusBadge = row.querySelector('span');
            const status = statusBadge ? statusBadge.textContent.toLowerCase() : '';
            
            const matchesSearch = !searchTerm || text.includes(searchTerm);
            const matchesStatus = !statusValue || status.includes(statusValue);
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
