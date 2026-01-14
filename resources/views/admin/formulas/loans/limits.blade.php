@extends('layouts.admin')

@section('page-title', 'Loan Limits & Ratios')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Limits & Ratios</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure loan limits and eligibility ratios</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Maximum Loan Amount Formula</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Based on Savings</label>
                    <div class="mb-2">
                        <label class="text-xs text-gray-600">Multiplier</label>
                        <input type="number" step="0.1" value="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-xs">total_savings * savings_multiplier</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Based on Shares</label>
                    <div class="mb-2">
                        <label class="text-xs text-gray-600">Percentage</label>
                        <input type="number" step="0.1" value="80" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-xs">total_shares_value * shares_percentage / 100</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Based on Income</label>
                    <div class="mb-2">
                        <label class="text-xs text-gray-600">Ratio</label>
                        <input type="number" step="0.1" value="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-xs">monthly_income * income_ratio</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Debt-to-Income Ratio</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Ratio (%)</label>
                    <input type="number" step="0.1" value="40" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(total_monthly_loan_payments / monthly_income) * 100</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Loan-to-Value Ratio</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum LTV (%)</label>
                    <input type="number" step="0.1" value="80" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(loan_amount / collateral_value) * 100</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

