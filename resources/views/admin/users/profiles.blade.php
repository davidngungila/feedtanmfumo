@extends('layouts.admin')

@section('page-title', 'Member Profiles')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Member Profiles</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Detailed profiles of all registered members with comprehensive information</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                + Register New Member
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.users.profiles') }}" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Search
            </button>
        </form>
    </div>

    <!-- Profiles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($users as $user)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] p-6 text-white">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                            <p class="text-sm text-white/80">{{ $user->email }}</p>
                            <p class="text-xs text-white/70 mt-1">Member #{{ $user->member_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="p-4 sm:p-6">
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600">{{ $user->phone ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600">{{ $user->city ?? 'N/A' }}, {{ $user->region ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-600">Joined {{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-[#015425]">{{ $user->loans_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Loans</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $user->savings_accounts_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Savings</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $user->investments_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600">Investments</p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="mt-4">
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
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="flex-1 px-3 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm">
                            View Profile
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No member profiles found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-white rounded-lg shadow-md p-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection

