@extends('layouts.admin')

@section('page-title')
    Records for {{ date("F", mktime(0, 0, 0, $month, 10)) }} {{ $year }}
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.monthly-deposits.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
        <form action="{{ route('admin.monthly-deposits.destroy', ['year' => $year, 'month' => $month]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all records for this month?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-4 py-2 rounded-lg transition font-medium border border-red-100">
                Delete All Month Records
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Member ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Savings</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Shares</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Welfare</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Loan Prin.</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Loan Int.</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Special Msg</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PDF</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Linked</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($deposits as $deposit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $deposit->member_id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 truncate max-w-[150px]">{{ $deposit->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($deposit->savings, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($deposit->shares, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($deposit->welfare, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($deposit->loan_principal, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($deposit->loan_interest, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-bold text-[#015425]">{{ number_format($deposit->total, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold">
                            @if($deposit->generated_message)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full cursor-help" title="{{ $deposit->generated_message }}">Yes</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($deposit->statement_pdf)
                                <a href="{{ $deposit->statement_pdf }}" target="_blank" class="text-orange-600 hover:text-orange-900">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($deposit->user_id)
                                <span class="text-green-600" title="Linked to User Account">
                                    <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            @else
                                <span class="text-gray-300" title="Not linked to any registered user">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $deposits->links() }}
        </div>
    </div>
</div>
@endsection
