@extends('layouts.admin')

@section('page-title', 'Service Fee Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Service Fee Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure service fee calculations</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Loan Application Fee</h3>
            <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">
            <button class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Share Transfer Fee</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">transfer_amount * transfer_fee_rate / 100</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

