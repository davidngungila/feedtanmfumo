@extends('layouts.member')

@section('page-title', 'Community Welfare Portfolio')

@section('content')
<div class="space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tight">Social Welfare</h1>
                    <p class="text-green-50 text-base sm:text-xl opacity-80 leading-relaxed font-medium">Supporting our community through collective safety nets. Contributing to welfare today secures our mutual future.</p>
                </div>
                <div class="flex flex-col gap-4 w-full md:w-auto">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-green-200 mb-1">Total Contributions</p>
                        <p class="text-3xl font-black">{{ number_format($stats['total_contributions'], 0) }} <span class="text-sm font-normal opacity-60">TZS</span></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 flex flex-wrap gap-4">
                <a href="{{ route('member.welfare.create') }}" class="px-8 py-4 bg-white text-[#015425] rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Welfare Record
                </a>
                <button class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-2xl font-bold border border-white/20 hover:bg-white/20 transition-all">
                    Benefit History
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Total Benefits</p>
            <p class="text-3xl font-black text-blue-600">{{ number_format($stats['total_benefits'], 0) }}</p>
            <div class="mt-2 h-1 w-12 bg-blue-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Pending Review</p>
            <p class="text-3xl font-black text-yellow-600">{{ $stats['pending'] }}</p>
            <div class="mt-2 h-1 w-12 bg-yellow-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Approved</p>
            <p class="text-3xl font-black text-green-600">{{ $stats['approved'] }}</p>
            <div class="mt-2 h-1 w-12 bg-green-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Community Rank</p>
            <p class="text-3xl font-black text-indigo-600">Active</p>
            <div class="mt-2 h-1 w-12 bg-indigo-500 rounded-full"></div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-black text-gray-900">Welfare Ledger</h2>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Filter:</span>
                <select class="text-xs font-bold text-gray-600 bg-gray-50 border-none rounded-xl focus:ring-0">
                    <option>All Records</option>
                    <option>Contributions</option>
                    <option>Benefits</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reference #</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Volume</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Flow</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($welfares as $welfare)
                        <tr class="group hover:bg-green-50/30 transition-all duration-300">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">{{ $welfare->welfare_number }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $welfare->transaction_date->format('d M, Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[9px] font-black rounded-full uppercase tracking-widest">
                                    {{ $welfare->type }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-700">{{ number_format($welfare->amount, 0) }}</p>
                                <p class="text-[9px] text-gray-400 uppercase font-bold tracking-tighter">TZS</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($welfare->type === 'contribution')
                                    <span class="text-green-600 font-bold text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        Outbound
                                    </span>
                                @else
                                    <span class="text-blue-600 font-bold text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        Inbound
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @if($welfare->status === 'approved')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Finalized
                                    </span>
                                @elseif($welfare->status === 'pending')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-yellow-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                        Reviewing
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        {{ $welfare->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('member.welfare.show', $welfare) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#015425] group-hover:text-white transition-all duration-300 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                         <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-2">No Welfare Records</h3>
                                    <p class="text-xs text-gray-400 mb-8 leading-relaxed">You haven't recorded any welfare contributions or benefits yet.</p>
                                    <a href="{{ route('member.welfare.create') }}" class="inline-block px-8 py-3 bg-[#015425] text-white rounded-xl text-xs font-black shadow-xl hover:shadow-2xl transition-all">Report Record</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($welfares->hasPages())
            <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
                {{ $welfares->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
