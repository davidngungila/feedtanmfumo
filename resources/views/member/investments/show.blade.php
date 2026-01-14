@extends('layouts.member')

@section('page-title', 'Investment Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-[#015425]">Investment Details</h1>
        <a href="{{ route('member.investments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Investment Information</h2>
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
                <p class="text-sm text-gray-600">Expected Return</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($investment->expected_return, 0) }} TZS</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Profit Share</p>
                <p class="text-xl font-bold text-purple-600">{{ number_format($investment->profit_share, 0) }} TZS</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 text-sm rounded-full {{ 
                    $investment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                }}">
                    {{ ucfirst($investment->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Start Date</p>
                <p class="text-lg font-semibold">{{ $investment->start_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Maturity Date</p>
                <p class="text-lg font-semibold">{{ $investment->maturity_date->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

