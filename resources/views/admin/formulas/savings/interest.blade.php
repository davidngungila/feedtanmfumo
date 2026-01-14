@extends('layouts.admin')

@section('page-title', 'Savings Interest Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Savings Interest Calculation Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure interest calculation for savings accounts</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Simple Interest</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">I = P × r × t</code>
            </div>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * interest_rate / 100 * (days_elapsed / 365)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Compound Interest</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">A = P(1 + r/n)^(nt)</code>
            </div>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * Math.pow(1 + (interest_rate / 100 / compounding_frequency), compounding_frequency * years)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Daily Compounding</h3>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">balance * Math.pow(1 + (annual_rate / 100 / 365), days_elapsed)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Monthly Compounding</h3>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">balance * Math.pow(1 + (annual_rate / 100 / 12), months_elapsed)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

