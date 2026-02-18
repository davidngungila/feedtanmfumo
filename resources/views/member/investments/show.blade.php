@extends('layouts.member')

@section('page-title', 'Investment Analysis')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumbs and Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest">
                    <li><a href="{{ route('member.investments.index') }}" class="text-gray-400 hover:text-[#015425] transition">Investments</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-[#015425]">Analysis #{{ $investment->investment_number }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold text-gray-900">Asset Analysis</h1>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('member.investments.index') }}" class="flex-1 md:flex-none px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-sm">
                Back to List
            </a>
            <button class="flex-1 md:flex-none px-6 py-2 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#013019] transition text-center text-sm shadow-lg">
                Download Statement
            </button>
        </div>
    </div>

    <!-- Main Analysis Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Major Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Asset Overview Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                             <span class="px-3 py-1 bg-green-50 text-[#015425] text-[10px] font-bold rounded-full uppercase tracking-tighter">
                                {{ $investment->plan_type_name }}
                            </span>
                            @if($investment->status === 'active')
                                <span class="flex items-center gap-1.5 text-xs font-bold text-green-600 ml-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    Operational
                                </span>
                            @endif
                        </div>
                        <h2 class="text-4xl font-black text-gray-900">{{ number_format($investment->principal_amount, 0) }} <span class="text-lg text-gray-400 font-normal">TZS</span></h2>
                        <p class="text-sm text-gray-400 mt-1">Total Principal Invested on {{ $investment->start_date->format('M d, Y') }}</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-20 h-20 rounded-2xl bg-gray-50 flex items-center justify-center text-[#015425]">
                             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-50">
                    <div class="p-6 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Duration</p>
                        <p class="text-lg font-bold text-gray-900">{{ $investment->plan_type === '4_year' ? '4 Years' : '6 Years' }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Interest Rate</p>
                        <p class="text-lg font-bold text-green-600">{{ $investment->interest_rate }}% <span class="text-xs text-gray-400">p.a</span></p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Maturity Date</p>
                        <p class="text-lg font-bold text-gray-900">{{ $investment->maturity_date->format('M d, Y') }}</p>
                    </div>
                    <div class="p-6 text-center">
                         <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Est. Profit</p>
                        <p class="text-lg font-bold text-purple-600">{{ number_format($investment->expected_return - $investment->principal_amount, 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- ROI Projection Chart / Mockup -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl shadow-xl p-8 text-white">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-xl font-bold">Return Projection</h3>
                        <p class="text-xs text-gray-400">Growth visualization over the next {{ $investment->plan_type === '4_year' ? '4' : '6' }} years</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-green-500">{{ number_format($investment->expected_return, 0) }}</p>
                        <p class="text-[10px] text-gray-400 uppercase">Maturity Value</p>
                    </div>
                </div>
                
                <!-- Mock Chart Visualization -->
                <div class="h-40 flex items-end gap-1">
                    @for($i=0; $i<12; $i++)
                        <div class="flex-1 bg-green-500 rounded-t-lg transition-all hover:bg-green-400 cursor-pointer" style="height: {{ 20 + ($i * 6) }}%; opacity: {{ 0.3 + ($i * 0.05) }}"></div>
                    @endfor
                </div>
                <div class="flex justify-between mt-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    <span>Year 1</span>
                    <span>Year {{ $investment->plan_type === '4_year' ? '4' : '6' }} (Peak)</span>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-lg font-extrabold text-gray-900">Asset Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Description</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Reference</th>
                                <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-4 text-sm font-medium text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</td>
                                    <td class="px-8 py-4 text-sm text-gray-800">{{ $transaction->description }}</td>
                                    <td class="px-8 py-4 text-sm font-mono text-gray-400">{{ $transaction->reference }}</td>
                                    <td class="px-8 py-4 text-sm font-bold text-gray-900 text-right">{{ number_format($transaction->amount, 0) }} TZS</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm italic">
                                        Initial deposit of {{ number_format($investment->principal_amount, 0) }} TZS recorded on inception.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Sidebar Insights -->
        <div class="space-y-6">
            <!-- Security Badge -->
            <div class="bg-green-50 rounded-3xl p-6 border border-green-100">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#015425] shadow-sm mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-[#015425] mb-2">Capital Guaranteed</h3>
                <p class="text-xs text-[#015425] text-opacity-70 leading-relaxed">This investment is backed by the FeedTan Community Trust. Your principal amount is fully protected against market volatility.</p>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-extrabold text-gray-900 mb-6">Asset Timeline</h3>
                <div class="space-y-6">
                    <div class="relative pl-6 border-l-2 border-green-500">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-500 border-4 border-white"></div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inception</p>
                        <p class="text-sm font-bold text-gray-900">{{ $investment->start_date->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Account successfully funded</p>
                    </div>
                    
                    <div class="relative pl-6 border-l-2 border-gray-200">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-gray-200 border-4 border-white"></div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mid-Term Review</p>
                        <p class="text-sm font-bold text-gray-900">{{ $investment->start_date->addYears($investment->plan_type === '4_year' ? 2 : 3)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400 italic text-opacity-50 underline decoration-dotted">Projected Milestone</p>
                    </div>

                    <div class="relative pl-6 border-l-2 border-gray-200">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-gray-200 border-4 border-white"></div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Maturity</p>
                        <p class="text-sm font-bold text-gray-900">{{ $investment->maturity_date->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400">Payout eligibility</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden group">
                 <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                 <h3 class="text-lg font-bold mb-2">Need Liquidity?</h3>
                 <p class="text-xs text-blue-100 mb-6 leading-relaxed">Emergency withdrawals are available but may incur a fee of up to 5% of the principal amount.</p>
                 <a href="{{ route('member.issues.create') }}" class="inline-block px-6 py-2 bg-white text-blue-600 rounded-xl font-bold text-xs hover:shadow-xl transition-all">Submit Request</a>
            </div>
        </div>
    </div>
</div>
@endsection
