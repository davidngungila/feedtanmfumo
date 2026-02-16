@extends('layouts.member')

@section('page-title', 'Statement Preview')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('member.monthly-deposits.index') }}" class="inline-flex items-center text-[#015425] font-bold hover:underline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Statements
        </a>
        <div class="flex items-center gap-3">
            @if($monthlyDeposit->statement_pdf)
            <a href="{{ $monthlyDeposit->statement_pdf }}" target="_blank" class="px-4 py-2 bg-orange-600 border-2 border-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition-all flex items-center shadow-sm no-print">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Official PDF
            </a>
            @endif
            <button onclick="window.print()" class="px-4 py-2 bg-white border-2 border-[#015425] text-[#015425] rounded-xl font-bold hover:bg-[#015425] hover:text-white transition-all flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Statement
            </button>
        </div>
    </div>

    <!-- Live Preview Card (Document Style) -->
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" id="printable-area">
        <!-- Document Header -->
        <div class="p-8 sm:p-12 bg-gradient-to-br from-[#015425] to-[#027a3a] text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-extrabold mb-2 uppercase tracking-tighter">Deposit Statement</h2>
                    <p class="text-white text-opacity-80 font-medium">Monthly Member Contribution Summary</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-2xl font-black">{{ $monthlyDeposit->month_name }} {{ $monthlyDeposit->year }}</p>
                    <p class="text-white text-opacity-70 text-sm">Statement ID: #{{ str_pad($monthlyDeposit->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 sm:p-12">
            <!-- Member Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Member Details</h4>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Full Name</p>
                        <p class="text-xl font-bold text-gray-900">{{ $monthlyDeposit->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Member ID</p>
                        <p class="text-lg font-mono font-bold text-[#015425]">{{ $monthlyDeposit->member_id }}</p>
                    </div>
                    @if($monthlyDeposit->email)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email Address</p>
                        <p class="text-sm font-medium text-gray-900">{{ $monthlyDeposit->email }}</p>
                    </div>
                    @endif
                </div>
                <div class="space-y-4 md:text-right">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2 md:border-b-0 md:border-b text-right">Issuing Entity</h4>
                    <p class="text-lg font-bold text-gray-900">FEEDTAN DIGITAL</p>
                    <p class="text-sm text-gray-500">Community Microfinance Group</p>
                    <p class="text-sm text-gray-500">Statement Generated On: {{ now()->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Financial Breakdown Table -->
            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Contribution Breakdown</h4>
            <div class="rounded-2xl border border-gray-100 overflow-hidden mb-12">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount (TZS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">Monthly Savings (Akiba)</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($monthlyDeposit->savings, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">Shares (Hisa)</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($monthlyDeposit->shares, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">Welfare Fund (Mfuko wa Jamii)</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($monthlyDeposit->welfare, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">Loan Principal Repayment</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($monthlyDeposit->loan_principal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700">Loan Interest Repayment</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ number_format($monthlyDeposit->loan_interest, 2) }}</td>
                        </tr>
                        @if($monthlyDeposit->fine_penalty > 0)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-red-600">Fines / Penalties</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-red-600">{{ number_format($monthlyDeposit->fine_penalty, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-green-50">
                            <td class="px-6 py-6 text-base font-black text-[#015425] uppercase">Total Contribution</td>
                            <td class="px-6 py-6 text-right text-2xl font-black text-[#015425]">{{ number_format($monthlyDeposit->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- System Message -->
            @if($monthlyDeposit->generated_message)
            <div class="p-8 bg-blue-50 rounded-2xl border border-blue-100 mb-12">
                <h4 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-4">Message from External System</h4>
                <div class="text-blue-900 leading-relaxed font-medium">
                    {!! nl2br(e($monthlyDeposit->generated_message)) !!}
                </div>
            </div>
            @endif

            <!-- Notes Section -->
            @if($monthlyDeposit->notes)
            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 mb-12">
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Additional Notes</h4>
                <p class="text-gray-700">{{ $monthlyDeposit->notes }}</p>
            </div>
            @endif

            <!-- Footer Verification -->
            <div class="flex flex-col md:flex-row items-center justify-between pt-12 border-t border-gray-100 gap-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A9 9 0 1120.364 6.364l-1.42 1.419a7 7 0 10-9.9 9.9l1.414-1.414L10 18a8 8 0 1118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Digitally Verified</p>
                        <p class="text-xs text-gray-500">This statement is electronically generated and valid without signature.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Placeholder for branding/QR -->
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-dashed border-gray-300">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    #printable-area { border: none !important; shadow: none !important; }
    nav, aside, header, button, .no-print { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; width: 100% !important; }
    .max-w-4xl { max-width: 100% !important; }
    .rounded-3xl { border-radius: 0 !important; }
}
</style>
@endsection
