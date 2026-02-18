@extends('layouts.member')

@section('page-title', 'Social Contribution Analysis')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <li><a href="{{ route('member.welfare.index') }}" class="text-gray-400 hover:text-[#015425] transition">Welfare</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-[#015425]">Analysis #{{ $welfare->welfare_number }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-gray-900">Record Analysis</h1>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('member.welfare.index') }}" class="flex-1 md:flex-none px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-xs text-nowrap">
                Back to Portfolio
            </a>
            <button class="flex-1 md:flex-none px-6 py-2.5 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#013019] transition text-center text-xs shadow-lg text-nowrap">
                Download Receipt
            </button>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Master Detail Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-12 border-b border-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                        <div>
                             <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[10px] font-black rounded-full uppercase tracking-widest">
                                    {{ $welfare->type }}
                                </span>
                                @if($welfare->status === 'approved')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest ml-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Finalized
                                    </span>
                                @endif
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-2">
                                {{ number_format($welfare->amount, 0) }}
                                <span class="text-lg text-gray-300 font-normal uppercase tracking-widest">TZS</span>
                            </h2>
                            <p class="text-sm text-gray-400 font-bold uppercase tracking-tighter">Recorded on {{ $welfare->transaction_date->format('d M, Y') }}</p>
                        </div>
                        <div class="hidden sm:block">
                            @if($welfare->type === 'contribution')
                                <div class="w-20 h-20 bg-green-50 rounded-3xl flex items-center justify-center text-green-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                </div>
                            @else
                                <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-50">
                    <div class="p-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Asset Code</p>
                        <p class="text-sm font-black text-gray-900 font-mono">{{ $welfare->welfare_number }}</p>
                    </div>
                    <div class="p-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Processing State</p>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $welfare->status }}</p>
                    </div>
                    <div class="p-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Reporting Entity</p>
                        <p class="text-sm font-black text-gray-900">FeedTan Community</p>
                    </div>
                </div>
            </div>

            <!-- Narrative Card -->
            @if($welfare->description)
            <div class="bg-gray-50 rounded-[2rem] p-8 sm:p-10 border border-gray-100">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Execution Narrative</h3>
                <p class="text-base text-gray-700 leading-relaxed font-medium">{{ $welfare->description }}</p>
            </div>
            @endif

            <!-- Impact Summary -->
            <div class="bg-[#015425] rounded-[2.5rem] p-10 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-5 rounded-full"></div>
                <div class="relative z-10 flex items-center gap-8">
                    <div class="hidden sm:flex w-20 h-20 bg-white/10 backdrop-blur-md rounded-3xl items-center justify-center text-green-300">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black mb-2">Collective Security</h3>
                        <p class="text-sm text-green-50 opacity-80 leading-relaxed">This record contributes to your community standing. Welfare participation strengthens the mutual support systems that protect all members during times of need.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Insights -->
        <div class="space-y-6">
            <!-- Security Audit -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Audit Metadata</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-400 font-bold uppercase">Record Level</span>
                        <span class="font-black text-gray-900">Standard</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-400 font-bold uppercase">Integrity Check</span>
                        <span class="font-black text-green-600">Passed</span>
                    </div>
                     <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-400 font-bold uppercase">Visibility</span>
                        <span class="font-black text-gray-900 italic">Personal Ledger</span>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-2xl">
                 <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-gray-500">Need Adjustment?</h3>
                 <p class="text-xs text-gray-400 leading-relaxed mb-8">If you believe there is a discrepancy in this record, please initiate a review request within 7 days of the transaction date.</p>
                 <a href="{{ route('member.issues.create') }}" class="block w-full text-center py-4 bg-white text-gray-900 rounded-2xl font-black text-xs hover:bg-gray-100 transition-all">Submit Review</a>
            </div>

            <!-- Promotion Card -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-white shadow-xl group cursor-pointer overflow-hidden relative">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                <h3 class="text-lg font-black mb-4">Mutual Success</h3>
                <p class="text-[10px] text-indigo-100 uppercase tracking-[0.2em] font-bold">Welfare + Investments</p>
                <p class="text-xs text-indigo-50 mt-4 leading-relaxed opacity-70">Balanced participation in welfare and investment funds qualifies you for premium community rewards.</p>
            </div>
        </div>
    </div>
</div>
@endsection
