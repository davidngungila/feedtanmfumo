@extends('layouts.member')

@section('page-title', 'My Savings')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">My Savings Accounts</h1>
        <a href="{{ route('member.savings.create') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm sm:text-base">
            Open New Account
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Accounts</p>
            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['total_accounts'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Balance</p>
            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_balance'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Active Accounts</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $accounts->where('status', 'active')->count() }}</p>
        </div>
    </div>

    <!-- Accounts Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($accounts as $account)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ $account->account_number }}</p>
                        <p class="text-lg font-bold text-[#015425]">{{ $account->account_type_name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ 
                        $account->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    }}">
                        {{ ucfirst($account->status) }}
                    </span>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Balance</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($account->balance, 0) }} TZS</p>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                    <span>Interest: {{ $account->interest_rate }}%</span>
                    <span>{{ $account->opening_date->format('M Y') }}</span>
                </div>
                <a href="{{ route('member.savings.show', $account->id) }}" class="block w-full text-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    View Details
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
                <p class="text-gray-500 mb-4">No savings accounts found.</p>
                <a href="{{ route('member.savings.create') }}" class="text-[#015425] hover:underline">Open your first savings account</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

