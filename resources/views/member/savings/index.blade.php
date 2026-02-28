@extends('layouts.member')

@section('page-title', 'My Savings Portfolio')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">My Savings Portfolio</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage your accounts, track balances, and access your deposit statements.</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('l, F d, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ number_format($stats['total_balance'] ?? 0, 0) }} Total Balance</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('member.savings.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Account
                </a>
                <a href="{{ route('member.monthly-deposits.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 text-white rounded-md border border-white/20 hover:bg-white/20 transition font-medium">
                    View Statements
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div id="quick-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Accounts</p>
                    <p class="text-2xl sm:text-3xl font-bold text-[#015425]">{{ number_format($stats['total_accounts'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-500 ml-1">active accounts</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m10-6h2a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Interest Earned</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">+{{ number_format($accounts->sum('interest_earned') ?? 0, 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-500 ml-1">to date</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Avg Interest</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600">{{ number_format($accounts->avg('interest_rate') ?? 0, 1) }}%</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-500 ml-1">rate across accounts</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-5 sm:p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Balance</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-600">{{ number_format($stats['total_balance'] ?? 0, 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-gray-500 ml-1">TZS</span>
                    </div>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Accounts</h2>
                <p class="text-sm text-gray-500">Search by account type or account number.</p>
            </div>
            <div class="w-full sm:w-80">
                <div class="relative">
                    <input id="savings-search" type="text" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#015425] focus:border-transparent" placeholder="Search accounts...">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Cards Grid -->
    <div id="savings-accounts-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($accounts as $account)
            <div class="savings-account-card group relative bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-search="{{ strtolower($account->account_type_name . ' ' . $account->account_number) }}">
                <!-- Color Bar based on account type -->
                @php
                    $colors = [
                        'emergency' => 'bg-red-500',
                        'flex' => 'bg-blue-500',
                        'rda' => 'bg-purple-500',
                        'business' => 'bg-green-500'
                    ];
                    $color = $colors[$account->account_type] ?? 'bg-gray-500';
                @endphp
                <div class="h-2 w-full {{ $color }}"></div>
                
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 group-hover:text-[#015425] transition-colors">{{ $account->account_type_name }}</h3>
                            <p class="text-xs font-mono text-gray-400 uppercase tracking-tighter">{{ $account->account_number }}</p>
                        </div>
                         <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-full uppercase tracking-widest">
                            {{ $account->status }}
                        </span>
                    </div>

                    <div class="mb-8">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Available Funds</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-gray-900">{{ number_format($account->balance, 0) }}</span>
                            <span class="text-xs font-bold text-gray-400">TZS</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Interest</p>
                            <p class="text-sm font-black text-gray-900">{{ $account->interest_rate }}%</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Since</p>
                            <p class="text-sm font-black text-gray-900">{{ $account->opening_date->format('M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('member.savings.show', $account->id) }}" class="flex-1 bg-[#015425] text-white py-3 rounded-xl font-bold text-center text-sm shadow-lg hover:bg-[#013019] transition-all">
                            Manage
                        </a>
                        <button class="w-12 flex items-center justify-center bg-gray-100 rounded-xl text-gray-400 hover:bg-gray-200 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-[3rem] shadow-sm border border-gray-100 p-20 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                     <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Initialize Your Savings</h3>
                <p class="text-gray-400 max-w-sm mx-auto mb-8">You don't have any active savings accounts. Start contributing to your future today.</p>
                <a href="{{ route('member.savings.create') }}" class="inline-block px-10 py-4 bg-[#015425] text-white rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all">Setup First Account</a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('savings-search');
    if (!input) return;

    const cards = Array.from(document.querySelectorAll('.savings-account-card'));
    input.addEventListener('input', function () {
        const q = (this.value || '').toLowerCase().trim();
        cards.forEach((card) => {
            const hay = (card.dataset.search || '');
            card.classList.toggle('hidden', q.length > 0 && !hay.includes(q));
        });
    });
});
</script>
@endpush
@endsection
