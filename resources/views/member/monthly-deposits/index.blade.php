@extends('layouts.member')

@section('page-title', 'Financial Repository')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl sm:text-6xl font-black mb-6 tracking-tight">Deposit Ledger</h1>
                <p class="text-green-50 text-lg sm:text-xl opacity-80 max-w-2xl leading-relaxed font-medium">Historical verification of your monthly community contributions. Access certified statements for your financial records.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 text-center min-w-[240px]">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-green-200 mb-2">Authenticated History</p>
                <p class="text-5xl font-black">{{ $deposits->count() }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-white/60 mt-2">Active Monthly Cycles</p>
            </div>
        </div>
    </div>

    <!-- Statement Grid Index -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($deposits as $deposit)
        <div class="bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden group hover:-translate-y-2">
            <div class="p-8 sm:p-10">
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
