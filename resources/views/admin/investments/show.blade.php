@extends('layouts.admin')

@section('page-title', 'Investment Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Investment Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $investment->investment_number }}</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.investments.edit', $investment) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Edit Investment
                </a>
                <a href="{{ route('admin.investments.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Investment Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Investment Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Investment Number</p>
                        <p class="text-lg font-semibold">{{ $investment->investment_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Plan Type</p>
                        <p class="text-lg font-semibold">{{ $investment->plan_type_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Principal Amount</p>
                        <p class="text-2xl font-bold text-[#015425]">{{ number_format($investment->principal_amount, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Interest Rate</p>
                        <p class="text-lg font-semibold">{{ $investment->interest_rate }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Expected Return</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($investment->expected_return, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Profit Share</p>
                        <p class="text-lg font-semibold text-orange-600">{{ number_format($investment->profit_share, 0) }} TZS</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Start Date</p>
                        <p class="text-lg font-semibold">{{ $investment->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Maturity Date</p>
                        <p class="text-lg font-semibold">{{ $investment->maturity_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ 
                            $investment->status === 'active' ? 'bg-green-100 text-green-800' : 
                            ($investment->status === 'matured' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')
                        }}">
                            {{ ucfirst($investment->status) }}
                        </span>
                    </div>
                    @if($investment->disbursement_date)
                    <div>
                        <p class="text-sm text-gray-600">Disbursement Date</p>
                        <p class="text-lg font-semibold">{{ $investment->disbursement_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Transaction History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($investment->transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold">{{ number_format($transaction->amount, 0) }} TZS</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ 
                                            $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                        }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Member Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Investor</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-semibold">{{ $investment->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $investment->user->email }}</p>
                    </div>
                </div>
            </div>

            @if($investment->notes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4">Notes</h3>
                <p class="text-gray-700">{{ $investment->notes }}</p>
            </div>
            @endif

            @if($investment->status === 'matured' && !$investment->disbursement_date)
            <div class="bg-blue-50 rounded-lg shadow-md p-6 border border-blue-200">
                <h3 class="text-lg font-bold text-blue-800 mb-2">Ready for Disbursement</h3>
                <p class="text-sm text-blue-700">This investment has matured and is ready for profit disbursement.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

