@extends('layouts.member')

@section('page-title', 'Certified Ledger Preview')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 no-print">
        <a href="{{ route('member.monthly-deposits.index') }}" class="group flex items-center gap-3 text-[#015425] font-black text-xs uppercase tracking-widest">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-[#015425] group-hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            Back to Vault
        </a>
        <div class="flex items-center gap-4">
            @if($monthlyDeposit->statement_pdf)
            <a href="{{ $monthlyDeposit->statement_pdf }}" target="_blank" class="px-8 py-4 bg-orange-600 text-white rounded-[1.25rem] font-black text-xs shadow-xl hover:bg-orange-700 hover:-translate-y-1 transition-all flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                OFFICIAL SYSTEM PDF
            </a>
            @endif
            <button onclick="window.print()" class="px-8 py-4 bg-white text-[#015425] border-2 border-[#015425]/10 rounded-[1.25rem] font-black text-xs hover:bg-[#015425] hover:text-white transition-all flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                PRINT LEDGER
            </button>
        </div>
    </div>

    <!-- Certified Document Body -->
    <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden" id="printable-area">
        <!-- Certificate Header -->
        <div class="p-10 sm:p-16 bg-gradient-to-br from-[#015425] to-[#027a3a] text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-10">
                <div>
                    <h2 class="text-4xl sm:text-5xl font-black mb-4 tracking-tighter uppercase">Certified Ledger</h2>
                    <div class="px-5 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-green-200">Authenticated Record</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-4xl font-black mb-1">{{ $monthlyDeposit->month_name }} {{ $monthlyDeposit->year }}</p>
                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest font-mono">HASH ID: CM-{{ str_pad($monthlyDeposit->id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <div class="p-10 sm:p-20">
            <!-- Strategic Credentials -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-20 mb-20">
                <div class="space-y-8">
                    <div>
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-100 pb-2">Beneficiary Intelligence</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Identity</p>
                                <p class="text-2xl font-black text-gray-900 leading-tight">{{ $monthlyDeposit->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Member Protocol</p>
                                <p class="text-lg font-black text-[#015425] font-mono tracking-tight">{{ $monthlyDeposit->member_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-8 md:text-right">
                    <div>
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-100 pb-2 md:text-right">Institutional Context</h4>
                        <div class="space-y-4">
                            <p class="text-2xl font-black text-gray-900 uppercase tracking-tighter">FEEDTAN REJESHO</p>
                            <p class="text-xs font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Electronic Settlement System<br>Certification Authority Index</p>
                            <p class="text-[10px] font-black text-[#015425] uppercase bg-green-50 px-4 py-2 rounded-full inline-block">Cycle Date: {{ now()->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantitative Matrix -->
            <div class="space-y-6 mb-20">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 block">Deployment Summary</h4>
                <div class="rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Asset Allocation</th>
                                <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Value (TZS)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-gray-900 group-hover:text-[#015425]">Monthly Liquidity (Akiba)</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Primary Savings Vehicle</p>
                                </td>
                                <td class="px-8 py-6 text-right text-lg font-black text-gray-900">{{ number_format($monthlyDeposit->savings, 0) }}</td>
                            </tr>
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-gray-900 group-hover:text-[#015425]">Equity Shares (Hisa)</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Community Capital Exposure</p>
                                </td>
                                <td class="px-8 py-6 text-right text-lg font-black text-gray-900">{{ number_format($monthlyDeposit->shares, 0) }}</td>
                            </tr>
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-gray-900 group-hover:text-[#015425]">Welfare Contribution</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Social Safety Protocol</p>
                                </td>
                                <td class="px-8 py-6 text-right text-lg font-black text-gray-900">{{ number_format($monthlyDeposit->welfare, 0) }}</td>
                            </tr>
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-gray-900 group-hover:text-[#015425]">Loan Amortization (Principal + Interest)</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Liability Servicing Dynamics</p>
                                </td>
                                <td class="px-8 py-6 text-right text-lg font-black text-gray-900">{{ number_format($monthlyDeposit->loan_principal + $monthlyDeposit->loan_interest, 0) }}</td>
                            </tr>
                            @if($monthlyDeposit->fine_penalty > 0)
                            <tr class="bg-red-50/20 group hover:bg-red-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-sm font-black text-red-600">Regulatory Adjustments (Fines/Penalties)</p>
                                    <p class="text-[10px] text-red-400 font-bold uppercase tracking-widest mt-0.5">Policy Compliance Correction</p>
                                </td>
                                <td class="px-8 py-6 text-right text-lg font-black text-red-600">{{ number_format($monthlyDeposit->fine_penalty, 0) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-[#015425] text-white">
                                <td class="px-8 py-10">
                                    <p class="text-xl font-black uppercase tracking-[0.2em]">Net Settlement</p>
                                    <p class="text-[10px] font-bold uppercase opacity-60 tracking-widest mt-1">Total Verified Contribution</p>
                                </td>
                                <td class="px-8 py-10 text-right">
                                    <p class="text-4xl font-black">{{ number_format($monthlyDeposit->total, 0) }}</p>
                                    <p class="text-[10px] font-bold uppercase opacity-60 tracking-widest mt-1">Currency: TZS</p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Contextual Intelligence -->
            @if($monthlyDeposit->generated_message)
            <div class="p-10 bg-blue-50/50 rounded-[2.5rem] border border-blue-100 mb-12 relative overflow-hidden group">
                <div class="absolute -right-5 -top-5 w-24 h-24 bg-blue-500/5 rounded-full group-hover:scale-110 transition-transform"></div>
                <h4 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    System Insight Proxy
                </h4>
                <div class="text-blue-900 leading-relaxed font-bold text-sm opacity-80 whitespace-pre-wrap">
                    {!! nl2br(e($monthlyDeposit->generated_message)) !!}
                </div>
            </div>
            @endif

            @if($monthlyDeposit->notes)
            <div class="p-8 bg-gray-50/50 rounded-[2rem] border border-gray-100 mb-12">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Strategic Notes</h4>
                <p class="text-sm font-bold text-gray-600 italic">"{{ $monthlyDeposit->notes }}"</p>
            </div>
            @endif

            <!-- Verification Protocol Footer -->
            <div class="flex flex-col md:flex-row items-center justify-between pt-16 border-t border-gray-100 gap-10">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A9 9 0 1120.364 6.364l-1.42 1.419a7 7 0 10-9.9 9.9l1.414-1.414L10 18a8 8 0 1118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Electronic Settlement Verified</p>
                        <p class="text-[10px] font-bold text-gray-400 leading-relaxed mt-0.5">This document serves as a binding digital certificate of deposit.<br>Validity verified through FEEDTAN Centralized Ledger.</p>
                    </div>
                </div>
                
                <div class="w-32 h-32 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100 flex items-center justify-center text-gray-300 relative group overflow-hidden">
                     <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-5 transition-opacity"></div>
                     <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; -webkit-print-color-adjust: exact; }
    #printable-area { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; }
    nav, aside, header, button, .no-print, [role="navigation"] { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; position: absolute; top: 0; left: 0; }
    .max-w-5xl { max-width: 100% !important; padding: 0 !important; }
    .rounded-[3rem] { border-radius: 0 !important; }
    .sm\:p-20 { padding: 2rem !important; }
}
</style>
@endsection
