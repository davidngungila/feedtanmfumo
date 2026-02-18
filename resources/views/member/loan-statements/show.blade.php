@extends('layouts.member')

@section('page-title', 'Statement Detail - ' . $loanStatement->month_name . ' ' . $loanStatement->year)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-8 bg-gradient-to-br from-[#015425] to-[#027a3a] text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-semibold uppercase tracking-wider">Loan Statement</p>
                    <h2 class="text-3xl font-bold mt-1">{{ $loanStatement->month_name }} {{ $loanStatement->year }}</h2>
                </div>
                <div class="text-right">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm p-3 rounded-lg border border-white border-opacity-30">
                        <p class="text-xs text-green-50 uppercase font-bold">Member Code</p>
                        <p class="text-xl font-mono font-bold">{{ Auth::user()->membership_code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Summary Card -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="w-8 h-8 bg-green-50 text-[#015425] rounded-full flex items-center justify-center mr-3 text-sm">01</span>
                        Repayment Summary
                    </h3>
                    
                    <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Principal Repayment</span>
                            <span class="font-bold text-gray-900">TZS {{ number_format($loanStatement->principal_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Interest Payment</span>
                            <span class="font-bold text-gray-900">TZS {{ number_format($loanStatement->interest_paid, 2) }}</span>
                        </div>
                        @if($loanStatement->penalty_paid > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Fines/Penalties</span>
                            <span class="font-bold text-red-600">TZS {{ number_format($loanStatement->penalty_paid, 2) }}</span>
                        </div>
                        @endif
                        <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-gray-900 font-bold">Total Processed</span>
                            <span class="text-xl font-black text-[#015425]">TZS {{ number_format($loanStatement->total_paid, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Balance Card -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="w-8 h-8 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mr-3 text-sm">02</span>
                        Loan Balances
                    </h3>
                    
                    <div class="bg-orange-50 bg-opacity-30 rounded-xl p-6 space-y-4 border border-orange-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Opening Balance</span>
                            <span class="font-medium text-gray-700">TZS {{ number_format($loanStatement->opening_balance, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 text-opacity-80 font-bold italic">Minus Repayments...</span>
                            <span class="font-medium text-green-600">-{{ number_format($loanStatement->total_paid, 2) }}</span>
                        </div>
                        <div class="pt-4 border-t border-orange-200 flex justify-between items-center">
                            <span class="text-gray-900 font-black">Closing Balance</span>
                            <span class="text-2xl font-black text-orange-600">TZS {{ number_format($loanStatement->closing_balance, 2) }}</span>
                        </div>
                    </div>

                    @if($loanStatement->notes)
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100 italic text-sm text-blue-800">
                        <strong>Notes:</strong> {{ $loanStatement->notes }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Action -->
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100 pt-8">
                <a href="{{ route('member.loan-statements.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold text-sm flex items-center transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to all statements
                </a>
                <button onclick="window.print()" class="px-8 py-3 bg-[#015425] text-white rounded-full font-bold shadow-lg hover:shadow-xl hover:bg-[#027a3a] transition transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Download PDF / Print
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        header, footer, .loading-screen, .flex-col.sm\:flex-row.items-center.justify-between.gap-4 {
            display: none !important;
        }
        .max-w-4xl {
            max-width: 100% !important;
        }
        .shadow-lg {
            box-shadow: none !important;
        }
    }
</style>
@endsection
