@extends('layouts.member')

@section('page-title', 'My Investments')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#013019] rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl sm:text-4xl font-extrabold mb-2">My Investment Portfolio</h1>
                <p class="text-green-100 text-sm sm:text-lg max-w-xl">Tracking your growth and future financial freedom through smart community investments.</p>
            </div>
            <a href="{{ route('member.investments.create') }}" class="group flex items-center gap-2 px-8 py-4 bg-white text-[#015425] rounded-xl font-bold hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Start New Plan
            </a>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Active Plans</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-400 mt-1">From total {{ $stats['total'] }} plans</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-green-50 p-2 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Invested</span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['total_principal'] / 1000000, 1) }}M <span class="text-xs text-gray-400">TZS</span></p>
            <p class="text-xs text-gray-400 mt-1">Initial capital commitment</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-purple-50 p-2 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Earnings</span>
            </div>
            <p class="text-3xl font-extrabold text-purple-600">+{{ number_format($stats['total_profit'], 0) }} <span class="text-xs text-gray-400">TZS</span></p>
            <p class="text-xs text-green-500 mt-1 font-bold">Growing steadily</p>
        </div>

        <div class="bg-[#015425] p-6 rounded-2xl shadow-xl text-white">
            <div class="flex items-center gap-3 mb-3 text-white text-opacity-70">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="text-sm font-bold uppercase tracking-widest">Maturity Goal</span>
            </div>
            <p class="text-3xl font-extrabold">{{ number_format($stats['expected_return'], 0) }}</p>
            <p class="text-xs text-white text-opacity-50 mt-1">Total value at maturity</p>
        </div>
    </div>

    <!-- Investments List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-center border-b border-gray-50 gap-4">
            <h2 class="text-xl font-extrabold text-gray-900">Portfolio Breakdown</h2>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filter:</span>
                <select class="text-sm font-bold text-gray-600 bg-gray-50 border-none rounded-lg focus:ring-0">
                    <option>All Assets</option>
                    <option>Active Only</option>
                    <option>Matured</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Asset Details</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Growth Plan</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Capital</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Earnings</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($investments as $investment)
                        <tr class="group hover:bg-green-50/30 transition-all duration-300">
                            <td class="px-8 py-6">
                                <p class="text-sm font-extrabold text-gray-900">{{ $investment->investment_number }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Started: {{ $investment->start_date->format('d M, Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-tighter">
                                    {{ $investment->plan_type_name }}
                                </span>
                            </td>
                            <td class="px-8 py-6 font-mono text-sm font-bold text-gray-700">
                                {{ number_format($investment->principal_amount, 0) }}
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-[#015425]">{{ number_format($investment->expected_return, 0) }}</p>
                                <p class="text-[10px] text-green-500 font-bold">+{{ number_format($investment->profit_share, 0) }} profit</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($investment->status === 'active')
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-green-600">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-blue-600">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        {{ ucfirst($investment->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('member.investments.show', $investment) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-400 group-hover:bg-[#015425] group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Portfolio Empty</p>
                                    <p class="text-xs text-gray-300 mt-2">You haven't started any investment plans yet. Start today to grow your wealth.</p>
                                    <a href="{{ route('member.investments.create') }}" class="inline-block mt-6 px-6 py-2 bg-[#015425] text-white rounded-lg text-xs font-bold hover:bg-[#013019] transition">Initialize Plan</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
