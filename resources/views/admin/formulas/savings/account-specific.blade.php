@extends('layouts.admin')

@section('page-title', 'Account-Specific Savings Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Account-Specific Savings Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure formulas for different account types</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Emergency Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Penalty (%)</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <textarea rows="2" class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-xs">withdrawal_amount * penalty_rate / 100</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Balance Interest</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">Math.max(0, balance - minimum_balance) * interest_rate / 100 / 12</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Limit</label>
                    <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">RDA Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Contribution Formula</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">monthly_contribution * contribution_months</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Early Withdrawal Penalty</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">withdrawal_amount * early_withdrawal_penalty_rate / 100</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maturity Bonus</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">total_contribution * maturity_bonus_rate / 100</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Flex Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Variable Interest Formula</label>
                    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">balance * (base_rate + (balance_tier_bonus / 100)) / 100 / 12</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Average Balance Calculation</label>
                    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">sum(daily_balances) / days_in_period</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

