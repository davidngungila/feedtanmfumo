@extends('layouts.member')

@section('page-title', 'Savings Account Details')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">Savings Account Details</h1>
        <a href="{{ route('member.savings.index') }}" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center text-sm sm:text-base">
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-3 sm:mb-4">Account Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <p class="text-sm text-gray-600">Account Number</p>
                    <p class="text-lg font-semibold">{{ $saving->account_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Account Type</p>
                    <p class="text-lg font-semibold">{{ $saving->account_type_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Balance</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($saving->balance, 0) }} TZS</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Interest Rate</p>
                    <p class="text-lg font-semibold">{{ $saving->interest_rate }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="px-3 py-1 text-sm rounded-full {{ 
                        $saving->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    }}">
                        {{ ucfirst($saving->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Opening Date</p>
                    <p class="text-lg font-semibold">{{ $saving->opening_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-3 sm:mb-4">Transaction History</h3>
            <div class="space-y-2">
                @forelse($transactions->take(5) as $transaction)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm font-medium">{{ $transaction->transaction_type }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->transaction_date->format('M d, Y') }}</p>
                        </div>
                        <p class="text-sm font-semibold">{{ number_format($transaction->amount, 0) }} TZS</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No transactions yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

