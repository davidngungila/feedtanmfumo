@extends('layouts.admin')

@section('page-title', 'Dividend Policy Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Dividend Policy Settings</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure dividend distribution policies</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Dividend Policy Configuration</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Payout Ratio (%) *</label>
                <input type="number" name="payout_ratio" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                <p class="text-xs text-gray-500 mt-1">Percentage of net profit to be distributed as dividends</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Reserve Requirement (%)</label>
                <input type="number" name="min_reserve" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <p class="text-xs text-gray-500 mt-1">Minimum percentage to retain as reserves before dividend distribution</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Calculation Method *</label>
                <select name="calculation_method" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    <option value="per_share">Per Share Basis</option>
                    <option value="percentage_of_profit">Percentage of Net Profit</option>
                    <option value="fixed_amount">Fixed Amount per Share</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Payment Frequency *</label>
                <select name="payment_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    <option value="annually">Annually</option>
                    <option value="semi_annually">Semi-Annually</option>
                    <option value="quarterly">Quarterly</option>
                </select>
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="allow_reinvestment" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <span class="ml-2 text-sm text-gray-700">Allow dividend reinvestment</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dividend Policy Statement</label>
                <textarea name="policy_statement" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Policy</button>
            </div>
        </form>
    </div>
</div>
@endsection

