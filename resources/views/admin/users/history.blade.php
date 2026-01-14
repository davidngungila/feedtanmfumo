@extends('layouts.admin')

@section('page-title', 'Member History')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Member History</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Activity timeline and history for all members</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.users.history') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="user_id" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <option value="">All Members</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            <select name="activity_type" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <option value="">All Activities</option>
                <option value="registration">Registration</option>
                <option value="loan">Loan</option>
                <option value="savings">Savings</option>
                <option value="investment">Investment</option>
                <option value="status_change">Status Change</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-lg font-semibold text-[#015425] mb-6">Activity Timeline</h2>
        
        <div class="space-y-6">
            <!-- Sample Activity Item -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-[#015425] flex items-center justify-center text-white font-semibold">
                        JD
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">John Doe registered as new member</p>
                            <p class="text-xs text-gray-500 mt-1">Account created and profile setup completed</p>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ now()->subDays(5)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Registration</span>
                        <span>Member ID: MEM-001234</span>
                    </div>
                </div>
            </div>

            <!-- More sample activities would be displayed here -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Loan application submitted</p>
                            <p class="text-xs text-gray-500 mt-1">Business loan application for TZS 5,000,000</p>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ now()->subDays(3)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Loan</span>
                        <span>Loan Number: LN-123456</span>
                    </div>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Savings account opened</p>
                            <p class="text-xs text-gray-500 mt-1">Emergency Savings Account activated</p>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ now()->subDays(2)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded">Savings</span>
                        <span>Account: SA-789012</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More / Pagination -->
        <div class="mt-6 text-center">
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                Load More Activities
            </button>
        </div>
    </div>
</div>
@endsection

