@extends('layouts.member')

@section('page-title', __('portal.deposit_statements'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#015425]">@lang('portal.deposit_statements')</h1>
            <p class="text-gray-500 text-sm mt-1">@lang('portal.view_history')</p>
        </div>
        <div class="bg-[#015425] bg-opacity-5 rounded-lg p-3 flex items-center border border-[#015425] border-opacity-10">
            <div class="w-10 h-10 bg-[#015425] rounded-full flex items-center justify-center text-white mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">@lang('portal.total_statements')</p>
                <p class="text-xl font-bold text-[#015425]">{{ $deposits->count() }} @lang('portal.months')</p>
            </div>
        </div>
    </div>

    <!-- Statement List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($deposits as $deposit)
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full uppercase tracking-widest">
                        @lang('portal.monthly_report')
                    </span>
                    <span class="text-sm font-bold text-gray-400 font-mono">{{ $deposit->year }}</span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $deposit->month_name }}</h3>
                <p class="text-xs text-gray-500 mb-4">Detailed contribution breakdown</p>

                <div class="space-y-2 mb-6 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">@lang('portal.savings_and_shares')</span>
                        <span class="font-bold text-gray-900">{{ number_format($deposit->savings + $deposit->shares, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-600">@lang('portal.total_contribution')</span>
                        <span class="font-bold text-[#015425]">{{ number_format($deposit->total, 2) }} TZS</span>
                    </div>
                </div>

                <a href="{{ route('member.monthly-deposits.show', $deposit->id) }}" class="block w-full text-center py-3 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#027a3a] transition-all transform group-hover:scale-[1.02]">
                    @lang('portal.preview_statement')
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-300">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">@lang('portal.no_statements')</h3>
        </div>
        @endforelse
    </div>
</div>
@endsection
