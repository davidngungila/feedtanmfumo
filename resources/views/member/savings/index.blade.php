@extends('layouts.member')

@section('page-title', 'My Savings Portfolio')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tight">Financial Resilience</h1>
                    <p class="text-green-50 text-base sm:text-xl opacity-80 leading-relaxed font-medium">Your total wealth in community savings is growing. Manage your accounts and watch your discipline pay off.</p>
                </div>
                <div class="flex flex-col gap-4 w-full md:w-auto">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-green-200 mb-1">Total Liquid Assets</p>
                        <p class="text-3xl font-black">{{ number_format($stats['total_balance'], 0) }} <span class="text-sm font-normal opacity-60">TZS</span></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 flex flex-wrap gap-4">
                <a href="{{ route('member.savings.create') }}" class="px-8 py-4 bg-white text-[#015425] rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Account
                </a>
                <button class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-2xl font-bold border border-white/20 hover:bg-white/20 transition-all">
                    View Statements
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Accounts</p>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total_accounts'] }}</p>
            <div class="mt-2 h-1 w-12 bg-blue-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Interest Earned</p>
            <p class="text-3xl font-black text-green-600">+{{ number_format($accounts->sum('interest_earned') ?? 0, 0) }}</p>
            <div class="mt-2 h-1 w-12 bg-green-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Avg Interest</p>
            <p class="text-3xl font-black text-gray-900">{{ $accounts->avg('interest_rate') ?? 0 }}%</p>
            <div class="mt-2 h-1 w-12 bg-purple-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Health Score</p>
            <p class="text-3xl font-black text-blue-600">Stable</p>
            <div class="mt-2 h-1 w-12 bg-blue-400 rounded-full"></div>
        </div>
    </div>

    <!-- Account Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($accounts as $account)
            <div class="group relative bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
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
                
                <div class="p-8">
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
@endsection
