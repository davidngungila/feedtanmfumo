@extends('layouts.member')

@section('page-title', 'Savings Statement')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <li><a href="{{ route('member.savings.index') }}" class="text-gray-400 hover:text-[#015425] transition">Savings</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-[#015425]">Statement</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-gray-900">Account Statement</h1>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('member.savings.index') }}" class="flex-1 md:flex-none px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-xs">
                Back to Portfolio
            </a>
            <button class="flex-1 md:flex-none px-6 py-2.5 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#013019] transition text-center text-xs shadow-lg">
                Download PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Statement Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Master Card -->
            <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 rounded-[2.5rem] p-8 sm:p-10 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 p-10 opacity-10">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-12">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-300 mb-1">Account Holder</p>
                            <h2 class="text-xl font-black">{{ $saving->user->name }}</h2>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $saving->account_type_name }}</span>
                        </div>
                    </div>

                    <div class="mb-12">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-300 mb-2">Available Balance</p>
                        <div class="flex items-baseline gap-3">
                            <span class="text-5xl sm:text-6xl font-black leading-none">{{ number_format($saving->balance, 0) }}</span>
                            <span class="text-sm font-bold opacity-60">TZS</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-8 items-center pt-8 border-t border-white/10">
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-300 mb-1">Account Number</p>
                            <p class="text-xs font-mono font-bold">{{ $saving->account_number }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-300 mb-1">Interest Rate</p>
                            <p class="text-sm font-black text-green-400">{{ $saving->interest_rate }}% <span class="text-[10px] opacity-60">p.a</span></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-indigo-300 mb-1">Inception</p>
                            <p class="text-sm font-black">{{ $saving->opening_date->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Ledger -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900">Transaction Ledger</h3>
                    <div class="flex gap-2">
                        <button class="p-2 bg-gray-50 text-gray-400 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaction Date</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ref / Description</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Type</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Credit/Debit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $transaction)
                                <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-900">{{ $transaction->transaction_date->format('d M, Y') }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $transaction->transaction_date->format('H:i') }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-700">{{ $transaction->description ?? 'Community Transaction' }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $transaction->reference ?? 'TXN-'.$transaction->id }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-tighter {{ $transaction->transaction_type === 'credit' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                            {{ $transaction->transaction_type }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-sm {{ $transaction->transaction_type === 'credit' ? 'text-[#015425]' : 'text-red-600' }}">
                                        {{ $transaction->transaction_type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="max-w-xs mx-auto">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No Activity Recorded</p>
                                            <p class="text-[10px] text-gray-300 mt-2">Any future credits or debits to this account will appear here in chronological order.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Insights -->
        <div class="space-y-6">
            <!-- Stability Badge -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-[#015425]">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Account Status</h3>
                        <p class="text-xs font-bold text-green-600">Secure & Active</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">This account is fully insured under the FeedTan community protection fund. Your liquidity is guaranteed within standard processing timelines.</p>
            </div>

            <!-- Maturity Progress (If RDA) -->
            @if($saving->account_type === 'rda' && $saving->maturity_date)
            <div class="bg-gray-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
                <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-gray-400">Lock-in Progress</h3>
                
                @php
                    $start = $saving->opening_date->timestamp;
                    $end = $saving->maturity_date->timestamp;
                    $current = time();
                    $progress = max(0, min(100, (($current - $start) / ($end - $start)) * 100));
                @endphp

                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-4xl font-black">{{ round($progress) }}%</span>
                        <span class="text-[10px] font-bold text-gray-500 tracking-wider">UNTIL MATURITY</span>
                    </div>
                    <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-white/5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase">Target Date</span>
                        <span class="font-black">{{ $saving->maturity_date->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-[#015425] rounded-[2rem] p-8 text-white shadow-xl">
                 <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-green-200">Account Control</h3>
                 <div class="space-y-3">
                     <button class="w-full py-3.5 bg-white text-[#015425] rounded-xl font-bold text-xs hover:bg-green-50 transition-all flex items-center justify-center gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         Request Withdrawal
                     </button>
                     <button class="w-full py-3.5 bg-white/10 text-white rounded-xl font-bold text-xs hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                         Report Dispute
                     </button>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
