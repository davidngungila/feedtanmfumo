@extends('layouts.admin')

@section('page-title', '6-Year Investment Plan Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">6-Year Investment Plan Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure formulas for 6-year investment plans</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Semi-Annual Compounding</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * Math.pow(1 + (interest_rate / 100 / 2), 2 * years)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Extended Term Bonus</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * extended_term_bonus_rate / 100</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Final Maturity Calculation</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * Math.pow(1 + (interest_rate / 100 / 2), 12) + extended_term_bonus</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Tax Deduction</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">profit_amount * tax_rate / 100</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

