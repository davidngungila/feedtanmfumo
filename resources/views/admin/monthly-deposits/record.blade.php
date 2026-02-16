@extends('layouts.admin')

@section('page-title', 'Statement Details: ' . $monthlyDeposit->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.monthly-deposits.show', ['year' => $monthlyDeposit->year, 'month' => $monthlyDeposit->month]) }}" class="text-gray-600 hover:text-gray-900 flex items-center font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Monthly List
        </a>
        <div class="flex gap-3">
            @if($monthlyDeposit->statement_pdf)
            <a href="{{ $monthlyDeposit->statement_pdf }}" target="_blank" class="px-4 py-2 bg-orange-600 text-white rounded-lg font-bold hover:bg-orange-700 transition flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Open Official PDF
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Details Card -->
        <div class="lg:col-span-12">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 bg-gradient-to-r from-[#015425] to-[#027a3a] text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h2 class="text-3xl font-bold uppercase tracking-tight">{{ $monthlyDeposit->name }}</h2>
                            <p class="text-white text-opacity-80 font-medium">Statement for {{ $monthlyDeposit->month_name }} {{ $monthlyDeposit->year }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <span class="px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm font-bold border border-white border-opacity-30">
                                Member ID: {{ $monthlyDeposit->member_id }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Info Groups -->
                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none">Email Address</label>
                        <p class="text-lg font-bold text-gray-900 break-all">{{ $monthlyDeposit->email ?? 'Not Provided' }}</p>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none">Status</label>
                        <div class="flex items-center mt-1">
                            @if($monthlyDeposit->user_id)
                                <div class="flex items-center text-green-600 font-bold">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Linked to Account
                                </div>
                            @else
                                <div class="flex items-center text-gray-400 font-bold">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Not Linked
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none">Last Updated</label>
                        <p class="text-lg font-bold text-gray-900">{{ $monthlyDeposit->updated_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none">Statement ID</label>
                        <p class="text-lg font-mono font-bold text-[#015425]">#{{ str_pad($monthlyDeposit->id, 8, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 p-8">
                    <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Financial Breakdown</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Savings (Akiba)</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($monthlyDeposit->savings, 2) }}</span>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Shares (Hisa)</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($monthlyDeposit->shares, 2) }}</span>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Welfare (Jamii)</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($monthlyDeposit->welfare, 2) }}</span>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Loan Principal</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($monthlyDeposit->loan_principal, 2) }}</span>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Loan Interest</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($monthlyDeposit->loan_interest, 2) }}</span>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Fines / Penalty</span>
                            <span class="text-xl font-bold text-red-600">{{ number_format($monthlyDeposit->fine_penalty, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 p-8 bg-green-50 rounded-3xl border border-green-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <span class="text-xl font-black text-[#015425] uppercase">Total Contribution</span>
                        <span class="text-4xl font-black text-[#015425]">{{ number_format($monthlyDeposit->total, 2) }} TZS</span>
                    </div>
                </div>

                @if($monthlyDeposit->generated_message || $monthlyDeposit->notes)
                <div class="border-t border-gray-100 p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($monthlyDeposit->generated_message)
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-blue-400 uppercase tracking-widest">System Message</h4>
                        <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100 text-blue-900 font-medium leading-relaxed">
                            {!! nl2br(e($monthlyDeposit->generated_message)) !!}
                        </div>
                    </div>
                    @endif

                    @if($monthlyDeposit->notes)
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Additional Notes</h4>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 text-gray-700 leading-relaxed font-medium">
                            {{ $monthlyDeposit->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        @if($monthlyDeposit->statement_pdf)
        <!-- PDF Preview Card -->
        <div class="lg:col-span-12">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest">Official PDF Preview</h4>
                    <span class="text-xs text-gray-500">Links to Google Drive File</span>
                </div>
                <div class="relative w-full aspect-[4/3] bg-gray-100">
                    @php
                        // Check if it's a google drive link and try to convert to embed link if possible
                        // Example: https://drive.google.com/file/d/1EcJbSCMoyV8kFrgXuDFZjx3Ez3IzH6ib/view?usp=drivesdk
                        // Embed format: https://drive.google.com/file/d/1EcJbSCMoyV8kFrgXuDFZjx3Ez3IzH6ib/preview
                        $embedUrl = $monthlyDeposit->statement_pdf;
                        if (str_contains($embedUrl, 'drive.google.com')) {
                            $embedUrl = str_replace('/view', '/preview', $embedUrl);
                            // Strip usp if present
                            $embedUrl = preg_replace('/\?usp=.*$/', '', $embedUrl);
                        }
                    @endphp
                    <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay"></iframe>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
