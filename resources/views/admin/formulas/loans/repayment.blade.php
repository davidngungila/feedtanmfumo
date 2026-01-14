@extends('layouts.admin')

@section('page-title', 'Repayment Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Repayment Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure loan repayment calculation formulas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">EMI Calculator</h3>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">
                    EMI = [P × r × (1+r)^n] ÷ [(1+r)^n - 1]
                </code>
            </div>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(principal * (rate/100/12) * Math.pow(1 + (rate/100/12), term)) / (Math.pow(1 + (rate/100/12), term) - 1)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Formula</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Principal Repayment</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">emi_amount - (outstanding_principal * (interest_rate / 100) / 12)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Formula</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Interest Repayment</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">outstanding_principal * (interest_rate / 100) / 12</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Formula</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Grace Period Interest</h3>
            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * (interest_rate / 100) * (grace_period_days / 365)</textarea>
            <button class="mt-4 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Formula</button>
        </div>
    </div>
</div>
@endsection

