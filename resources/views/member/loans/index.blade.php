@extends('layouts.member')

@section('page-title', 'My Loan Portfolio')

@section('content')
<div class="space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tight">Credit Management</h1>
                    <p class="text-green-50 text-base sm:text-xl opacity-80 leading-relaxed font-medium">Access community capital to fuel your goals. Track your repayment progress and maintaining your financial standing.</p>
                </div>
                <div class="flex flex-col gap-4 w-full md:w-auto">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-green-200 mb-1">Total Outstanding Debt</p>
                        <p class="text-3xl font-black">{{ number_format($stats['remaining_amount'], 0) }} <span class="text-sm font-normal opacity-60">TZS</span></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 flex flex-wrap gap-4">
                <a href="{{ route('member.loans.create') }}" class="px-8 py-4 bg-white text-[#015425] rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Apply for New Loan
                </a>
                <button class="px-8 py-4 bg-white/10 backdrop-blur-md text-white rounded-2xl font-bold border border-white/20 hover:bg-white/20 transition-all">
                    Repayment History
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Total Facilities</p>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</p>
            <div class="mt-2 h-1 w-12 bg-blue-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Active Loans</p>
            <p class="text-3xl font-black text-[#015425]">{{ $stats['active'] }}</p>
            <div class="mt-2 h-1 w-12 bg-green-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Disbursed Volume</p>
            <p class="text-xl font-black text-gray-900 truncate" title="{{ number_format($stats['total_amount'], 0) }}">{{ number_format($stats['total_amount'] / 1000000, 1) }}M <span class="text-[10px] text-gray-400">TZS</span></p>
            <div class="mt-2 h-1 w-12 bg-indigo-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Credit Score</p>
            <p class="text-3xl font-black text-blue-600">Prime</p>
            <div class="mt-2 h-1 w-12 bg-blue-400 rounded-full"></div>
        </div>
    </div>

    <!-- Facilities List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-black text-gray-900">Loan Facilities</h2>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Filter:</span>
                <select class="text-xs font-bold text-gray-600 bg-gray-50 border-none rounded-xl focus:ring-0">
                    <option>All Facilities</option>
                    <option>Awaiting Approval</option>
                    <option>Active</option>
                    <option>Settled</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Facility Details</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Capital</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Repayment Progress</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Exposure</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($loans as $loan)
                        <tr class="group hover:bg-green-50/30 transition-all duration-300">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">{{ $loan->loan_number }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Application: {{ $loan->application_date->format('d M, Y') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-700">{{ number_format($loan->principal_amount, 0) }}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">{{ $loan->loan_type }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="w-32">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Paid {{ number_format($loan->paid_amount, 0) }}</span>
                                        <span class="text-[9px] font-black text-[#015425]">{{ round(($loan->paid_amount / $loan->total_repayment_amount) * 100) }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#015425] rounded-full transition-all duration-1000" style="width: {{ ($loan->paid_amount / $loan->total_repayment_amount) * 100 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm font-black text-red-600">
                                {{ number_format($loan->remaining_amount, 0) }}
                            </td>
                            <td class="px-8 py-6">
                                @if($loan->status === 'active')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @elseif($loan->status === 'pending')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-yellow-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        Pending Review
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        {{ $loan->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('member.loans.show', $loan) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#015425] group-hover:text-white transition-all duration-300 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                         <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-2">No Active Facilities</h3>
                                    <p class="text-xs text-gray-400 mb-8 leading-relaxed">You haven't applied for any community loans yet. Secure fast and low-interest capital today.</p>
                                    <a href="{{ route('member.loans.create') }}" class="inline-block px-8 py-3 bg-[#015425] text-white rounded-xl text-xs font-black shadow-xl hover:shadow-2xl transition-all">Submit Application</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($loans->hasPages())
            <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
