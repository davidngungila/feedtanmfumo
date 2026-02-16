@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Guarantor Assessments</h1>
            <p class="text-gray-600">Review and manage loan guarantee assessments</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Guarantor</th>
                        <th class="px-6 py-4 font-semibold">Borrower</th>
                        <th class="px-6 py-4 font-semibold">Loan Amount</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 italic">
                    @forelse($assessments as $assessment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $assessment->submitted_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $assessment->guarantor->name }}</div>
                            <div class="text-xs text-gray-500">{{ $assessment->guarantor->member_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $assessment->loan->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $assessment->loan->loan_number }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            TZS {{ number_format($assessment->loan->principal_amount) }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'clarification_needed' => 'bg-orange-100 text-orange-800',
                                ];
                                $class = $statusClasses[$assessment->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $class }}">
                                {{ str_replace('_', ' ', ucfirst($assessment->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm flex items-center space-x-3">
                            <a href="{{ route('admin.guarantor-assessments.show', $assessment) }}" class="text-blue-600 hover:text-blue-900 font-medium whitespace-nowrap">View Review</a>
                            <a href="{{ route('admin.guarantor-assessments.download', $assessment) }}" class="text-gray-400 hover:text-gray-600" title="Download PDF">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No assessments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assessments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $assessments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
