@extends('layouts.admin')

@section('page-title', 'Member Directory')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Member Directory</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive directory of all registered members with detailed information</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                + Register New Member
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Members</p>
                    <p class="text-2xl font-bold text-[#015425] mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#015425]/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Members</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Inactive Members</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <form method="GET" action="{{ route('admin.users.directory') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, member number..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <!-- Group Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group</label>
                    <select name="group" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">All Groups</option>
                        <!-- Add dynamic groups here -->
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('admin.users.directory') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Clear Filters
                </a>
                <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loans</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Savings</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->member_number ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $user->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'suspended' => 'bg-red-100 text-red-800',
                                    ];
                                    $status = $user->status ?? 'pending';
                                    $color = $statusColors[$status] ?? $statusColors['pending'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->loans_count ?? 0 }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->savings_accounts_count ?? 0 }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="openModal('user-modal-{{ $user->id }}')" class="text-[#015425] hover:text-[#013019]" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No members found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-4 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- User Detail Modals -->
@foreach($users as $user)
    <x-modal id="user-modal-{{ $user->id }}" title="Member Details - {{ $user->name }}">
        <div class="space-y-6">
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Member Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->member_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    @if($user->alternate_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Alternate Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->alternate_phone }}</p>
                        </div>
                    @endif
                    @if($user->date_of_birth)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->date_of_birth->format('M d, Y') }}</p>
                        </div>
                    @endif
                    @if($user->gender)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Gender</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($user->gender) }}</p>
                        </div>
                    @endif
                    @if($user->national_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">National ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->national_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Address Information -->
            @if($user->address || $user->city || $user->region)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($user->address)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Street Address</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->address }}</p>
                            </div>
                        @endif
                        @if($user->city)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">City</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->city }}</p>
                            </div>
                        @endif
                        @if($user->region)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Region</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->region }}</p>
                            </div>
                        @endif
                        @if($user->postal_code)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Postal Code</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->postal_code }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Employment Information -->
            @if($user->occupation || $user->employer || $user->monthly_income)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($user->occupation)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Occupation</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->occupation }}</p>
                            </div>
                        @endif
                        @if($user->employer)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Employer</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->employer }}</p>
                            </div>
                        @endif
                        @if($user->monthly_income)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Monthly Income</label>
                                <p class="mt-1 text-sm text-gray-900">TZS {{ number_format($user->monthly_income, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Account Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Account Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-gray-100 text-gray-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'suspended' => 'bg-red-100 text-red-800',
                            ];
                            $status = $user->status ?? 'pending';
                            $color = $statusColors[$status] ?? $statusColors['pending'];
                        @endphp
                        <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">KYC Status</label>
                        @php
                            $kycColors = [
                                'verified' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'expired' => 'bg-orange-100 text-orange-800',
                            ];
                            $kycStatus = $user->kyc_status ?? 'pending';
                            $kycColor = $kycColors[$kycStatus] ?? $kycColors['pending'];
                        @endphp
                        <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $kycColor }}">
                            {{ ucfirst($kycStatus) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Member Since</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    @if($user->status_changed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status Changed</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->status_changed_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Financial Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-blue-600">Total Loans</label>
                        <p class="mt-1 text-2xl font-bold text-blue-900">{{ $user->loans_count ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-green-600">Savings Accounts</label>
                        <p class="mt-1 text-2xl font-bold text-green-900">{{ $user->savings_accounts_count ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-purple-600">Investments</label>
                        <p class="mt-1 text-2xl font-bold text-purple-900">{{ $user->investments_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Edit Member
                </a>
                <button type="button" onclick="closeModal('user-modal-{{ $user->id }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Close
                </button>
            </div>
        </x-slot>
    </x-modal>
@endforeach
@endsection

