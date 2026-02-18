@extends('layouts.admin')

@section('page-title', 'Loan Statements - ' . date('F Y', mktime(0, 0, 0, $month, 10, $year)))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.loan-statements.index') }}" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h3 class="text-xl font-bold text-gray-900">Loan Statements for {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}</h3>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print List
            </button>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Records</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statements->total()) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Principal Paid</p>
            <p class="text-2xl font-bold text-[#015425]">TZS {{ number_format($statements->sum('principal_paid'), 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Interest Paid</p>
            <p class="text-2xl font-bold text-blue-600">TZS {{ number_format($statements->sum('interest_paid'), 2) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Outstanding Balance</p>
            <p class="text-2xl font-bold text-orange-600">TZS {{ number_format($statements->sum('closing_balance'), 2) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Opening Bal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Principal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Interest</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Closing Bal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($statements as $s)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $s->name }}</div>
                                <div class="text-xs font-mono text-gray-500 mt-0.5">{{ $s->member_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ number_format($s->opening_balance, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-700">
                                {{ number_format($s->principal_paid, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-blue-600 font-semibold">
                                {{ number_format($s->interest_paid, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap">
                                    {{ number_format($s->total_paid, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-orange-600">
                                {{ number_format($s->closing_balance, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No records found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($statements->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $statements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
