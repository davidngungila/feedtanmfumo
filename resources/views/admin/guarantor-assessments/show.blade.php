@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.guarantor-assessments.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            Back to List
        </a>
        <div class="flex space-x-3">
            @if($assessment->status !== 'approved')
            <form action="{{ route('admin.guarantor-assessments.approve', $assessment) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">Approve Assessment</button>
            </form>
            @endif
            @if($assessment->status === 'pending')
            <form action="{{ route('admin.guarantor-assessments.clarify', $assessment) }}" method="POST">
                @csrf
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition">Request Clarification</button>
            </form>
            @endif
            <a href="{{ route('admin.guarantor-assessments.download', $assessment) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Assessment Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">Guarantor Assessment Responses</h2>
                
                <div class="space-y-8">
                    <!-- SECTION 2 -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Borrower & Loan Purpose</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-xl">
                            <div class="field">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Primary Purpose</span>
                                <span class="text-sm text-gray-900 font-semibold">{{ $assessment->loan_purpose === 'Other' ? $assessment->loan_purpose_other : $assessment->loan_purpose }}</span>
                            </div>
                            <div class="field">
                                <span class="block text-xs font-medium text-gray-500 mb-1">History Reviewed?</span>
                                <span class="text-sm @if(str_contains($assessment->reviewed_history, 'No')) text-red-600 @else text-gray-900 @endif font-semibold">{{ $assessment->reviewed_history }}</span>
                            </div>
                            <div class="field">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Other Debts?</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $assessment->other_debts }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3 -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Financial Self-Assessment</h3>
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl border border-gray-100 @if($assessment->sufficient_savings === 'No') bg-red-50 @endif">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Sufficient Savings?</span>
                                <span class="text-sm font-bold @if($assessment->sufficient_savings === 'No') text-red-700 @else text-gray-900 @endif">{{ $assessment->sufficient_savings }}</span>
                            </div>
                            <div class="p-4 rounded-xl border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Impact on Obligations</span>
                                <span class="text-sm font-bold text-gray-900">{{ $assessment->financial_obligation_impact }}</span>
                            </div>
                            <div class="p-4 rounded-xl border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Other Guarantees</span>
                                <span class="text-sm font-bold text-gray-900">{{ $assessment->other_guarantees }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4 -->
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Understanding & Contingency</h3>
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl border @if($assessment->solely_responsible_understanding === 'I need clarification') border-orange-200 bg-orange-50 @else border-gray-100 @endif">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Liability Understanding</span>
                                <span class="text-sm font-bold @if($assessment->solely_responsible_understanding === 'I need clarification') text-orange-700 @else text-gray-900 @endif">{{ $assessment->solely_responsible_understanding }}</span>
                            </div>
                            <div class="p-4 rounded-xl border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Borrower Backup Plan</span>
                                <span class="text-sm font-bold text-gray-900">{{ $assessment->borrower_backup_plan }}</span>
                            </div>
                            <div class="p-4 rounded-xl border border-gray-100">
                                <span class="block text-xs font-medium text-gray-500 mb-1">Guarantor's Backup Plan</span>
                                <p class="text-sm text-gray-700 italic mt-2">"{{ $assessment->guarantor_backup_plan }}"</p>
                            </div>
                        </div>
                    </div>

                    @if($assessment->additional_comments)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Additional Comments</h3>
                        <div class="p-4 bg-blue-50 rounded-xl text-sm text-blue-900 border border-blue-100 italic">
                            {{ $assessment->additional_comments }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-gray-900 font-bold mb-4">Summary</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Agreement ID:</span>
                        <span class="font-mono font-bold">#{{ substr($assessment->ulid, -8) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Submitted:</span>
                        <span class="font-bold">{{ $assessment->submitted_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status:</span>
                        <span class="font-bold capitalize">{{ $assessment->status }}</span>
                    </div>
                    <hr class="border-gray-50">
                    <div>
                        <span class="text-gray-500 block mb-1">Guarantor:</span>
                        <a href="{{ route('admin.users.show', $assessment->guarantor) }}" class="text-blue-600 font-bold hover:underline">{{ $assessment->guarantor->name }}</a>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-1">Borrower:</span>
                        <a href="{{ route('admin.users.show', $assessment->loan->user) }}" class="text-blue-600 font-bold hover:underline">{{ $assessment->loan->user->name }}</a>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl mt-4">
                        <span class="text-gray-500 block mb-1 text-xs">LOAN AMOUNT</span>
                        <span class="text-lg font-bold text-gray-900">TZS {{ number_format($assessment->loan->principal_amount) }}</span>
                    </div>
                </div>
            </div>

            @if($assessment->status === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
                <div class="flex items-center text-green-800 font-bold mb-2">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                    Ready for Approval
                </div>
                <p class="text-xs text-green-700">This assessment is satisfactory. You can now proceed with the loan approval process if other requirements are met.</p>
                <a href="{{ route('admin.loans.show', $assessment->loan) }}" class="block text-center mt-4 text-xs font-bold text-green-800 hover:underline">Go to Loan Details &rarr;</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
