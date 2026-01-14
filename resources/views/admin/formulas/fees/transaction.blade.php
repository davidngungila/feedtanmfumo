@extends('layouts.admin')

@section('page-title', 'Transaction Fee Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Transaction Fee Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure transaction fee calculations</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Deposit Fee</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">transaction_amount * deposit_fee_rate / 100</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Withdrawal Fee</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">transaction_amount * withdrawal_fee_rate / 100</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

