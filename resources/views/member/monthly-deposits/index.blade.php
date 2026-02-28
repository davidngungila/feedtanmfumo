@extends('layouts.member')

@section('page-title', 'Financial Repository')

@section('content')
<div class="space-y-6 pb-12">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Deposit Statements</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Filter your monthly contributions and export your ledger for your records.</p>
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
                        <span>{{ number_format($deposits->count() ?? 0) }} cycles</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('member.monthly-deposits.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('member.monthly-deposits.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 text-white rounded-md border border-white/20 hover:bg-white/20 transition font-medium">
                    Reset
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 sm:p-6">
        <form method="GET" action="{{ route('member.monthly-deposits.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">From (YYYY-MM)</label>
                <input type="month" name="from" value="{{ request('from') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#015425] focus:border-transparent">
            </div>
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">To (YYYY-MM)</label>
                <input type="month" name="to" value="{{ request('to') }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#015425] focus:border-transparent">
            </div>
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">Apply Filters</button>
                <a href="{{ route('member.monthly-deposits.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">Clear</a>
            </div>
        </form>
    </div>

    <!-- Statement Grid Index -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($deposits as $deposit)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group hover:-translate-y-1">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8">
                    <span class="px-4 py-1.5 bg-green-50 text-[#015425] text-[10px] font-black rounded-full uppercase tracking-widest">
                        Cycle Verification
                    </span>
                    <span class="text-xs font-black text-gray-300 font-mono tracking-widest group-hover:text-[#015425] transition-colors">{{ $deposit->year }}</span>
                </div>
                
                <h3 class="text-3xl font-black text-gray-900 mb-2">{{ $deposit->month_name }}</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-8">Asset Allocation Summary</p>

                <div class="space-y-4 mb-10">
                    <div class="flex justify-between items-center py-3 border-b border-gray-50 group-hover:border-green-100 transition-colors">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-tight">Savings & Shares</span>
                        <span class="text-sm font-black text-gray-900">{{ number_format($deposit->savings + $deposit->shares, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-4 bg-gray-50/50 px-4 rounded-2xl group-hover:bg-green-50/50 transition-colors">
                        <span class="text-[11px] font-black text-[#015425] uppercase tracking-tight">Total Contribution</span>
                        <span class="text-lg font-black text-[#015425]">{{ number_format($deposit->total, 0) }} <span class="text-[10px] font-normal opacity-60">TZS</span></span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('member.monthly-deposits.show', $deposit->id) }}" class="flex items-center justify-center gap-3 w-full py-4 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-xl hover:bg-[#013019] transition-all">
                        PREVIEW LEDGER
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                    @if($deposit->statement_pdf)
                    <a href="{{ $deposit->statement_pdf }}" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 bg-white text-orange-600 border-2 border-orange-50 rounded-2xl font-black text-xs hover:bg-orange-50 transition-all">
                        OFFICIAL PDF
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 text-center bg-gray-50/50 rounded-[3rem] border-2 border-dashed border-gray-200">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200 shadow-sm">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Vault Empty</h3>
            <p class="text-gray-400 font-medium max-w-sm mx-auto">No contributions recorded in the digital vault yet. Active cycles will appear here once certified.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
