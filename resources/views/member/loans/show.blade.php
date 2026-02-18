@extends('layouts.member')

@section('page-title', 'Facility Analysis')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumbs and Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <li><a href="{{ route('member.loans.index') }}" class="text-gray-400 hover:text-[#015425] transition">Loans</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-[#015425]">Analysis #{{ $loan->loan_number }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-gray-900">Facility Analysis</h1>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('member.loans.index') }}" class="flex-1 md:flex-none px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-xs">
                Back to List
            </a>
            <button class="flex-1 md:flex-none px-6 py-2.5 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#013019] transition text-center text-xs shadow-lg">
                Download Schedule
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-10 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                             <span class="px-3 py-1 bg-[#015425]/5 text-[#015425] text-[10px] font-bold rounded-full uppercase tracking-tighter">
                                {{ $loan->loan_type }}
                            </span>
                            @if($loan->status === 'active')
                                <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest ml-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Operational
                                </span>
                            @endif
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-black text-gray-900 leading-tight">
                            {{ number_format($loan->remaining_amount, 0) }}
                             <span class="text-lg text-gray-300 font-normal uppercase tracking-widest block sm:inline">TZS Owed</span>
                        </h2>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 min-w-[200px]">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Repayment Health</p>
                        @php
                            $progress = ($loan->paid_amount / $loan->total_repayment_amount) * 100;
                        @endphp
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-2xl font-black text-gray-900">{{ round($progress) }}%</span>
                            <span class="text-[9px] font-bold text-gray-400">PAID</span>
                        </div>
                        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#015425] rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-50">
                    <div class="p-6 text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Principal</p>
                        <p class="text-sm font-black text-gray-900">{{ number_format($loan->principal_amount, 0) }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Net Interest</p>
                        <p class="text-sm font-black text-red-600">+{{ number_format($loan->total_repayment_amount - $loan->principal_amount, 0) }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Term</p>
                        <p class="text-sm font-black text-gray-900">{{ $loan->term_months }} Mo</p>
                    </div>
                    <div class="p-6 text-center">
                         <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Due Rate</p>
                        <p class="text-sm font-black text-gray-900">{{ $loan->interest_rate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Execution Timeline</h3>
                    <div class="space-y-6">
                         <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Approved</p>
                                <p class="text-sm font-black text-gray-900">{{ $loan->approval_date ? $loan->approval_date->format('d M, Y') : 'Pending' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Maturity</p>
                                <p class="text-sm font-black text-gray-900">{{ $loan->maturity_date ? $loan->maturity_date->format('d M, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full"></div>
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-6">Capital Flow</h3>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter mb-1">Total to Repay</p>
                            <p class="text-2xl font-black">{{ number_format($loan->total_repayment_amount, 0) }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-[9px] font-bold text-green-500 uppercase tracking-tighter mb-1">Paid to Date</p>
                            <p class="text-2xl font-black text-green-500">{{ number_format($loan->paid_amount, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amortization / Payments -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-xl font-black text-gray-900">Payment History</h3>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Records: {{ $transactions->count() }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reference</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Description</th>
                                <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $txn)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-900">{{ $txn->transaction_date->format('d M, Y') }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-mono text-gray-400">{{ $txn->reference ?? 'REF-'.$txn->id }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs text-gray-600">{{ $txn->description ?? 'Loan Repayment' }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-sm text-[#015425]">
                                        {{ number_format($txn->amount, 0) }} TZS
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-14 text-center">
                                        <div class="max-w-xs mx-auto">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">No Transactions</p>
                                            <p class="text-[10px] text-gray-300">Wait for your first payment to be processed and appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($loan->status === 'active')
            <div class="bg-red-50 rounded-[2rem] p-8 border border-red-100 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-red-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-red-800 uppercase tracking-widest">Urgent Notice</h3>
                        <p class="text-[10px] font-bold text-red-400">Repayment Outstanding</p>
                    </div>
                </div>
                <p class="text-xs text-red-800 text-opacity-70 leading-relaxed mb-6">Maintaining your repayment schedule is critical for your community trust score. Please ensure timely settlements.</p>
                <a href="#" class="block w-full text-center py-3.5 bg-red-600 text-white rounded-xl font-bold text-xs hover:bg-red-700 transition-all shadow-lg">
                    Submit Payment Proof
                </a>
            </div>
            @endif

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Endorsement</h3>
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl">
                    <div class="w-10 h-10 bg-[#015425] rounded-full flex items-center justify-center text-white font-black text-xs">
                        {{ strtoupper(substr($loan->approver->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-900">{{ $loan->approver->name ?? 'System Authority' }}</p>
                        <p class="text-[9px] text-gray-400 uppercase font-bold tracking-tighter">Approved Officer</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600 rounded-[2rem] p-8 text-white shadow-xl">
                 <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-indigo-200">Refinancing</h3>
                 <p class="text-xs text-white text-opacity-70 leading-relaxed mb-8">Want to settle early or top up your loan? We offer flexible refinancing options for active members.</p>
                 <a href="{{ route('member.issues.create') }}" class="block w-full text-center py-3.5 bg-white text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-50 transition-all flex items-center justify-center gap-2">
                     Learn More
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                 </a>
            </div>
        </div>
    </div>
</div>
@endsection
