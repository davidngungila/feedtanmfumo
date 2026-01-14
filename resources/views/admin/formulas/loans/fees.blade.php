@extends('layouts.admin')

@section('page-title', 'Loan Fee Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Fee Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure loan fee calculation formulas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Processing Fee</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee Type</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <option>Percentage (%)</option>
                        <option>Fixed Amount</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee Rate/Amount</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * processing_fee_rate / 100</textarea>
                </div>
                <button class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Late Payment Penalty</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penalty Rate (%)</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">overdue_amount * penalty_rate / 100 * days_overdue / 30</textarea>
                </div>
                <button class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Insurance Fee</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Rate (%)</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">principal_amount * insurance_rate / 100</textarea>
                </div>
                <button class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Early Repayment Penalty</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penalty Rate (%)</label>
                    <input type="number" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">remaining_principal * early_repayment_rate / 100</textarea>
                </div>
                <button class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

